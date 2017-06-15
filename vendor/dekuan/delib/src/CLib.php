<?php

namespace dekuan\delib;


/**
 *     CLib 
 */
class CLib 
{
	const ENCODEOBJECT_TYPE_SERIALIZE	= 1;
	const ENCODEOBJECT_TYPE_JSON		= 2;

	const VARTYPE_NUMERIC			= 1;
	const VARTYPE_STRING			= 2;
	const VARTYPE_ARRAY			= 3;


	static function IsCharacters( $vValue )
	{
		return ( is_string( $vValue ) || is_numeric( $vValue ) );
	}

	static function IsArrayWithKeys( $vData, $vKeys = null )
	{
		//
		//	vData	- array
		//	vKeys	- keys array, like: [ 'key1', 'key2', ... ]
		//		  key string, like: 'key1'
		//	RETURN	- true / false
		//
		if ( ! is_array( $vData ) )
		{
			return false;
		}

		//	...
		$bRet = false;

		if ( is_array( $vKeys ) && count( $vKeys ) > 0 )
		{
			//	vKeys is a list in array
			//	check if vData have the specified keys
			$nKeyCount	= count( $vKeys );
			$nMatchedCount	= 0;
			foreach ( $vKeys as $vKey )
			{
				if ( is_string( $vKey ) || is_numeric( $vKey ) )
				{
					if ( array_key_exists( $vKey, $vData ) )
					{
						$nMatchedCount ++;
					}
				}
			}

			$bRet = ( $nKeyCount == $nMatchedCount );
		}
		else if ( self::IsExistingString( $vKeys ) )
		{
			//	vKeys is a key in string
			$bRet = array_key_exists( $vKeys, $vData );
		}
		else
		{
			//	vKeys is null
			$bRet = ( count( $vData ) > 0 );
		}

		return $bRet;
	}
	static function IsSameString( $sStr1, $sStr2 )
	{
		return ( is_string( $sStr1 ) && is_string( $sStr2 ) && 0 == strcmp( $sStr1, $sStr2 ) );
	}
	static function IsCaseSameString( $sStr1, $sStr2 )
	{
		return ( is_string( $sStr1 ) && is_string( $sStr2 ) && 0 == strcasecmp( $sStr1, $sStr2 ) );
	}
	static function IsExistingString( $sStr, $bTrim = false )
	{
		$bRet	= false;

		if ( is_string( $sStr ) || is_numeric( $sStr ) )
		{
			$sStr	= strval( $sStr );
			$bRet	= ( strlen( $bTrim ? trim( $sStr ) : $sStr ) > 0 );
		}

		return $bRet;
	}

	static function EncodeObject( $ArrObject, $nEncodeType = CLib::ENCODEOBJECT_TYPE_JSON )
	{
		$sRet = '';

		if ( is_array( $ArrObject ) )
		{
			if ( self::ENCODEOBJECT_TYPE_SERIALIZE == $nEncodeType )
			{
				$sRet = @ serialize( $ArrObject );
			}
			else if ( self::ENCODEOBJECT_TYPE_JSON == $nEncodeType )
			{
				$sRet = @ json_encode( $ArrObject );
			}
		}

		return $sRet;
	}

	static function DecodeObject( $sString, $nEncodeType = CLib::ENCODEOBJECT_TYPE_JSON )
	{
		$ArrRet	= Array();

		//	...
		$sString = trim( $sString );
		if ( ! empty( $sString ) )
		{
			if ( self::ENCODEOBJECT_TYPE_SERIALIZE == $nEncodeType )
			{
				$ArrRet = @ unserialize( $sString );
			}
			else if ( self::ENCODEOBJECT_TYPE_JSON == $nEncodeType )
			{
				$ArrRet = @ json_decode( $sString, true );
			}

			if ( ! is_array( $ArrRet ) )
			{
				$ArrRet = Array();
			}
		}

		return $ArrRet;
	}

