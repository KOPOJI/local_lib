<?php

namespace Local\Bitrix\IBlock;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Data\Cache;
use Local\Bitrix\Base\IblockData;

\Bitrix\Main\Loader::includeModule('iblock');

/**
 * Class Element
 * @package Local\Bitrix\IBlock
 */
class Element extends IblockData
{
    const CLASS_NAME = '\\Bitrix\\IBlock\\ElementTable';
    const OLD_CLASS_NAME = '\\CIBlockElement';

    /**
     * @param $name
     * @param $arguments
     * @return bool
     */
    public static function __callStatic($name, $arguments)
    {
        if (method_exists(static::CLASS_NAME, $name)) {
            return call_user_func_array(array(static::CLASS_NAME, $name), $arguments);
        }
        return false;
    }

    /**
     * Get element by id
     *
     * @param $elementId
     * @param  array  $params
     * @return mixed
     */
    public static function getByIdD7($elementId, array $params = array())
    {
        $className = static::CLASS_NAME;
        return $className::getByPrimary($elementId, $params)->fetch();
    }

    /**
     * Get element by id
     *
     * @param $elementId
     * @param  array  $params
     * @return mixed
     */
    public static function getById($iblockId, $elementId, array $params = array())
    {
        $params['filter']['ID'] = (int) $elementId;
        return current(static::getAll($iblockId, $params));
    }

    /**
     * Returns array of products (without D7)
     *
     * @param $iblockId
     * @param  array  $params
     * @param  bool  $pagination
     * @return array
     */
    public static function getAll($iblockId, array $params = array(), $pagination = false)
    {
        $params['filter']['IBLOCK_ID'] = $iblockId;
        return $pagination ? static::getListPagination($iblockId, $params) : static::getList($iblockId, $params);
    }

    /**
     * Get array of products with pagination
     *
     * @param $iblockId
     * @param  array  $params
     * @return array
     */
    public static function getListPagination($iblockId, array $params = array())
    {
        $arOrder = static::getData($params, 'order', array('SORT' => 'ASC'));
        $arFilter = static::getData($params, 'filter', array('IBLOCK_ID' => $iblockId, 'ACTIVE' => 'Y'));
        $arGroupBy = static::getData($params, 'bIncCnt', false);
        $arNavStartParams = static::getData($params, 'arNavStartParams', false);
        $arSelect = static::getData($params, 'arSelect', array());

        $ret = array();
        $className = static::OLD_CLASS_NAME;
        $res = $className::GetList($arOrder, $arFilter, $arGroupBy, $arNavStartParams, $arSelect);
        $res->NavStart($arNavStartParams['nPageSize']);
        while ($row = $res->NavNext(true)) {
            $ret[$row['ID']] = $row;
        }

        //$ret['navPrint'] = $res->NavPrint($params['navPrintTitle']);

        return $ret;
    }

    /**
     * Get array of products (without D7)
     *
     * @param  int  $iblockId
     * @param  array  $params
     * @param  bool  $refreshCache
     * @return array
     */
    public static function getList($iblockId, array $params = array(), $refreshCache = false)
    {
        $arOrder = static::getData($params, 'order', array('SORT' => 'ASC'));
        $arFilter = static::getData($params, 'filter', array('IBLOCK_ID' => $iblockId, 'ACTIVE' => 'Y'));
        $arGroupBy = static::getData($params, 'bIncCnt', false);
        $arNavStartParams = static::getData($params, 'arNavStartParams', false);
        $arSelect = static::getData($params, 'arSelect', array());

        $arFilter['IBLOCK_ID'] = $iblockId;
        if (!in_array('ID', $arSelect)) {
            $arSelect[] = 'ID';
        }

        $className = static::OLD_CLASS_NAME;

        $result = [];
        $cache = Cache::createInstance();
        $cacheId = md5(serialize([
            __FUNCTION__, $className, $arOrder, $arFilter, $arGroupBy, $arNavStartParams, $arSelect
        ]));

        if (!$refreshCache && $cache->initCache(static::$ttl, $cacheId, static::$cacheDir)) {
            $result = $cache->getVars();
        } elseif ($cache->startDataCache()) {

            $res = $className::GetList($arOrder, $arFilter, $arGroupBy, $arNavStartParams, $arSelect);
            while ($row = $res->GetNext()) {
                $result[$row['ID']] = $row;
            }

            $cache->endDataCache($result);
        }

        return $result;
    }

    /**
     * Get array of products (with D7)
     *
     * @param  int  $iblockId
     * @param  array  $params
     * @param  bool  $refreshCache
     * @return array
     */
    public static function getListD7($iblockId, array $params = array(), $refreshCache = false)
    {
        $params['filter']['IBLOCK_ID'] = $iblockId;
        $params['filter']['ACTIVE'] = 'Y';
        if (!in_array('ID', $params['select'])) {
            $params['select'][] = 'ID';
        }

        $className = static::CLASS_NAME;

        $result = [];
        $cache = Cache::createInstance();
        $cacheId = md5(serialize([__FUNCTION__, $className, $params]));

        if (!$refreshCache && $cache->initCache(static::$ttl, $cacheId, static::$cacheDir)) {
            $result = $cache->getVars();
        } elseif ($cache->startDataCache()) {

            $res = $className::getList($params);
            while ($row = $res->fetch()) {
                $result[$row['ID']] = $row;
            }

            $cache->endDataCache($result);
        }

        return $result;
    }

    /**
     * Get count of products
     * 
     * @param $iblockId
     * @param  array  $params
     * @param  bool  $refreshCache
     * @return int
     */
    public static function getCount($iblockId, array $params = array(), $refreshCache = false)
    {
        $params['IBLOCK_ID'] = $iblockId;

        $cache = Cache::createInstance();
        $cacheId = md5(serialize([__FUNCTION__, $params]));

        $result = 0;
        if (!$refreshCache && $cache->initCache(static::$ttl, $cacheId, static::$cacheDir)) {
            $result = $cache->getVars();
        } elseif ($cache->startDataCache()) {

            $className = static::CLASS_NAME;

            $result = $className::getCount($params);

            $cache->endDataCache($result);
        }

        return $result;
    }

    /**
     * Returns array of products (with D7)
     *
     * @param  int  $iblockId
     * @param  array  $params
     * @param  bool  $refreshCache
     * @return array
     */
    public static function getAllD7($iblockId, array $params = array(), $refreshCache = false)
    {
        $params['filter']['IBLOCK_ID'] = $iblockId;
        $className = static::CLASS_NAME;
        $res = $className::getList($params);
        $ret = array();
        while ($row = $res->fetch()) {
            $ret[$row['ID']] = $row;
        }
        return $ret;
    }
}