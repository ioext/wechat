<?php

namespace dekuan\delib;


class CEnv
{
	const ENV_UNKNOWN		= -1;	//	unknown
	const ENV_PRODUCTION		= 1;	//	production
	const ENV_PRE_PRODUCTION	= 2;	//	pre-production
	const ENV_DEVELOPMENT		= 3;	//	development
	const ENV_LOCAL			= 4;	//	local
	const ENV_TEST			= 5;	//	test

	const ROOT_DOMAIN		= 'dekuan.org';


	//
	//	get environment type
	//
	static function GetEnvType()
	{
		//
		//	RETURN	- environment type
		//		0	- production
		//		1	- pre-production
		//		2	- development
		//		3	- local
		//		4	- test
		//
		if ( ! is_array( $_SERVER ) ||
			! array_key_exists( 'SERVER_NAME', $_SERVER ) ||
			! is_string( $_SERVER[ 'SERVER_NAME' ] ) ||
			empty( $_SERVER[ 'SERVER_NAME' ] ) )
		{
			return self::ENV_UNKNOWN;
		}

		//	...
		$nRet = self::ENV_PRODUCTION;

		//	...
		$sServerName	= strtolower( trim( $_SERVER[ 'SERVER_NAME' ] ) );
		$sRootDomain	= strtolower( trim( self::ROOT_DOMAIN ) );

		if ( 0 == strcasecmp( $sServerName, $sRootDomain ) )
		{
			//	production
			$nRet	= self::ENV_PRODUCTION;
		}
		else if ( strstr( $sServerName, sprintf( "-pre.%s", $sRootDomain ) ) )
		{
			//	pre-production
			$nRet	= self::ENV_PRE_PRODUCTION;
		}
		else if ( strstr( $sServerName, sprintf( "-dev.%s", $sRootDomain ) ) )
		{
			//	development
			$nRet	= self::ENV_DEVELOPMENT;
		}
		else if ( strstr( $sServerName, sprintf( "-loc.%s", $sRootDomain ) ) )
		{
			//	local
			$nRet	= self::ENV_LOCAL;
		}
		else if ( strstr( $sServerName, sprintf( "-test.%s", $sRootDomain ) ) )
		{
			//	test
			$nRet	= self::ENV_TEST;
		}
		else
		{
			//	test for production
			$sNeedle	= sprintf( ".%s", $sRootDomain );
			$nRightPos	= strlen( $sServerName ) - strlen( $sNeedle ) - 1;
			$nSearchPos	= strrpos( $sServerName, $sNeedle );

			if ( $nRightPos > 0 && $nRightPos === $nSearchPos )
			{
				//	production
				$nRet = self::ENV_PRODUCTION;
			}
		}

		return $nRet;
	}

	static function IsSecureHttp()
	{
		return ( CLib::IsArrayWithKeys( $_SERVER, 'HTTPS' ) &&
			CLib::IsExistingString( $_SERVER[ 'HTTPS' ] ) &&
			(
				0 == strcasecmp( 'ON', $_SERVER[ 'HTTPS' ] ) ||
				0 == strcasecmp( '1', $_SERVER[ 'HTTPS' ] )
			) );
	}
}