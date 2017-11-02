<?php
namespace ioext\wechat\Common;


class Common
{
    /**
     *
     */
    public static function GetCurrentUrl()
    {
        $sProtocol = (isset($_SERVER['HTTPS']) && ('off' !== $_SERVER['HTTPS'] || 443 == $_SERVER['SERVER_PORT']))
            ? 'https://' : 'http://';

        return $sProtocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }

    /**
     * 获取随机字符
     */
    public static function GetRandomString( $nLength = 10 )
    {
        $sPool = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

        return substr(str_shuffle(str_repeat($sPool, ceil($nLength / strlen($sPool)))), 0, $nLength);
    }
}