	static function GetEnvVar( $sKey, $arrEnv = null )
	{
		//
		//	sKey	- [in] string,	the key that we search by in arrEnv
		//	arrEnv	- [in] array,	Specify a environment data array,
		//				default is to $_SERVER if it exists
		//	RETURN	- the value with key in arrEvn, default was set to null
		//
		if ( ! self::IsExistingString( $sKey ) )
		{
			return null;
		}

		//	...
		$vRet	= null;

		//
		//	make arrEvn if not exists
		//
		if ( ! is_array( $arrEnv ) )
		{
			$arrEnv	= is_array( $_SERVER ) ? $_SERVER : null;
		}

		//
		//	try to obtain the value with key in arrEnv
		//
		if ( is_array( $arrEnv ) &&
			array_key_exists( $sKey, $arrEnv ) )
		{
			$vRet = $arrEnv[ $sKey ];
		}
		else
		{
			$vRet = getenv( $sKey );
		}

		return $vRet;
	}

	static function GetClientIP( $bMustBePublic = true, $bPlayWithProxy = true )
	{
		//
		//	bMustBePublic	- true 	/ the ip address must be a valid public address
		//			  false	/ return true if an address is valid in its format.
		//				  return true for all type of internal addresses, e.g.: 127.0.0.1, 192.168.0.1
		//	bPlayWithProxy	- true	/ try to extract address from proxy field of HTTP
		//			  false	/ give up to extract address from proxy field
		//	RETURN		- ip address of client
		//
		//
		//	* History
		//		liu qixing	created		@20160221
		//
		//	* About HTTP_X_FORWARDED_FOR
		//
		//		The X-Forwarded-For (XFF) HTTP header field was a common method for identifying
		//		the originating IP address of a client connecting to a web server through an HTTP proxy
		//		or load balancer.
		//
		//		The general format of the field is:
		//		X-Forwarded-For: client, proxy1, proxy2
		//
		//		Where the value is a comma+space separated list of IP addresses,
		// 		the left-most being the original client, and each successive proxy that passed the request
		// 		adding the IP address where it received the request from.
		// 		In this example, the request passed through proxy1, proxy2, and then proxy3 ( not shown in the header ).
		// 		proxy3 appears as remote address of the request.
		//
		//		Since it is easy to forge an X-Forwarded-For field the given information should be used with care.
		// 		The last IP address is always the IP address that connects to the last proxy,
		// 		which means it is the most reliable source of information.
		// 		X-Forwarded-For data can be used in a forward or reverse proxy scenario.
		//
		//		Just logging the X-Forwarded-For field is not always enough as the last proxy IP address in a chain
		// 		is not contained within the X-Forwarded-For field, it is in the actual IP header.
		//		A web server should log BOTH the request's source IP address and
		// 		the X-Forwarded-For field information for completeness.
		//
		if ( ! is_bool( $bMustBePublic ) || ! is_bool( $bPlayWithProxy ) )
		{
			return '';
		}

		//	...
		$sRet		= '';
		$sClientIp	= '';

		//	...
		if ( $bPlayWithProxy )
		{
			//
			//	a new field defined by dekuan.org
			//	almost like HTTP_X_FORWARDED_FOR
			//
			$sClientIp = self::GetEnvVar( 'HTTP_VDATA_FORWARDED_FOR', $_SERVER );
		}

		if ( ! self::IsExistingString( $sClientIp ) )
		{
			//
			//	for acfun.cn only
			//
			$sClientIp = self::GetEnvVar( 'HTTP_X_TRUE_IP', $_SERVER );
		}

		if ( ! self::IsExistingString( $sClientIp ) )
		{
			//
			//	It's real client IP address given by HTTPD
			//
			$sClientIp = self::GetEnvVar( 'REMOTE_ADDR', $_SERVER );
		}

		if ( $bPlayWithProxy )
		{
			if ( ! self::IsExistingString( $sClientIp ) )
			{
				$sClientIp = self::GetEnvVar( 'HTTP_X_FORWARDED_FOR', $_SERVER );
			}
		}

		if ( self::IsExistingString( $sClientIp ) )
		{
			$nPos = strpos( $sClientIp, ',' );
			if ( false !== $nPos )
			{
				//
				//	may be an address from HTTP_X_FORWARDED_FOR
				//
				$sClientIp = trim( substr( $sClientIp, 0, $nPos ) );
			}
			else
			{
				//
				//	may be an address from REMOTE_ADDR
				//
			}

			if ( self::IsValidIp( $sClientIp, $bMustBePublic ) )
			{
				$sRet = $sClientIp;
			}
		}

		return $sRet;
	}

