<?php

namespace Local\Bitrix\Data;

use Local\Bitrix\Base\BitrixInstances;

/**
 * Class Request
 * @package Local\Bitrix\Data
 */
class Request extends BitrixInstances
{
    /**
     * @return void
     */
    public static function setInstance()
    {
        static::$instance = Context::get()->getRequest();
    }

    /**
     * Returns instance of \Bitrix\Main\HttpRequest
     *
     * @return \Bitrix\Main\HttpRequest
     */
    public static function get()
    {
        return parent::get();
    }
}