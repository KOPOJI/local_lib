<?php

namespace Local\Bitrix\Data;

use Local\Bitrix\Base\BitrixInstances;

/**
 * Class Context
 * @package Local\Bitrix\Data
 */
class Context extends BitrixInstances
{
    /**
     * @return mixed|void
     */
    public static function setInstance()
    {
        static::$instance = \Bitrix\Main\Application::getInstance()->getContext();
    }

    /**
     * Returns instance of \Bitrix\Main\Context
     *
     * @return \Bitrix\Main\Context
     */
    public static function get()
    {
        return parent::get();
    }
}