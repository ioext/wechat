<?php
namespace ioext\wechat\Common;


class Common
{
    /**
     * 获取随机字符
     */
    public static function GetRandomString( $nLength = 10 )
    {
        $sPool = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

        return substr(str_shuffle(str_repeat($sPool, ceil($nLength / strlen($sPool)))), 0, $nLength);
    }
}