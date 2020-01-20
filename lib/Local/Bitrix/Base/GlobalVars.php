<?php

namespace Local\Bitrix\Base;


/**
 * Class GlobalVars
 * @package Local\Bitrix\Base
 */
abstract class GlobalVars
{
    /**
     * @var null
     */
    protected static $object = null;

    /**
     * @var null
     */
    protected static $varName = null;

    /**
     * Returns object of current class from $GLOBALS
     *
     * @return Object
     * @throws \Exception
     */
    public static function get()
    {
        if (static::$object === null) {
            static::setVarName();

            if (empty(static::$varName)) {
                throw new \Exception('Empty global variable name!');
            }
            if (empty($GLOBALS[static::$varName])) {
                throw new \Exception('Empty global array with this name ('.static::$varName.')!');
            }
            static::$object = $GLOBALS[static::$varName];
        }

        return static::$object;
    }

    /**
     * @return mixed
     */
    abstract static function setVarName();

    /**
     * Call method of current object
     *
     * @param $name
     * @param $arguments
     * @return bool
     */
    public function __call($name, $arguments)
    {
        if (method_exists(static::$object, $name)) {
            return static::$object->$name($arguments);
        }
        return false;
    }
}