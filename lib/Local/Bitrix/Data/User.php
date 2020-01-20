<?php

namespace Local\Bitrix\Data;

use Local\Bitrix\Base\GlobalVars;

/**
 * Class User
 * @package Local\Bitrix\Data
 */
class User extends GlobalVars
{
    /**
     * @return mixed|void
     */
    public static function setVarName()
    {
        static::$varName = 'USER';
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public static function isGuest()
    {
        return !static::isAuthed();
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public static function isAuthed()
    {
        return static::get()->IsAuthorized();
    }

    /**
     * Returns instance of \CUser
     *
     * @return \CUser
     * @throws \Exception
     */
    public static function get()
    {
        return parent::get();
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public static function isAdmin()
    {
        $user = static::get();
        return $user->IsAuthorized() && $user->IsAdmin();
    }
}