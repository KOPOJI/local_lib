<?php

namespace Local\Bitrix\Data;

use Local\Bitrix\Base\GlobalVars;

/**
 * Class App
 * @package Local\Bitrix\Data
 */
class App extends GlobalVars
{
    /**
     * set global variable name
     *
     * @return void
     */
    public static function setVarName()
    {
        static::$varName = 'APPLICATION';
    }

    /**
     * Returns instance of \Bitrix\Main\Application
     *
     * @return \Bitrix\Main\Application
     * @throws \Exception
     */
    public static function get()
    {
        return parent::get();
    }
}