<?php


namespace Local\Bitrix\Base;


interface CacheInterface
{
    public static function createInstance();
    
    public function initCache($TTL, $uniqueString, $initDir = false, $baseDir = 'cache');
    public function startDataCache($TTL = false, $uniqueString = false, $initDir = false, $vars = array(), $baseDir = 'cache');
    public function endDataCache($vars=false);
    
    public function getVars();
}