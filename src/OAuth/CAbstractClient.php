<?php

namespace ioext\wechat\OAuth;

use ioext\wechat\Common\Common;

abstract class CAbstractClient
{
    // AccessToken Url
    const ACCESS_TOKEN_URL = "https://api.weixin.qq.com/sns/oauth2/access_token";

    protected $m_sAppID;

    protected $m_sAppSecret;

    protected $m_sScope;

    protected $m_sState;

    protected $m_sRedirectUri;

    protected $mc_sStateManager;

    public function __construct( $sAppID, $sAppSecret )
    {
        $this->m_sAppID         =   $sAppID;
        $this->m_sAppSecret     =   $sAppSecret;
        $this->mc_sStateManager  =   new CStateManager();
    }

    public function SetScope( $sScope )
    {
        $this->m_sScope = $sScope;
    }

    public function SetState( $sState )
    {
        $this->m_sState = $sState;
    }

    public function SetRedirectUri( $sRedirectUrl )
    {
        $this->m_sRedirectUri = $sRedirectUrl;
    }

    public function GetAuthorizeUrl()
    {
        if( null === $this->m_sState )
        {
            $this->m_sState = Common::GetRandomString( 16 );
        }

        $this->mc_sStateManager->SetState( $this->m_sState );
    }
}