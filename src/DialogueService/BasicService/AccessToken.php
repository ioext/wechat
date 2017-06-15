<?php
namespace zkxtriumph\wechat\DialogueService\BasicService;

use dekuan\delib\CLib;
use dekuan\vdata\CConst;
use zkxtriumph\wechat\WeChatConst;

class AccessToken
{
    //get Access Token api Url
    const GET_ACCESS_TOKEN_URL = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s";

    //get Access Token by Code Url
    const GET_ACCESS_TOKEN_BY_CODE_URL = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code";

    //test Access Token is valid Url
    const TEST_ACCESS_TOKEN_IS_VALID_URL = "https://api.weixin.qq.com/sns/auth?access_token=%s&openid=%s";

    //refresh Access Token Url
    const REFRESH_ACCESS_TOKEN_URL = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=%s&grant_type=refresh_token&refresh_token=%s";

    private $m_sAppId;
    private $m_sAppSecret;

    /**
     * AccessToken constructor.
     *
     * @param $sAppId
     * @param $sAppSecret
     * @param string $sGrantType
     */
    public function __construct( $sAppId, $sAppSecret )
    {
        $this->m_sAppId     =   $sAppId;
        $this->m_sAppSecret =   $sAppSecret;
    }

    /**
     * get access token
     *
     * If you want to see official return information, see parameters $arrAccessTokenData
     *
     * @param array $arrAccessTokenRet[ only return access_token ]
     * @param string $sDesc
     * @return int
     */
    public function GetAccessToken(& $arrAccessTokenRet = [], & $sDesc = 'unknown error')
    {
        $nErrCode = CConst::ERROR_UNKNOWN;

        $sAccessTokenUrl = sprintf( self::GET_ACCESS_TOKEN_BY_CODE_URL, $this->m_sAppId, $this->m_sAppSecret);
        $arrAccessTokenRet = (array)json_decode(file_get_contents($sAccessTokenUrl), TRUE);

        if( CLib::IsArrayWithKeys( $arrAccessTokenRet )
            && CLib::IsExistingString( CLib::GetVal( $arrAccessTokenRet, 'access_token',false,'') )
        )
        {
            $nErrCode = CConst::ERROR_SUCCESS;
            $sDesc = "get access token success ";
        }
        else
        {
            $nErrCode = WeChatConst::GET_ACCESS_TOKEN_FAIL_ERROR;
            $sDesc  =  "get access token fail ";
        }

        return $nErrCode;
    }

    /**
     * get access token by code
     *
     * If you want to see official return information, see parameters $arrAccessTokenRet
     *
     * @param string $sCode
     * @param array $arrAccessTokenRet[ return access_token、refresh_token、openid ]
     * @param string $sDesc
     * @return int
     */
    public function GetAccessTokenByCode( $sCode = '', & $arrAccessTokenRet = [], & $sDesc = 'unknown error' )
    {
        $nErrCode = CConst::ERROR_UNKNOWN;

        if( CLib::IsExistingString( $sCode )  )
        {
           $sAccessTokenUrl = sprintf( self::GET_ACCESS_TOKEN_BY_CODE_URL, $this->m_sAppId, $this->m_sAppSecret, $sCode);

            $arrAccessTokenRet = (array)json_decode(file_get_contents($sAccessTokenUrl), TRUE);

            if( CLib::IsArrayWithKeys( $arrAccessTokenRet )
                && CLib::IsExistingString(   CLib::GetVal( $arrAccessTokenRet, 'access_token',false,'') )
                && CLib::IsExistingString(   CLib::GetVal( $arrAccessTokenRet, 'openid',false,'') )
            )
            {
                $nErrCode = CConst::ERROR_SUCCESS;
                $sDesc = "get access token by code success";
            }
            else
            {
                $nErrCode = WeChatConst::GET_ACCESS_TOKEN_BY_CODE_FAIL_ERROR;
                $sDesc  =  "get access token by code fail";
            }
        }
        else
        {
            $nErrCode = WeChatConst::GET_ACCESS_TOKEN_BY_CODE_PARAM_ERROR;
            $sDesc  = "get access token by code param [code] is not string ";
        }

        return $nErrCode;
    }

