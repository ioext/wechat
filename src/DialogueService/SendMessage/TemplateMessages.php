<?php
namespace zkxtriumph\wechat\DialogueService\BasicService;

use dekuan\delib\CLib;
use dekuan\vdata\CConst;
use dekuan\vdata\CRequest;
use zkxtriumph\wechat\WeChatConst;

class TemplateMessage
{
    //超时时间
    const CONST_REQUEST_TIME_OUT = 20;

    //设置所属行业
    const SET_INDUSTRY_URL      = "https://api.weixin.qq.com/cgi-bin/template/api_set_industry";

    //获取设置的行业信息
    const GET_INDUSTRY_INFO_URL = "https://api.weixin.qq.com/cgi-bin/template/get_industry?access_token=%s";

    //获取模板ID
    const GET_TEMPLATE_ID_URL   = "https://api.weixin.qq.com/cgi-bin/template/api_add_template";

    //获取模板列表
    const GET_TEMPLATE_LIST_URL = "https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=%s";

    //删除模板
    const DEL_TEMPLATE_URL      = "https://api.weixin.qq.com/cgi-bin/template/del_private_template";


    public function __construct(){}

    /**
     * 设置所属行业
     *
     * @param $sAccessToken
     * @param int $nPrimaryIndustry
     * @param int $nSecondaryIndustry
     * @param array $arrRet
     * @param string $sDesc
     * @return int
     */
    public function SetIndustry( $sAccessToken, $nPrimaryIndustry = 0, $nSecondaryIndustry = 0, & $arrRet = [], & $sDesc = 'unknown error' )
    {
        $nErrCode = CConst::ERROR_UNKNOWN;

        if( CLib::IsExistingString( $sAccessToken ) && is_numeric( $nPrimaryIndustry ) && is_numeric( $nSecondaryIndustry ) )
        {
            $arrParameter 	= [
                'url'		=> self::SET_INDUSTRY_URL,
                'timeout'	=> self::CONST_REQUEST_TIME_OUT,
                'data'		=> [
                    'access_token'	=> $sAccessToken,
                    'industry_id1'	=> $nPrimaryIndustry,
                    'industry_id2'	=> $nSecondaryIndustry,
                ],
            ];

            $nErrCode = CRequest::GetInstance()->Post( $arrParameter, $arrRet );

            if ( CConst::ERROR_SUCCESS == $nErrCode )
            {
                if ( CLib::IsArrayWithKeys( $arrRet ) && CLib::IsArrayWithKeys( $arrRet, [ 'errorid', 'errordesc', 'vdata' ] ) )
                {
                    if ( CConst::ERROR_SUCCESS == $arrRet[ 'errorid' ] )
                    {
                        $nErrCode = CConst::ERROR_SUCCESS;
                        $sDesc = "set industry success ";
                    }
                    else
                    {
                        $nErrCode = WeChatConst::SET_INDUSTRY_FAIL_ERROR;
                        $sDesc = "set industry fail [3] ".$arrRet['errordesc'];
                    }
                }
                else
                {
                    $nErrCode = WeChatConst::SET_INDUSTRY_FAIL_ERROR;
                    $sDesc = "set industry fail [2] ".$arrRet['errordesc'];
                }
            }
            else
            {
                $nErrCode = WeChatConst::SET_INDUSTRY_FAIL_ERROR;
                $sDesc = "set industry fail [1] ".$arrRet['errordesc'];
            }
        }
        else
        {
            $nErrCode = WeChatConst::SET_INDUSTRY_PARAM_ERROR;
            $sDesc = "set industry param [access_token] error";
        }

        return $nErrCode;
    }

    /**
     * 获取设置的行业信息
     *
     * @param $sAccessToken
     * @param array $arrRet
     * @param string $sDesc
     * @return int
     */
    public function GetIndustryInfo( $sAccessToken, & $arrRet = [], & $sDesc = 'unknown error' )
    {
        $nErrCode = CConst::ERROR_UNKNOWN;

        if( CLib::IsExistingString( $sAccessToken ) )
        {
            $sUrl = sprintf( self::GET_INDUSTRY_INFO_URL, $sAccessToken );
            $arrRet = (array)json_decode( file_get_contents($sUrl), TRUE);

            if( CLib::IsArrayWithKeys( $arrRet ) )
            {
                $nErrCode = CConst::ERROR_SUCCESS;
                $sDesc = "get industry info success";
            }
            else
            {
                $nErrCode = WeChatConst::GET_INDUSTRY_INFO_FAIL_ERROR;
                $sDesc = "get industry info fail";
            }
        }
        else
        {
            $nErrCode = WeChatConst::GET_INDUSTRY_INFO_PARAM_ERROR;
            $sDesc = "get industry info param [ access_token ] error";
        }

        return $nErrCode;
    }

