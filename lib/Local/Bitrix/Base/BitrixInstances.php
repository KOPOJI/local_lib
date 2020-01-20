<?php

namespace Local\Bitrix\Base;


/**
 * Class BitrixInstances
 * @package Local\Bitrix\Base
 */
abstract class BitrixInstances
{
    /**
     * @var instance for static singleton call
     */
    protected static $instance = null;

    /**
     * Returns object of current class
     *
     * @return Object
     */
    public static function get()
    {
        static::setInstance();

        return static::$instance;
    }

    /**
     * @return mixed
     */
    abstract static function setInstance();
}