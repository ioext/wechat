<?php
namespace pcappp\wechat\DialogueService\BasicService;

use dekuan\delib\CLib;
use dekuan\vdata\CConst;
use wechat\WeChatConst;

class AccessToken
{
    //get Access Token by Code url
    const GET_ACCESS_TOKEN_BY_CODE_URL = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code";

    //test Access Token is valid Url
    const TEST_ACCESS_TOKEN_IS_VALID_URL = "https://api.weixin.qq.com/sns/auth?access_token=%s&openid=%s";

    //refresh access token url
    const REFRESH_ACCESS_TOKEN_URL = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=%s&grant_type=refresh_token&refresh_token=%s";

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
    public function __construct( $sAppId, $sAppSecret )
    {
        $this->m_sAppId     =   $sAppId;
        $this->m_sAppSecret =   $sAppSecret;
    }

    /**
     * get access token by code
     *
     * @param string $sCode
     * @param array $arrTokenData
     * @param string $sDesc
     * @return int
     */
    public function GetAccessToken( $sCode = '', & $arrAccessTokenData = [], & $sDesc = 'unknown error' )
    {
        $nErrCode = CConst::ERROR_UNKNOWN;

        if( CLib::IsExistingString( $sCode )  )
        {
           $sAccessTokenUrl = sprintf( self::GET_ACCESS_TOKEN_BY_CODE_URL, $this->m_sAppId, $this->m_sAppSecret, $sCode, $this->m_sGrantType );

            $arrAccessTokenData = (array)json_decode(file_get_contents($sAccessTokenUrl), TRUE);

            if( CLib::IsArrayWithKeys( $arrAccessTokenData )
                && CLib::IsExistingString(   CLib::GetVal( $arrAccessTokenData, 'access_token',false,'') )
                && CLib::IsExistingString(   CLib::GetVal( $arrAccessTokenData, 'openid',false,'') )
            )
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

    /**
     * test access token is valid
     *
     * @param $sAccessToken
     * @param $sOpenId
     * @param string $sDesc
     * @return int
     */
    public function TestAccessTokenIsValid( $sAccessToken, $sOpenId, & $bIsValid = false, & $sDesc = "unknown error" )
    {
        $nErrCode = CConst::ERROR_UNKNOWN;

        if( CLib::IsExistingString( $sAccessToken ) || CLib::IsExistingString( $sOpenId ) )
        {
            $sIsValidAccessTokenUrl = sprintf( self::TEST_ACCESS_TOKEN_IS_VALID_URL, $sAccessToken, $sOpenId );
            $arrIsValidRet = (array)json_decode(file_get_contents($sIsValidAccessTokenUrl), TRUE);

            if( CLib::IsArrayWithKeys( $arrIsValidRet )
                && CLib::IsExistingString( CLib::GetVal( $arrIsValidRet, 'errmsg', false, '' ) )
                && CLib::IsExistingString( CLib::GetVal( $arrIsValidRet, 'errcode', false, '' ) )
                && CLib::GetVal( $arrIsValidRet, 'errmsg', false, '' ) === "ok"
                && CLib::GetVal( $arrIsValidRet, 'errcode', false, '' ) == 0
            )
            {
                $nErrCode = CConst::ERROR_SUCCESS;
                $bIsValid = true;
                $sDesc    = "the access token is valid";
            }
            else
            {
                $nErrCode = WeChatConst::TEST_ACCESS_TOKEN_IS_VALID_ERROR;
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
     * @param $sAccessToken
     * @param array $arrAccessTokenData
     * @param string $sDesc
     * @return int
     */
    public function RefreshAccessToken( $sAccessToken, & $arrAccessTokenData = [], & $sDesc = "unknown error" )
    {
        $nErrCode = CConst::ERROR_UNKNOWN;

       if( CLib::IsExistingString( $sAccessToken ) )
       {
           $sRefreshTokenUrl =  sprintf( self::REFRESH_ACCESS_TOKEN_URL, $this->m_sAppId, $sAccessToken );
           $arrAccessTokenData = (array)json_decode(file_get_contents($sRefreshTokenUrl), TRUE);

           if( CLib::IsArrayWithKeys( $arrAccessTokenData )
               && CLib::IsExistingString( CLib::GetVal( $arrAccessTokenData, 'access_token',false,'') )
               && CLib::IsExistingString( CLib::GetVal( $arrAccessTokenData, 'openid',false,'') )
           )
           {
               $nErrCode = CConst::ERROR_SUCCESS;
               $sDesc = "refresh access token success";
           }
           else
           {
               $nErrCode = WeChatConst::REFRESH_ACCESS_TOKEN_ERROR;
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