    /**
     * 获得模板ID
     *
     * @param $sAccessToken
     * @param $sTemplateIdShort
     * @param array $arrRet
     * @param string $sDesc
     * @return int
     */
    public function GetTemplateId( $sAccessToken, $sTemplateIdShort, & $arrRet = [], & $sDesc = 'unknown error' )
    {
        $nErrCode = CConst::ERROR_UNKNOWN;

        if( CLib::IsExistingString( $sAccessToken ) && CLib::IsExistingString( $sTemplateIdShort ) )
        {
            $arrParameter 	= [
                'url'		=> self::GET_TEMPLATE_ID_URL,
                'timeout'	=> self::CONST_REQUEST_TIME_OUT,
                'data'		=> [
                    'access_token'	    => $sAccessToken,
                    'template_id_short'	=> $sTemplateIdShort,
                ],
            ];

            $nErrCode = CRequest::GetInstance()->Post( $arrParameter, $arrRet );

            if ( CConst::ERROR_SUCCESS == $nErrCode )
            {
                if ( CLib::IsArrayWithKeys( $arrRet ) && CLib::IsArrayWithKeys( $arrRet, [ 'errorid', 'errordesc', 'vdata' ] ) )
                {
                    if ( CConst::ERROR_SUCCESS == $arrRet[ 'errorid' ] )
                    {
                        $nErrCode = CConst::ERROR_SUCCESS;
                        $sDesc = "get template id success ";
                    }
                    else
                    {
                        $nErrCode = WeChatConst::GET_TEMPLATE_ID_FAIL_ERROR;
                        $sDesc = "get template id [3] ".$arrRet['errordesc'];
                    }
                }
                else
                {
                    $nErrCode = WeChatConst::GET_TEMPLATE_ID_FAIL_ERROR;
                    $sDesc = "get template id fail [2] ".$arrRet['errordesc'];
                }
            }
            else
            {
                $nErrCode = WeChatConst::GET_TEMPLATE_ID_FAIL_ERROR;
                $sDesc = "get template id fail [1] ".$arrRet['errordesc'];
            }
        }
        else
        {
            $nErrCode = WeChatConst::GET_TEMPLATE_ID_PARAM_ERROR;
            $sDesc = "get template id param [access_token] error";
        }

        return $nErrCode;
    }

    /**
     * 获取模板列表
     *
     * @param $sAccessToken
     * @param array $arrRet
     * @param string $sDesc
     * @return int
     */
    public function GetTemplateList( $sAccessToken, & $arrRet = [], & $sDesc = 'unknown error' )
    {
        $nErrCode = CConst::ERROR_UNKNOWN;

        if( CLib::IsExistingString( $sAccessToken ) )
        {
            $sUrl = sprintf( self::GET_TEMPLATE_LIST_URL, $sAccessToken );
            $arrRet = (array)json_decode( file_get_contents($sUrl), TRUE);

            if( CLib::IsArrayWithKeys( $arrRet ) )
            {
                $nErrCode = CConst::ERROR_SUCCESS;
                $sDesc = "get template list success";
            }
            else
            {
                $nErrCode = WeChatConst::GET_TEMPLATE_LIST_FAIL_ERROR;
                $sDesc = "get template list fail";
            }
        }
        else
        {
            $nErrCode = WeChatConst::GET_TEMPLATE_LIST_PARAM_ERROR;
            $sDesc = "get template list param [ access_token ] error";
        }

        return $nErrCode;
    }

