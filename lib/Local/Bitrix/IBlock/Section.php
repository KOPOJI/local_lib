<?php

namespace Local\Bitrix\IBlock;

use Bitrix\Main\Data\Cache;
use Local\Bitrix\Base\IblockData;

/**
 * Class Section
 * @package Local\Bitrix\IBlock
 */
class Section extends IblockData
{

    const CLASS_NAME = '\\Bitrix\\IBlock\\SectionTable';
    const OLD_CLASS_NAME = '\\CIBlockSection';

    /**
     * @param $name
     * @param $arguments
     * @return bool
     */
    public static function __callStatic($name, $arguments)
    {
        if(method_exists(static::CLASS_NAME, $name))
            return call_user_func_array(array(static::CLASS_NAME, $name), $arguments);
        return false;
    }

    /**
     * Returns array of sections (with D7)
     *
     * @param $iblockId
     * @param array $params
     * @param bool $refreshCache
     * @return array
     */
    public static function getListD7($iblockId, array $params = array(), $refreshCache = false)
    {
        $args = static::getSelectParams($iblockId, $params);

        $args['arFilter']['IBLOCK_ID'] = $iblockId;
        if(!in_array('ID', $args['arSelect']))
            $args['arSelect'][] = 'ID';

        $className = static::CLASS_NAME;

        $cache = Cache::createInstance();
        $cacheId = md5(__FUNCTION__ . serialize($args));

        $result = [];

        if (!$refreshCache && $cache->initCache(static::$ttl, $cacheId, static::$cacheDir)) {
            $result = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            $res = $className::getList([
                'order' => $args['arOrder'],
                'filter' => $args['arFilter'],
                'select' => $args['arSelect'],
            ]);
            while($row = $res->fetch())
                $result[$row['ID']] = $row;

            $cache->endDataCache($result);
        }

        return $result;
    }

    /**
     * Returns array of sections (without D7)
     *
     * @param $iblockId
     * @param array $params
     * @param bool $refreshCache
     * @return array
     */
    public static function getList($iblockId, array $params = array(), $refreshCache = false)
    {
        $args = static::getSelectParams($iblockId, $params);

        $args['arFilter']['IBLOCK_ID'] = $iblockId;
        if(!in_array('ID', $args['arSelect']))
            $args['arSelect'][] = 'ID';

        $className = static::OLD_CLASS_NAME;

        $cache = Cache::createInstance();
        $cacheId = md5(__FUNCTION__ . serialize($args));

        $result = [];

        if (!$refreshCache && $cache->initCache(static::$ttl, $cacheId, static::$cacheDir)) {
            $result = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            $res = $className::GetList(
                $args['arOrder'], $args['arFilter'], $args['bInCnt'], $args['arSelect'], $args['arNavStartParams']
            );
            while($row = $res->GetNext())
                $result[$row['ID']] = $row;

            $cache->endDataCache($result);
        }

        return $result;
    }

    /**
     * Returns array of sections (with D7)
     *
     * @param $iblockId
     * @param array $params
     * @param bool $refreshCache
     * @return array
     */
    public static function getAllD7($iblockId, array $params = array(), $refreshCache = false)
    {
        $params['filter']['IBLOCK_ID'] = $iblockId;
        return static::getListD7($iblockId, $params, $refreshCache);
    }

    /**
     * Returns array of sections (without D7)
     *
     * @param int $iblockId
     * @param array $params
     * @param bool $refreshCache
     * @return array
     */
    public static function getAll($iblockId, array $params = array(), $refreshCache = false)
    {
        $params['filter']['IBLOCK_ID'] = $iblockId;
        return static::getList($iblockId, $params, $refreshCache);
    }
}