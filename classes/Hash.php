<?php

class Hash
{
    public static function make($string)
    {
        return password_hash($string, PASSWORD_BCRYPT);
    }

//    public static function salt($length)
//    {
//        return utf8_encode(mcrypt_create_iv($length));
//    }

    public static function unique()
    {
        return self::make(uniqid());
    }
}