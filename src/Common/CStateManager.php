<?php

namespace ioext\wechat\Common;

class CStateManager
{
    const SESSION_SIGN  = "ioext_oauth_state";

    private  $m_bIsSessionStarted = false;

    private  $m_sNameSpace;

    /**
     * CStateManager constructor.
     * @param $sNameSpace
     */
    public function __construct( $sNameSpace = self::SESSION_SIGN )
    {
        $this->m_sNameSpace = $sNameSpace;
    }

    /**
     * 设置state存入session
     *
     * @param $sState
     */
    public function SetState( $sState )
    {
        if( ! $this->m_bIsSessionStarted )
        {
            $this->StartSession();
        }

        $_SESSION[$this->m_sNameSpace] = (string) $sState;

    }

    /**
     * 获取state
     *
     * @param $sState
     * @return null|string
     */
    public function GetState()
    {
        if( ! $this->m_bIsSessionStarted )
        {
            $this->StartSession();
        }

        return $this->HasState() ? ( string ) $_SESSION[$this->m_sNameSpace] : null;
    }

    /**
     * 检测是否有state
     *
     * @return bool
     */
    public function HasState()
    {
        if (!$this->m_bIsSessionStarted)
        {
            $this->StartSession();
        }

        return isset($_SESSION[$this->m_sNameSpace]);
    }

    /**
     * remove state
     */
    public function RemoveState()
    {
        if ( ! $this->m_bIsSessionStarted )
        {
            $this->StartSession();
        }

        if ( $this->HasState() )
        {
            unset($_SESSION[$this->m_sNameSpace]);
        }
    }

    /**
     * 验证state
     *
     * @param $sState
     * @return bool
     */
    public function IsValidState( $sState )
    {
        $bRet = false;

        if ( $this->HasState() )
        {
            if( $sState === $this->GetState() )
            {
                $bRet =
            }
            $bRet = $sState === $this->GetState();
        }

        return $bRet;
    }

    /**
     * open session
     */
    public function StartSession()
    {
        if( PHP_SESSION_NONE == session_start() )
        {
            session_start();
        }

        $this->m_bIsSessionStarted = true;
    }
}