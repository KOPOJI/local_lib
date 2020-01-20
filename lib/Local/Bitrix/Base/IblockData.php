<?php

namespace Local\Bitrix\Base;

\Bitrix\Main\Loader::includeModule('iblock');

/**
 * Class IblockData
 * @package Local\Bitrix\Base
 */
abstract class IblockData
{
    protected static $ttl = 3600;
    protected static $cacheDir = 'bitrixIBlocks';

    /**
     * @param $iblockId
     * @param  array  $params
     * @param  bool  $refreshCache
     * @return mixed
     */
    abstract static function getListD7($iblockId, array $params = array(), $refreshCache = false);

    /**
     * @param $iblockId
     * @param  array  $params
     * @param  bool  $refreshCache
     */
    abstract static function getList($iblockId, array $params = array(), $refreshCache = false);

    /**
     * @param $iblockId
     * @param  array  $params
     * @param  bool  $refreshCache
     * @return mixed
     */
    abstract static function getAllD7($iblockId, array $params = array(), $refreshCache = false);

    /**
     * @param $iblockId
     * @param  array  $params
     * @param  bool  $refreshCache
     * @return mixed
     */
    abstract static function getAll($iblockId, array $params = array(), $refreshCache = false);

    /**
     * @param $iblockId
     * @param $params
     * @return array
     */
    public static function getSelectParams($iblockId, $params)
    {
        return [
            'arOrder' => static::getData($params, 'order', array('SORT' => 'ASC')),
            'arFilter' => static::getData($params, 'filter', array('IBLOCK_ID' => $iblockId, 'ACTIVE' => 'Y')),
            'bIncCnt' => static::getData($params, 'bIncCnt', false),
            'arSelect' => static::getData($params, 'arSelect', array()),
            'arNavStartParams' => static::getData($params, 'arNavStartParams', false),
        ];
    }

    /**
     * @param  array  $params
     * @param $key
     * @param  null  $defValue
     * @return mixed|null
     */
    protected static function getData(array $params, $key, $defValue = null)
    {
        return isset($params[$key]) ? $params[$key] : $defValue;
    }
}