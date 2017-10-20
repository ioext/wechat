<?php
namespace ioext\wechat\OAuth;

class Client
{
    /**
     * 客户端授权url
     *
     * @return string
     */
    public function GetAuthorizeUrl()
    {
        return 'https://open.weixin.qq.com/connect/oauth2/authorize';
    }

    public function GetScope()
    {
        return $this->sScope ? : "snsapi_base";
    }

}