<?php
namespace zkxtriumph\wechat\WebService\WebAccount;

//网页授权获取用户基本信息
use dekuan\delib\CLib;
use dekuan\vdata\CConst;
use zkxtriumph\wechat\WeChatConst;

class WebAuthorizeAccessToUserInfo
{
    public function __construct(){}

    public function GetState( $sString, & $sRet = '', & $sDesc = '' )
    {
        $nErrCode = CConst::ERROR_UNKNOWN;

        if( CLib::IsExistingString( $sString ) )
        {
            $sRet = md5( "" );
        }
        else
        {
            $nErrCode = WeChatConst::GET_STATES_PARAM_ERROR;
            $sDesc = "get states param [ string ] error";
        }

        return $nErrCode;
    }
}