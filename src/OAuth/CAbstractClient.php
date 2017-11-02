<?php

namespace ioext\wechat\OAuth;

use ioext\wechat\Common\Common;
use ioext\wechat\Common\CStateManager;

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

    /**
     * 授权作用域
     *
     * @return mixed
     */
    abstract public function ResolveScope();

    /**
     * 授权接口地址
     *
     * @return mixed
     */
    abstract public function ResolveAuthorizeUrl();

    /**
     * 设置scope
     *
     * @param $sScope
     */
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

        $arrQuery = [
            'appid'         =>  $this->m_sAppID,
            'redirect_uri'  =>  $this->m_sRedirectUri ?: Common::GetCurrentUrl(),
            'scope'         =>  $this->ResolveScope(),
            'state'         =>  $this->m_sState,
            'response_type' =>  'code'
        ];

        return $this->ResolveAuthorizeUrl()."?".http_build_query( $arrQuery );
    }


}