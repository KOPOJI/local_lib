<?php


namespace Local\Bitrix\Seo\Text\MainDomain;


use Local\Bitrix\IBlock\Element;

class CatalogIllsSeoText extends CatalogSeoText
{
    const TEMPLATE_FILE = 'ills.json';

    const IBLOCK_ID = 5;
    
    /**
     * @param $sectionCode
     * @return bool|mixed
     */
    protected function getSection($sectionCode)
    {
        $id = preg_replace('~\\D+~', '', $sectionCode);
        if (empty($id)) {
            return false;
        }

        $elements = Element::getListD7(static::IBLOCK_ID, [
            'filter' => ['ID' => $id, 'ACTIVE' => 'Y',],
            'select' => ['ID', 'NAME'],
        ]);

        if (empty($elements) || !(($element = current($elements)))) {
            return false;
        }
        return $element;
    }
}