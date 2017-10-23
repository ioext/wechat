<?php
namespace ioext\wechat\OAuth;

class AccessToken
{
    protected $m_sAppId;
    /**
     * AccessToken constructor.
     */
    public function __construct()
    {
    }

    public function GetAppID()
    {
        return $this->m_sAppId;
    }

    public function GetAppSecret()
    {
        return $this->m_sAppId;
    }

}