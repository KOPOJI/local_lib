<?php

namespace Local\Bitrix\Data;

use Local\Bitrix\Base\BitrixInstances;

/**
 * Class Server
 * @package Local\Bitrix\Data
 */
class Server extends BitrixInstances
{
    /**
     * @return void
     */
    public static function setInstance()
    {
        static::$instance = Context::get()->getServer();
    }

    /**
     * Returns instance of \Bitrix\Main\Server
     *
     * @return \Bitrix\Main\Server
     */
    public static function get()
    {
        return parent::get();
    }
}