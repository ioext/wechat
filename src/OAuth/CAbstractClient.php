<?php

namespace ioext\wechat\OAuth;

use ioext\wechat\Common\Common;
use ioext\wechat\Common\CStateManager;
use ioext\wechat\Common\Http;

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

    /**
     * 设置state
     *
     * @param $sState
     */
    public function SetState( $sState )
    {
        $this->m_sState = $sState;
    }

    /**
     * 设置redirectUri
     *
     * @param $sRedirectUrl
     */
    public function SetRedirectUri( $sRedirectUrl )
    {
        $this->m_sRedirectUri = $sRedirectUrl;
    }

    /**
     * 获取authorizeUrl
     *
     * @return string
     */
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

    /**
     * 通过code获得access token
     *
     * @param $sCode
     * @param null $sState
     * @throws \Exception
     */
    public function GetAccessToken( $sCode, $sState = null )
    {
        if( null ===  $sState && ! isset( $_GET['state'] ) )
        {
            throw new \Exception('Invalid Request');
        }

        $sState = $sState ? : $_GET['state'];
        $sState = $sState ? : $_GET['state'];
        $sState = $sState ? : $_GET['state'];
        $sState = $sState ? : $_GET['state'];
        $sState = $sState ? : $_GET['state'];
        $sState = $sState ? : $_GET['state'];
        $sState = $sState ? : $_GET['state'];
        $sState = $sState ? : $_GET['state'];
        $sState = $sState ? : $_GET['state'];
        $sState = $sState ? : $_GET['state'];
        $sState = $sState ? : $_GET['state'];
        $sState = $sState ? : $_GET['state'];
        $sState = $sState ? : $_GET['state'];
        $sState = $sState ? : $_GET['state'];
        $sState = $sState ? : $_GET['state'];
        $sState = $sState ? : $_GET['state'];
        $sState = $sState ? : $_GET['state'];

        if( ! $this->mc_sStateManager->IsValidState( $sState ))
        {
            throw new \Exception( sprintf( 'Invalid Authentication State "%S"', $sState ) );
        }

        $arrQuery = [
            'appid'         =>  $this->m_sAppID,
            'secret'        =>  $this->m_sAppSecret,
            'code'          =>  $sCode,
            'grant_type'    =>  'authorization_code',
        ];

        $arrResponse = Http::request( 'GET', static::ACCESS_TOKEN_URL )
            ->withQuery( $arrQuery )
            ->send();

        if( 0 != $arrResponse['errcode'] )
        {
            throw new \Exception( $arrResponse['errmsg'], $arrResponse['errcode'] );
        }

        return new AccessToken( $this->m_sAppID, $arrResponse );
    }


}