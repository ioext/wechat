<?php

namespace ioext\wechat\Common;

class CStateManager
{
    const SESSION_SIGN  = "IoextOauthState";

    private  $m_sSessionSign;

    public function __construct( $sSessionSign = self::SESSION_SIGN )
    {
        $this->m_sSessionSign = $sSessionSign;
    }

    public function SetState()
    {
        if( true )
        {

        }
    }
}