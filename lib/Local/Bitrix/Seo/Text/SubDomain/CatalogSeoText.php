<?php

namespace Local\Bitrix\Seo\Text\SubDomain;

use Local\Bitrix\Data\Request;
use Local\Bitrix\Seo\Text\MainDomain\CatalogSeoText as MainDomainCatalogSeoText;

class CatalogSeoText extends MainDomainCatalogSeoText
{
    const TEMPLATES_PATH = '/local/php_interface/include/seo_texts/catalog/subdomains/';
    
    /**
     * @return array|mixed
     */
    protected function getTemplateContent()
    {
        $file = $_SERVER['DOCUMENT_ROOT'].static::TEMPLATES_PATH.static::getSubDomain().static::TEMPLATE_FILE;

        if (is_file($file)) {
            return json_decode(file_get_contents($file), true);
        }
        return [];
    }

    /**
     * @return mixed
     */
    protected static function getSubDomain()
    {
        return current(explode('.', trim(Request::get()->getHttpHost(), '.'))).'/';
    }
}