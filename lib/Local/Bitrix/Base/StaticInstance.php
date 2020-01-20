<?php

namespace Local\Bitrix\Base;


/**
 * Class StaticInstance
 * @package Local\Bitrix\Base
 */
abstract class StaticInstance
{
    /**
     * @var static instance of object
     */
    protected static $instance = null;

    /**
     * Returns static instance of current object
     *
     * @return $this
     */
    public static function get()
    {
        return new static();
    }

    /**
     * Returns singleton static instance of current object
     *
     * @return $this
     */
    public static function getSingleton()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }
}