    /**
     * test access token is valid
     *
     * If you want to see official return information, see parameters $arrAccessTokenRet
     *
     * @param $sAccessToken
     * @param $sOpenId
     * @param array $arrAccessTokenRet
     * @param string $sDesc
     * @return int
     */
    public function TestAccessTokenIsValid( $sAccessToken, $sOpenId, & $arrAccessTokenRet = [], & $sDesc = "unknown error" )
    {
        $nErrCode = CConst::ERROR_UNKNOWN;

        if( CLib::IsExistingString( $sAccessToken ) || CLib::IsExistingString( $sOpenId ) )
        {
            $sIsValidAccessTokenUrl = sprintf( self::TEST_ACCESS_TOKEN_IS_VALID_URL, $sAccessToken, $sOpenId );
            $arrAccessTokenRet = (array)json_decode(file_get_contents($sIsValidAccessTokenUrl), TRUE);

            if( CLib::IsArrayWithKeys( $arrAccessTokenRet )
                && CLib::IsExistingString( CLib::GetVal( $arrAccessTokenRet, 'errmsg', false, '' ) )
                && CLib::IsExistingString( CLib::GetVal( $arrAccessTokenRet, 'errcode', false, '' ) )
                && CLib::GetVal( $arrAccessTokenRet, 'errmsg', false, '' ) === "ok"
                && CLib::GetVal( $arrAccessTokenRet, 'errcode', false, '' ) == 0
            )
            {
                $nErrCode = CConst::ERROR_SUCCESS;
                $sDesc    = "the access token is valid";
            }
            else
            {
                $nErrCode = WeChatConst::TEST_ACCESS_TOKEN_IS_VALID_FAIL_ERROR;
                $sDesc = "the access token is not valid";
            }
        }
        else
        {
            $nErrCode = WeChatConst::TEST_ACCESS_TOKEN_IS_VALID_PARAM_ERROR;
            $sDesc = "TestAccessTokenIsValid param [access_token,openid] is not string";
        }

        return $nErrCode;
    }

    /**
     * refresh access token
     *
     * If you want to see official return information, see parameters $arrAccessTokenRet
     *
     * @param $sAccessToken
     * @param array $arrAccessTokenRet
     * @param string $sDesc
     * @return int
     */
    public function RefreshAccessToken( $sAccessToken, & $arrAccessTokenRet = [], & $sDesc = "unknown error" )
    {
        $nErrCode = CConst::ERROR_UNKNOWN;

       if( CLib::IsExistingString( $sAccessToken ) )
       {
           $sRefreshTokenUrl =  sprintf( self::REFRESH_ACCESS_TOKEN_URL, $this->m_sAppId, $sAccessToken );
           $arrAccessTokenRet = (array)json_decode(file_get_contents($sRefreshTokenUrl), TRUE);

           if( CLib::IsArrayWithKeys( $arrAccessTokenRet )
               && CLib::IsExistingString( CLib::GetVal( $arrAccessTokenRet, 'access_token',false,'') )
               && CLib::IsExistingString( CLib::GetVal( $arrAccessTokenRet, 'openid',false,'') )
           )
           {
               $nErrCode = CConst::ERROR_SUCCESS;
               $sDesc = "refresh access token success";
           }
           else
           {
               $nErrCode = WeChatConst::REFRESH_ACCESS_TOKEN_FAIL_ERROR;
               $sDesc = "refresh access token error";
           }
       }
       else
       {
            $nErrCode = WeChatConst::REFRESH_ACCESS_TOKEN_PARAM_ERROR;
            $sDesc = "refresh access token param [ access_token ] error";
       }
       return $nErrCode;
    }

}