	static function IsValidIP( $sStr, $bMustBePublic = true, $bTrim = false )
	{
		//
		//	sStr		- the ip address / the variable being evaluated
		//	bMustBePublic	- true 	/ the ip address must be a valid public address
		//			  false	/ return true if an address is valid in its format.
		//				  return true for all type of internal addresses, e.g.: 127.0.0.1, 192.168.0.1
		//	RETURN		- ip address or empty if occurred errors
		//
		//
		//	<Documentation>
		//		https://en.wikipedia.org/wiki/X-Forwarded-For
		//		https://en.wikipedia.org/wiki/IPv6
		//		http://php.net/manual/en/function.filter-var.php
		//
		if ( ! self::IsExistingString( $sStr ) )
		{
			return false;
		}
		if ( ! is_bool( $bMustBePublic ) )
		{
			return false;
		}
		if ( false == $bMustBePublic && self::IsSameString( '127.0.0.1', $sStr ) )
		{
			return true;
		}
		if ( ! is_bool( $bTrim ) )
		{
			return false;
		}

		//	...
		$sStr	= ( $bTrim ? trim( $sStr ) : $sStr );

		//
		//	Documentation
		//	http://php.net/manual/en/filter.filters.flags.php
		//
		//	FILTER_FLAG_NO_PRIV_RANGE
		//		Fails validation for the following private IPv4 ranges: 10.0.0.0/8, 172.16.0.0/12 and 192.168.0.0/16.
		//		Fails validation for the IPv6 addresses starting with FD or FC.
		//
		//	FILTER_FLAG_NO_RES_RANGE
		//		Fails validation for the following reserved IPv4 ranges:
		//		0.0.0.0/8, 169.254.0.0/16, 192.0.2.0/24 and 224.0.0.0/4.
		//		This flag does not apply to IPv6 addresses.
		//
		return ( false !== filter_var
			(
				$sStr,
				FILTER_VALIDATE_IP,
				$bMustBePublic ? ( FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) : FILTER_DEFAULT
			) );
	}

	static function IsValidEMail( $sStr, $bCheckDNS = false, $bTrim = false )
	{
		//
		//	sStr		- email address / the variable being evaluated
		//	bCheckDNS	- if we also check the DNS server
		//	bTrim		- if we trim sStr before checking
		//	RETURN		- true / false
		//
		//	<Documentation>
		//
		//	local-part@domain.xx.xx
		//		- local-part	must be no more than 64 characters long
		//		- domain	must be no more than 64 characters long
		//
		//	https://en.wikipedia.org/wiki/Email_address
		//
		//		The format of email addresses is local-part@domain where the local-part may be up to
		// 		64 characters long and the domain name may have a maximum of 255 characters.
		// 		- but the maximum of 256-character length of a forward or reverse path restricts
		// 		  the entire email address to be no more than 254 characters long.
		//		  The formal definitions are in RFC 5322 (sections 3.2.3 and 3.4.1) and RFC 5321
		//		– with a more readable form given in the informational RFC 3696 and the associated errata
		//
		if ( ! self::IsExistingString( $sStr ) )
		{
			return false;
		}
		if ( ! is_bool( $bCheckDNS ) || ! is_bool( $bTrim ) )
		{
			return false;
		}

		//	...
		$bRet	= false;
		$sStr	= ( $bTrim ? trim( $sStr ) : $sStr );

		if ( false !== filter_var( $sStr, FILTER_VALIDATE_EMAIL ) )
		{
			if ( $bCheckDNS )
			{
				//
				//	continue to check DNS
				//
				$arrMailParts = explode( '@', $sStr, 2 );
				if ( self::IsArrayWithKeys( $arrMailParts ) )
				{
					$sDomain = trim( end( $arrMailParts ) );
					if ( self::IsExistingString( $sDomain ) )
					{
						//
						//	Note:
						//	Adding the dot enforces the root.
						//	The dot is sometimes necessary if you are searching for a fully qualified domain
						//	which has the same name as a host on your local domain.
						//	Of course the dot does not alter results that were OK anyway.
						//
						$bRet = checkdnsrr( sprintf( "%s.", $sDomain ), 'MX' );
					}
				}
			}
			else
			{
				$bRet = true;
			}
		}

		return $bRet;
	}

