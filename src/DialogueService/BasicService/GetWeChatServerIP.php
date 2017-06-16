<?php
namespace zkxtriumph\wechat\DialogueService\BasicService;

use dekuan\delib\CLib;
use dekuan\vdata\CConst;
use zkxtriumph\wechat\WeChatConst;

class GetWeChatServerIP
{
    //获取微信服务器ip地址
    const GET_WE_CHAT_SERVER_IP_URL = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=%s";

    public function __construct()
    {

    }

    public function GetWeChatServerIp( $sAccessToken, & $arrRet = [], $sDesc = '' )
    {
        $nErrCode = CConst::ERROR_UNKNOWN;

        if( CLib::IsExistingString( $sAccessToken ) )
        {
            $sUrl = sprintf( self::GET_WE_CHAT_SERVER_IP_URL, $sAccessToken);
            $arrRet = (array)json_decode(file_get_contents($sUrl), TRUE);

            if( CLib::IsArrayWithKeys( $arrRet ) && CLib::IsArrayWithKeys( $arrRet,["ip_list"] ))
            {
                $nErrCode = CConst::ERROR_SUCCESS;
                $sDesc = "get wechat server ip success ";
            }
            else
            {
                $nErrCode = WeChatConst::GET_WE_CHAT_SERVER_IP_FAIL_ERROR;
                $sDesc  =  "get wechat server ip fail ";
            }
        }
        else
        {
            $nErrCode = WeChatConst::GET_WE_CHAT_SERVER_IP_PARAM_ERROR;
            $sDesc = "get wechat server ip param [ access_token ] error";
        }

        return $nErrCode;
    }
}