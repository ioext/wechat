<?php

namespace ioext\wechat\OAuth;

abstract class CAbstractClient
{
    // AccessToken Url
    const ACCESS_TOKEN_URL = "https://api.weixin.qq.com/sns/oauth2/access_token";

    protected $m_sAppID;

    protected $m_sAppSecret;

    protected $m_sScope;

    protected $m_sState;

    protected $m_sRedirectUrl;

    protected $m_sStateManager;

    public function __construct( $sAppID, $sAppSecret )
    {
        $this->m_sAppID         =   $sAppID;
        $this->m_sAppSecret     =   $sAppSecret;
        $this->m_sStateManager  =   new CStateManager();
    }

    public function SetScope( $sScope )
    {
        $this->m_sScope = $sScope;
    }

    public function SetState( $sState )
    {

    }
}