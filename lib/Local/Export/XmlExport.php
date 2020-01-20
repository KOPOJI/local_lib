<?php

namespace Local\Export;

use Bitrix\Main\IO\Directory;
use Local\Bitrix\Data\Server;
use Local\Bitrix\IBlock\Element;
use Local\Helpers\BaseHelper as Helper;

class XmlExport
{
    CONST DEFAULT_DIR = '/bitrix/catalog_export/export';

    protected $serverUrl = null;
    protected $storageDir = null;
    protected $productsFile = null;
    protected $sectionsFile = null;
    protected $additionalDataFile = null;

    protected $iblockId = null;
    protected $productIds = array();
    protected $sectionIds = array();

    protected $products = array();
    protected $sections = array();
    protected $additionalData = array();

    protected $needUpdate = false;

    protected $time = null;

    /**
     * XmlExport constructor.
     *
     * @param $iblockId
     * @param  array  $params
     */
    public function __construct($iblockId, array $params = array())
    {
        $this->iblockId = $iblockId;

        $this->storageDir = rtrim(Server::get()->getDocumentRoot().(isset($params['storageDir']) ? $params['storageDir'] : static::DEFAULT_DIR),
            '/');
        if (!is_dir($this->storageDir)) {
            Directory::createDirectory($this->storageDir);
        }
        $this->productsFile = $this->storageDir.'/products.json';
        $this->sectionsFile = $this->storageDir.'/sections.json';
        $this->additionalDataFile = $this->storageDir.'/additionalData.json';

        $this->serverUrl = rtrim((isset($params['serverUrl']) ? $params['serverUrl'] : Helper::getFullUrl('')), '/');
        if (isset($params['productIds'])) {
            $this->productIds = (array) $params['productIds'];
        }
        if (isset($params['sectionIds'])) {
            $this->sectionIds = (array) $params['sectionIds'];
        }
        if (isset($params['additionalData'])) {
            $this->additionalData = (array) $params['additionalData'];
        }
    }

    /**
     * Load data from iblock or cached file (override to change)
     *
     * @param  array  $params
     * @param  bool  $return
     * @return array
     */
    public function loadData(array $params = array(), $return = false)
    {
        if ($this->isNeedUpdate()) {
            \Bitrix\Main\Loader::includeModule('iblock');

            $this->products = array();

            $this->loadSections();

            $this->loadAdditionalData();


            $data = Element::getAll($this->iblockId, $params);
            $i = 0;
            foreach ($data as $info) {if(isset($_GET['dump'])){var_dump($params, $info);exit;}

                $info['PREVIEW_PICTURE'] = $this->serverUrl.\CFile::GetPath($info['PREVIEW_PICTURE']);
                $info['PREVIEW_PAGE_URL'] = $this->serverUrl.$info['PREVIEW_PAGE_URL'];
                if (!empty($info['PREVIEW_TEXT'])) {
                    $info['PREVIEW_TEXT'] = preg_replace('~\\R+|&nbsp;~iu', ' ', HTMLToTxt($info['PREVIEW_TEXT']));
                }
                if (!empty($info['DETAIL_TEXT'])) {
                    $info['DETAIL_TEXT'] = preg_replace('~\\R+|&nbsp;~iu', ' ',
                        HTMLToTxt(strip_tags($info['DETAIL_TEXT'])));
                }

                if (isset($params['callback']) && $params['callback'] instanceof \Closure) {
                    $info = $params['callback']($info);
                }
                $this->products[$info['ID']] = $info;

                if (isset($params['limit']) && ++$i === $params['limit']) {
                    break;
                }
            }

            file_put_contents($this->productsFile, json_encode($this->products, JSON_UNESCAPED_UNICODE));
            file_put_contents($this->sectionsFile, json_encode($this->sections, JSON_UNESCAPED_UNICODE));
        } else {
            $this->sections = json_decode(file_get_contents($this->sectionsFile), true);
            $this->products = json_decode(file_get_contents($this->productsFile), true);
        }
        if ($return) {
            return array(
                'sections' => $this->sections,
                'products' => $this->products,
            );
        }
    }

    /**
     * Returns is need to update or not
     *
     * @return bool
     */
    protected function isNeedUpdate()
    {
        $this->needUpdate = !is_file($this->sectionsFile) || !is_file($this->productsFile) || !is_file($this->additionalDataFile) || isset($_GET['update']);
        if (!$this->needUpdate) {
            $this->time = filectime($this->sectionsFile);
            if (date('Ymd', $this->time) != date('Ymd')) {
                $this->needUpdate = true;
            }
        }
        return $this->needUpdate;
    }

    /**
     * Set need update or not
     *
     * @param $additional
     * @return bool
     */
    public function setNeedUpdate($additional)
    {
        return $this->needUpdate = $this->needUpdate || $additional;
    }

    /**
     * Load sections from IBlock or cached file (override for change)
     *
     * @return void
     */
    protected function loadSections()
    {
        $this->sections = array();

        $res = \CIBlockSection::GetList(array(), array('IBLOCK_ID' => $this->iblockId, 'ID' => $this->sectionIds));
        while ($row = $res->fetch()) {
            $this->sections[$row['ID']] = array('name' => $row['NAME']);
            $rs = \CIBlockSection::GetList(
                array('LEFT_MARGIN' => 'ASC'),
                array(
                    'IBLOCK_ID' => $this->iblockId,
                    '>LEFT_MARGIN' => $row['LEFT_MARGIN'],
                    '<RIGHT_MARGIN' => $row['RIGHT_MARGIN'],
                    '!ID' => $row['ID']
                )
            );
            while ($innerRow = $rs->fetch()) {
                $this->sections[$innerRow['ID']] = array('name' => $innerRow['NAME'], 'parentId' => $row['ID']);
            }
        }
    }

    /**
     * Set additional export params
     *
     * @param  array  $additionalData
     */
    public function loadAdditionalData(array $additionalData = array())
    {
        $this->additionalData = $additionalData;
    }

    /**
     * Returns array of additional export params
     *
     * @return array
     */
    public function getAdditionalData()
    {
        return $this->additionalData;
    }

    /**
     * Output generated content
     *
     * @TODO add xml struct
     *
     * @param  array  $params
     * @return mixed
     */
    public function showData(array $params = array())
    {
        if (!empty($params['sendHeader'])) {
            header('Content-type: text/xml; charset='.SITE_CHARSET);
        }

        ?>
        <<?php ?>?xml version="1.0" encoding="<?=SITE_CHARSET?>"?>
        ...
        <?php
    }

    /**
     * Returns array of selected products
     *
     * @return array
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Returns array of selected sections
     *
     * @return array
     */
    public function getSections()
    {
        return $this->sections;
    }
}