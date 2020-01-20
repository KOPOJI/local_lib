<?php

namespace Local\Bitrix\Seo\Text\MainDomain;

use Local\Bitrix\IBlock\Section;
use Local\Bitrix\Seo\Text\SeoTextContentInterface;
use Local\Bitrix\Seo\Text\SeoTextInterface;
use Local\Bitrix\Seo\Text\SeoTextContent;

class CatalogSeoText implements SeoTextInterface
{
    const TEMPLATES_PATH = '/local/php_interface/include/seo_texts/catalog/';
    const TEMPLATE_FILE = 'list.json';

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
        $sectionCode = array_pop($uriParts);

        if (!($section = $this->getSection($sectionCode))) {
            return new SeoTextContent();
        }

        return new SeoTextContent($this->getTemplateContent(), ['category_name' => $section['NAME']]);
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
     * @param $sectionCode
     * @return bool|mixed
     */
    protected function getSection($sectionCode)
    {
        $sections = Section::getListD7(static::IBLOCK_ID, [
            'filter' => ['CODE' => $sectionCode, 'ACTIVE' => 'Y',],
            'arSelect' => ['ID', 'NAME', 'CODE'],
        ], true);
        if (empty($sections) || !(($section = current($sections)))) {
            return false;
        }
        return $section;
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