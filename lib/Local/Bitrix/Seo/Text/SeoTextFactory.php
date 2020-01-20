<?php

namespace Local\Bitrix\Seo\Text;


use Local\Bitrix\Data\Request;
use Local\Bitrix\IBlock\Element;

class SeoTextFactory
{
    const MAIN_DOMAIN = 'site.ru';
    const CATALOG_ILLS_IBLOCK_CODE = 5;
    protected static $request = null;
    protected static $currentUri = null;

    /**
     * @param  null  $uri
     * @return MainDomain\CatalogIllsSeoText|MainDomain\CatalogSeoText|MainDomain\DetailSeoText|SubDomain\CatalogIllsSeoText|SubDomain\CatalogSeoText|SubDomain\DetailSeoText
     */
    public static function create($uri = null)
    {
        if (empty($uri)) {
            $uri = static::getCurrentUri();
        }
        static::$currentUri = $uri;

        $uriParts = static::splitUri();

        //check for ills
        if (static::isCatalogIll(end($uriParts))) {
            return static::isInSubDomain()
                ? static::createSubDomainCatalogIllsSeoText($uri)
                : static::createCatalogIllsSeoText($uri);
        }

        //check for subdomains
        if (static::isInSubDomain()) {
            return static::isDetail($uriParts)
                ? static::createSubDomainDetailSeoText($uri)
                : static::createSubDomainCatalogSeoText($uri);
        }

        //main domain, not ills
        return static::isDetail($uriParts)
            ? static::createDetailSeoText($uri)
            : static::createCatalogSeoText($uri);
    }

    /**
     * @param $uri
     * @return MainDomain\CatalogSeoText
     */
    public static function createCatalogSeoText($uri)
    {
        return new MainDomain\CatalogSeoText($uri);
    }

    /**
     * @param $uri
     * @return MainDomain\CatalogIllsSeoText
     */
    public static function createCatalogIllsSeoText($uri)
    {
        return new MainDomain\CatalogIllsSeoText($uri);
    }

    /**
     * @param $uri
     * @return MainDomain\DetailSeoText
     */
    public static function createDetailSeoText($uri)
    {
        return new MainDomain\DetailSeoText($uri);
    }

    /**
     * @param $uri
     * @return SubDomain\CatalogSeoText
     */
    public static function createSubDomainCatalogSeoText($uri)
    {
        return new SubDomain\CatalogSeoText($uri);
    }

    /**
     * @param $uri
     * @return SubDomain\CatalogIllsSeoText
     */
    public static function createSubDomainCatalogIllsSeoText($uri)
    {
        return new SubDomain\CatalogIllsSeoText($uri);
    }

    /**
     * @param $uri
     * @return SubDomain\DetailSeoText
     */
    public static function createSubDomainDetailSeoText($uri)
    {
        return new SubDomain\DetailSeoText($uri);
    }

    /**
     * @return null
     */
    protected static function getRequest()
    {
        if (empty(static::$request)) {
            static::$request = Request::get();
        }
        return static::$request;
    }

    /**
     * @return mixed
     */
    protected static function getCurrentUri()
    {
        return static::getRequest()->getRequestedPageDirectory();
    }

    /**
     * @return bool
     */
    protected static function isInSubDomain()
    {
        return static::getRequest()->getHttpHost() !== static::MAIN_DOMAIN;
    }

    /**
     * @param  array  $uriParts
     * @return bool
     */
    protected static function isDetail(array $uriParts)
    {
        return count($uriParts) === 3;
    }

    /**
     * @param $code
     * @return bool
     */
    protected static function isCatalogIll($code)
    {
        $id = preg_replace('~\\D+~', '', $code);

        if (empty($id)) {
            return false;
        }

        return (bool) Element::getCount(static::CATALOG_ILLS_IBLOCK_CODE, ['ACTIVE' => 'Y', 'ID' => $id]);
    }

    /**
     * @return array
     */
    private static function splitUri()
    {
        return explode('/', trim(static::$currentUri, '/'));
    }
}