	static function IsValidMobile( $sStr, $bTrim = false )
	{
		//
		//	sStr	- cell phone number / the variable being evaluated
		//	bTrim	- if we trim sStr before checking
		//	RETURN	- true / false
		//
		if ( ! self::IsExistingString( $sStr ) )
		{
			return false;
		}
		if ( ! is_bool( $bTrim ) )
		{
			return false;
		}

		$sReExp	= '/^(?:13|14|15|18|17)[0-9]{9}$/';
		$sStr	= ( $bTrim ? trim( $sStr ) : $sStr );

		return ( 1 == preg_match( $sReExp, $sStr ) );
	}

	static function IsMobileDevice()
	{
		$cMobDet = new CMobileDetector();
		return ( $cMobDet->isMobile() || $cMobDet->isTablet() );
	}

	static function GetVal( $arrObj, $sName, $bIsNumeric = false, $vDefValue = null )
	{
		//
		//	arrObj		- [in] object
		//	sName		- [in] index name
		//	bIsNumeric	- [in] is numeric
		//	vDefValue	- [in] default value
		//
		$vRet = $vDefValue;

		if ( ! self::IsArrayWithKeys( $arrObj ) )
		{
			return $vDefValue;
		}
		if ( ! self::IsExistingString( $sName ) )
		{
			return $vDefValue;
		}

		if ( array_key_exists( $sName, $arrObj ) && isset( $arrObj[ $sName ] ) )
		{
			if ( $bIsNumeric )
			{
				if ( is_numeric( $arrObj[ $sName ] ) )
				{
					$vRet = $arrObj[ $sName ];
				}
			}
			else
			{
				$vRet = $arrObj[ $sName ];
			}
		}

		return $vRet;
	}
	static function GetValEx( $arrObj, $sName, $nVarType = self::VARTYPE_STRING, $vDefValue = null )
	{
		//
		//	arrObj		- [in] object
		//	sName		- [in] index name
		//	nVarType	- [in] type of variable: VARTYPE_NUMERIC, VARTYPE_STRING, VARTYPE_ARRAY
		//	vDefValue	- [in] default value
		//	RETURN		- value in user specified type
		//
		$vRet = $vDefValue;

		if ( ! self::IsArrayWithKeys( $arrObj ) )
		{
			return $vDefValue;
		}
		if ( ! self::IsExistingString( $sName ) )
		{
			return $vDefValue;
		}

		if ( array_key_exists( $sName, $arrObj ) && isset( $arrObj[ $sName ] ) )
		{
			if ( self::VARTYPE_NUMERIC == $nVarType )
			{
				if ( is_numeric( $arrObj[ $sName ] ) )
				{
					$vRet = $arrObj[ $sName ];
				}
			}
			else if ( self::VARTYPE_STRING == $nVarType )
			{
				if ( is_string( $arrObj[ $sName ] ) || is_numeric( $arrObj[ $sName ] ) )
				{
					$vRet = strval( $arrObj[ $sName ] );
				}
			}
			else if ( self::VARTYPE_ARRAY == $nVarType )
			{
				if ( is_array( $arrObj[ $sName ] ) )
				{
					$vRet = $arrObj[ $sName ];
				}
			}
			else
			{
				$vRet = $arrObj[ $sName ];
			}
		}

		return $vRet;
	}

	static function SafeStringVal( $vVal, $sDefaultVal = '' )
	{
		return ( ( is_string( $vVal ) || is_numeric( $vVal ) ) ? strval( $vVal ) : $sDefaultVal );
	}
	static function SafeIntVal( $vVal, $nDefaultVal = 0 )
	{
		return ( ( is_string( $vVal ) || is_numeric( $vVal ) || is_array( $vVal ) ) ? intval( $vVal ) : $nDefaultVal );
	}

	static function GenerateRandomString( $nMaxLength = 10, $bNumeric = false )
	{
		$sRet = '';

		//	...
		$sChars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		if ( $bNumeric )
		{
			$sChars = '0123456789';
		}

		//	...
		$nCharLen = strlen( $sChars );

		for ( $i = 0; $i < $nMaxLength; $i ++ )
		{
			$sRet .= $sChars[ rand( 0, $nCharLen - 1 ) ];
		}

		return $sRet;
	}
}