    /**
     * 删除模板
     *
     * @param $sAccessToken
     * @param $nTemplateId
     * @param array $arrRet
     * @param string $sDesc
     * @return int
     */
    public function DelTemplate( $sAccessToken, $nTemplateId, & $arrRet = [], & $sDesc = 'unknown error' )
    {
        $nErrCode = CConst::ERROR_UNKNOWN;

        if( CLib::IsExistingString( $sAccessToken ) && is_numeric( $nTemplateId ) )
        {
            $arrParameter 	= [
                'url'		=> self::GET_TEMPLATE_ID_URL,
                'timeout'	=> self::CONST_REQUEST_TIME_OUT,
                'data'		=> [
                    'access_token'	=> $sAccessToken,
                    'template_id'	=> $nTemplateId,
                ],
            ];

            $nErrCode = CRequest::GetInstance()->Post( $arrParameter, $arrRet );

            if ( CConst::ERROR_SUCCESS == $nErrCode )
            {
                if ( CLib::IsArrayWithKeys( $arrRet ) && CLib::IsArrayWithKeys( $arrRet, [ 'errorid', 'errordesc', 'vdata' ] ) )
                {
                    if ( CConst::ERROR_SUCCESS == $arrRet[ 'errorid' ] )
                    {
                        $nErrCode = CConst::ERROR_SUCCESS;
                        $sDesc = "del template success ";
                    }
                    else
                    {
                        $nErrCode = WeChatConst::DEL_TEMPLATE_FAIL_ERROR;
                        $sDesc = "del template [3] ".$arrRet['errordesc'];
                    }
                }
                else
                {
                    $nErrCode = WeChatConst::DEL_TEMPLATE_FAIL_ERROR;
                    $sDesc = "del template fail [2] ".$arrRet['errordesc'];
                }
            }
            else
            {
                $nErrCode = WeChatConst::DEL_TEMPLATE_FAIL_ERROR;
                $sDesc = "del template fail [1] ".$arrRet['errordesc'];
            }
        }
        else
        {
            $nErrCode = WeChatConst::DEL_TEMPLATE_PARAM_ERROR;
            $sDesc = "del template param [access_token, template_id] error";
        }

        return $nErrCode;
    }

    /**
     * 发送模板消息
     *
     * @param $arrInputData
     * @param array $arrRet
     * @param string $sDesc
     * @return int
     */
    public function SendTemplateMessage( $arrInputData, & $arrRet = [], & $sDesc = 'unknown error' )
    {
        // touser	    是   接收者openid
        // template_id	是   模板ID
        // url	        否   模板跳转链接
        // miniprogram	否   跳小程序所需数据，不需跳小程序可不用传该数据
        // appid	    是   所需跳转到的小程序appid（该小程序appid必须与发模板消息的公众号是绑定关联关系）
        // pagepath	    是   所需跳转到小程序的具体页面路径，支持带参数,（示例index?foo=bar）
        // data	        是   模板数据
        //  注：url和miniprogram都是非必填字段，若都不传则模板无跳转；若都传，会优先跳转至小程序。开发者可根据实际需要选择其中一种跳转方式即可。当用户的微信客户端版本不支持跳小程序时，将会跳转至url。
        $nErrCode = CConst::ERROR_UNKNOWN;

        if( CLib::IsArrayWithKeys( $arrInputData )
            && CLib::IsArrayWithKeys( $arrInputData, [ 'touser', 'template_id', 'appid', 'pagepath', 'data' ] )
        )
        {
            $arrParameter 	= [
                'url'		=> self::GET_TEMPLATE_ID_URL,
                'timeout'	=> self::CONST_REQUEST_TIME_OUT,
                'data'		=> [
                    'touser'	    => $arrInputData['touser'],
                    'template_id'	=> $arrInputData['template_id'],
                    'url'	        => isset( $arrInputData['url'] ) ? $arrInputData['template_id'] : '',
                    'miniprogram'	=> isset( $arrInputData['miniprogram'] ) ? $arrInputData['miniprogram'] : '',
                    'appid'	        => $arrInputData['appid'],
                    'pagepath'	    => $arrInputData['pagepath'],
                    'data'	        => $arrInputData['data'],
                ],
            ];

            $nErrCode = CRequest::GetInstance()->Post( $arrParameter, $arrRet );

            if ( CConst::ERROR_SUCCESS == $nErrCode )
            {
                if ( CLib::IsArrayWithKeys( $arrRet ) && CLib::IsArrayWithKeys( $arrRet, [ 'errorid', 'errordesc', 'vdata' ] ) )
                {
                    if ( CConst::ERROR_SUCCESS == $arrRet[ 'errorid' ] )
                    {
                        $nErrCode = CConst::ERROR_SUCCESS;
                        $sDesc = "del template success ";
                    }
                    else
                    {
                        $nErrCode = WeChatConst::SEND_TEMPLATE_MESSAGE_FAIL_ERROR;
                        $sDesc = "send template message [3] ".$arrRet['errordesc'];
                    }
                }
                else
                {
                    $nErrCode = WeChatConst::SEND_TEMPLATE_MESSAGE_FAIL_ERROR;
                    $sDesc = "send template message fail [2] ".$arrRet['errordesc'];
                }
            }
            else
            {
                $nErrCode = WeChatConst::SEND_TEMPLATE_MESSAGE_FAIL_ERROR;
                $sDesc = "send template message fail [1] ".$arrRet['errordesc'];
            }
        }
        else
        {
            $nErrCode = WeChatConst::SEND_TEMPLATE_MESSAGE_PARAM_ERROR;
            $sDesc = "send template message param [touser、template_id、appid、pagepath、data] error";
        }

        return $nErrCode;
    }
}