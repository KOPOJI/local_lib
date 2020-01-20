<?php

namespace Local\Bitrix\Seo\Text\MainDomain;

use Local\Bitrix\IBlock\Element;
use Local\Bitrix\IBlock\Section;
use Local\Bitrix\Seo\Text\SeoTextContent;
use Local\Bitrix\Seo\Text\SeoTextContentInterface;
use Local\Bitrix\Seo\Text\SeoTextInterface;

class DetailSeoText implements SeoTextInterface
{
    const TEMPLATES_PATH = '/local/php_interface/include/seo_texts/catalog/';
    const TEMPLATE_FILE = 'detail.json';

    const IBLOCK_ID = 2;

    protected $uri = null;

    /**
     * CatalogSeoText constructor.
     * @param  string|null  $uri
     */
    public function __construct($uri = null)
    {
        $this->uri = $uri;
    }

    /**
     * @return SeoTextContentInterface
     */
    public function getContent()
    {
        $uriParts = explode('/', trim($this->uri, '/'));
        $elementCode = array_pop($uriParts);
        $sectionCode = array_pop($uriParts);

        if (!($section = $this->getSection($sectionCode)) || !($element = $this->getElement($elementCode))) {
            return new SeoTextContent();
        }

        return new SeoTextContent($this->getTemplateContent(), [
            'category_name' => $section['NAME'],
            'element_name' => $element['NAME'],
            'price' => number_format($element['CATALOG_PRICE_3']).' руб.',
        ]);
    }

    /**
     * @param $sectionCode
     * @return bool|mixed
     */
    protected function getSection($sectionCode)
    {
        $sections = Section::getListD7(static::IBLOCK_ID, [
            'filter' => ['CODE' => $sectionCode, 'ACTIVE' => 'Y',],
            'arSelect' => ['ID', 'NAME', 'CODE'],
        ]);
        if (empty($sections) || !(($section = current($sections)))) {
            return false;
        }
        return $section;
    }

    /**
     * @param $elementCode
     * @return bool|mixed
     */
    protected function getElement($elementCode)
    {
        $elements = Element::getList(static::IBLOCK_ID, [
            'filter' => ['CODE' => $elementCode, 'ACTIVE' => 'Y',],
            'arSelect' => ['ID', 'NAME', 'CODE', 'CATALOG_GROUP_3'],
        ]);
        if (empty($elements) || !(($element = current($elements)))) {
            return false;
        }
        return $element;
    }

    /**
     * @return array|mixed
     */
    protected function getTemplateContent()
    {
        $file = $_SERVER['DOCUMENT_ROOT'].static::TEMPLATES_PATH.static::TEMPLATE_FILE;
        if (is_file($file)) {
            return json_decode(file_get_contents($file), true);
        }
        return [];
    }

    /**
     * @return string|null
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param  string|null  $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }
}