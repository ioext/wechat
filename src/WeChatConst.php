<?php
namespace wechat;

use dekuan\vdata\CConst;

class WeChatConst
{
    //access token
    const GET_ACCESS_TOKEN_PARAM_ERROR    =   CConst::ERROR_USER_START + 1;
    const GET_ACCESS_TOKEN_FAIL_ERROR    =   CConst::ERROR_USER_START + 1;
    const TEST_ACCESS_TOKEN_IS_VALID_PARAM_ERROR    =   CConst::ERROR_USER_START + 1;
    const TEST_ACCESS_TOKEN_IS_VALID_ERROR    =   CConst::ERROR_USER_START + 1;
    const REFRESH_ACCESS_TOKEN_PARAM_ERROR    =   CConst::ERROR_USER_START + 1;
    const REFRESH_ACCESS_TOKEN_ERROR    =   CConst::ERROR_USER_START + 1;
}