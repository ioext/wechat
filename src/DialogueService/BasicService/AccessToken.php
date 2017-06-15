<?php
namespace pcappp\wechat\DialogueService\BasicService;


use dekuan\delib\CLib;
use dekuan\vdata\CConst;
use pcappp\wechat\WeChatConst;

class AccessToken
{
    //get Access Token by Code Url
    const GET_ACCESS_TOKEN_BY_CODE_URL = " https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=%s";

    private $m_sAppId;
    private $m_sAppSecret;
    private $m_sGrantType;

    /**
     * AccessToken constructor.
     *
     * @param $sAppId
     * @param $sAppSecret
     * @param string $sGrantType
     */
    public function __construct( $sAppId, $sAppSecret, $sGrantType = "authorization_code" )
    {
        $this->m_sAppId     =   $sAppId;
        $this->m_sAppSecret =   $sAppSecret;
        $this->m_sGrantType =   $sGrantType;
    }


    /**
     * get access token by code
     *
     * @param string $sCode
     * @param array $arrTokenDate
     * @param string $sDesc
     * @return int
     */
    public function GetAccessToken( $sCode = '', & $arrTokenDate = [], & $sDesc = 'unknown error' )
    {
        $nErrCode = CConst::ERROR_UNKNOWN;

        if( CLib::IsExistingString( $sCode )  )
        {
           $sAccessTokenUrl = sprintf( self::GET_ACCESS_TOKEN_BY_CODE_URL, $this->m_sAppId, $this->m_sAppSecret, $sCode, $this->m_sGrantType );

            $arrTokenData = (array)json_decode(file_get_contents($sAccessTokenUrl), TRUE);

            if( CLib::IsExistingString(   CLib::GetVal( $arrTokenData, 'access_token',false,'') ) && CLib::IsExistingString(   CLib::GetVal( $arrTokenData, 'openid',false,'') ) )
            {
                $nErrCode = CConst::ERROR_SUCCESS;
                $sDesc = "get access token success";
            }
            else
            {
                $nErrCode = WeChatConst::GET_ACCESS_TOKEN_FAIL_ERROR;
                $sDesc  =  "get access token fail";
            }
        }
        else
        {
            $nErrCode = WeChatConst::GET_ACCESS_TOKEN_PARAM_ERROR;
            $sDesc  = "get access token param [code] is not string ";
        }

        return $nErrCode;
    }



    //获取
    //刷新
    //验证


    /**
     * 刷新 access_token
     */
    const REFRESH = 'https://api.weixin.qq.com/sns/oauth2/refresh_token';
    /**
     * 检测 access_token 是否有效
     */
    const IS_VALID = 'https://api.weixin.qq.com/sns/auth';
    /**
     * 网页授权获取用户信息
     */
    const USERINFO = 'https://api.weixin.qq.com/sns/userinfo';
    /**
     * 用户 access_token 和公众号是一一对应的
     */
    protected $appid;
    /**
     * 构造方法
     */

    /**
     * 公众号 appid
     */
    public function getAppid()
    {
        return $this->appid;
    }
    /**
     * 获取用户信息
     */
    public function getUser($lang = 'zh_CN')
    {
        if( !$this->isValid() ) {
            $this->refresh();
        }
        $query = array(
            'access_token'  => $this['access_token'],
            'openid'        => $this['openid'],
            'lang'          => $lang
        );
        $response = Http::request('GET', static::USERINFO)
            ->withQuery($query)
            ->send();
        if( $response['errcode'] != 0 ) {
            throw new \Exception($response['errmsg'], $response['errcode']);
        }
        return $response;
    }
    /**
     * 刷新用户 access_token
     */
    public function refresh()
    {
        $query = array(
            'appid'         => $this->appid,
            'grant_type'    => 'refresh_token',
            'refresh_token' => $this['refresh_token']
        );
        $response = Http::request('GET', static::REFRESH)
            ->withQuery($query)
            ->send();
        if( $response['errcode'] != 0 ) {
            throw new \Exception($response['errmsg'], $response['errcode']);
        }
        // update new access_token from ArrayCollection
        parent::__construct($response->toArray());
        return $this;
    }
    /**
     * 检测用户 access_token 是否有效
     */
    public function isValid()
    {
        $query = array(
            'access_token'  => $this['access_token'],
            'openid'        => $this['openid']
        );
        $response = Http::request('GET', static::IS_VALID)
            ->withQuery($query)
            ->send();
        return ($response['errmsg'] === 'ok');
    }
}