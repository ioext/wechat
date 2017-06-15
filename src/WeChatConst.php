<?php
namespace zkxtriumph\wechat;

use dekuan\vdata\CConst;

class WeChatConst
{
    //access token
    const GET_ACCESS_TOKEN_FAIL_ERROR                 =   CConst::ERROR_USER_START + 1; //获取access_token失败

    const GET_ACCESS_TOKEN_BY_CODE_PARAM_ERROR        =   CConst::ERROR_USER_START + 3; //通过code获取access_token参数错误
    const GET_ACCESS_TOKEN_BY_CODE_FAIL_ERROR         =   CConst::ERROR_USER_START + 5; //通过code获取access_token失败


    const TEST_ACCESS_TOKEN_IS_VALID_PARAM_ERROR      =   CConst::ERROR_USER_START + 7; //验证access_token是否有效参数错误
    const TEST_ACCESS_TOKEN_IS_VALID_FAIL_ERROR       =   CConst::ERROR_USER_START + 9; //验证access_token是否有效失败

    const REFRESH_ACCESS_TOKEN_PARAM_ERROR            =   CConst::ERROR_USER_START + 11; //刷新access_token参数错误
    const REFRESH_ACCESS_TOKEN_FAIL_ERROR             =   CConst::ERROR_USER_START + 13; //刷新access_token失败


}