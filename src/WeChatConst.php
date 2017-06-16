<?php
namespace zkxtriumph\wechat;

use dekuan\vdata\CConst;

class WeChatConst
{
    //class GetAccessToken
    const GET_ACCESS_TOKEN_FAIL_ERROR               =   CConst::ERROR_USER_START + 1; //获取access_token失败

    const GET_ACCESS_TOKEN_BY_CODE_PARAM_ERROR      =   CConst::ERROR_USER_START + 3; //通过code获取access_token参数错误
    const GET_ACCESS_TOKEN_BY_CODE_FAIL_ERROR       =   CConst::ERROR_USER_START + 5; //通过code获取access_token失败


    const TEST_ACCESS_TOKEN_IS_VALID_PARAM_ERROR    =   CConst::ERROR_USER_START + 7; //验证access_token是否有效参数错误
    const TEST_ACCESS_TOKEN_IS_VALID_FAIL_ERROR     =   CConst::ERROR_USER_START + 9; //验证access_token是否有效失败

    const REFRESH_ACCESS_TOKEN_PARAM_ERROR          =   CConst::ERROR_USER_START + 11; //刷新access_token参数错误
    const REFRESH_ACCESS_TOKEN_FAIL_ERROR           =   CConst::ERROR_USER_START + 13; //刷新access_token失败

    //class TemplateMessage
    const SET_INDUSTRY_PARAM_ERROR                  =   CConst::ERROR_USER_START + 13; //设置所属行业参数错误
    const SET_INDUSTRY_FAIL_ERROR                   =   CConst::ERROR_USER_START + 13; //设置所属行业失败

    const GET_INDUSTRY_INFO_PARAM_ERROR             =   CConst::ERROR_USER_START + 13; //获取设置所属行业的参数错误
    const GET_INDUSTRY_INFO_FAIL_ERROR              =   CConst::ERROR_USER_START + 13; //获取设置所属行业失败

    const GET_TEMPLATE_ID_PARAM_ERROR               =   CConst::ERROR_USER_START + 13; //获取模板ID的参数错误
    const GET_TEMPLATE_ID_FAIL_ERROR                =   CConst::ERROR_USER_START + 13; //获取模板ID失败

    const GET_TEMPLATE_LIST_PARAM_ERROR             =   CConst::ERROR_USER_START + 13; //获取模板列表参数错误
    const GET_TEMPLATE_LIST_FAIL_ERROR              =   CConst::ERROR_USER_START + 13; //获取模板列表失败

    const DEL_TEMPLATE_PARAM_ERROR                  =   CConst::ERROR_USER_START + 13; //删除模板参数错误
    const DEL_TEMPLATE_FAIL_ERROR                   =   CConst::ERROR_USER_START + 13; //删除模板失败

    const SEND_TEMPLATE_MESSAGE_PARAM_ERROR         =   CConst::ERROR_USER_START + 13; //发送模板消息参数错误
    const SEND_TEMPLATE_MESSAGE_FAIL_ERROR          =   CConst::ERROR_USER_START + 13; //发送模板消息失败

    //class GetWeChatServerIP
    const GET_WE_CHAT_SERVER_IP_FAIL_ERROR          =   CConst::ERROR_USER_START + 13; //获取微信服务器IP参数错误
    const GET_WE_CHAT_SERVER_IP_PARAM_ERROR         =   CConst::ERROR_USER_START + 13; //获取微信服务器IP失败



}