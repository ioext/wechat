<?php

require_once dirname( __DIR__ ) . '/src/CEnv.php';
require_once dirname( __DIR__ ) . '/src/CLib.php';

use dekuan\delib;


/**
 * Created by PhpStorm.
 * User: xing
 * Date: 17/02/2017
 * Time: 12:49 AM
 */
class test extends PHPUnit_Framework_TestCase
{
	public function testGetClientIP()
	{
		$arrVarList	=
		[
			[ true,	true,	'HTTP_VDATA_FORWARDED_FOR', '106.39.200.1,106.39.200.2', '106.39.200.1' ],
			[ true,	false,	'HTTP_VDATA_FORWARDED_FOR', '106.39.200.1,106.39.200.2', '' ],
			[ false, true,	'HTTP_VDATA_FORWARDED_FOR', '106.39.200.1,106.39.200.2', '106.39.200.1' ],
			[ false, false,	'HTTP_VDATA_FORWARDED_FOR', '106.39.200.1,106.39.200.2', '' ],

			[ true,	true,	'REMOTE_ADDR', '106.39.200.3', '106.39.200.3' ],
			[ true,	false,	'REMOTE_ADDR', '106.39.200.3', '106.39.200.3' ],
			[ false, true,	'REMOTE_ADDR', '106.39.200.3', '106.39.200.3' ],
			[ false, false,	'REMOTE_ADDR', '106.39.200.3', '106.39.200.3' ],

			[ true,	true,	'HTTP_X_TRUE_IP', '45.34.23.101, 115.238.232.96', '45.34.23.101' ],
			[ true,	false,	'HTTP_X_TRUE_IP', '45.34.23.101, 115.238.232.96', '45.34.23.101' ],
			[ false, true,	'HTTP_X_TRUE_IP', '45.34.23.101, 115.238.232.96', '45.34.23.101' ],
			[ false, false,	'HTTP_X_TRUE_IP', '45.34.23.101, 115.238.232.96', '45.34.23.101' ],

			[ true,	true,	'HTTP_X_FORWARDED_FOR', '106.39.200.5,106.39.200.6', '106.39.200.5' ],
			[ true,	false,	'HTTP_X_FORWARDED_FOR', '106.39.200.5,106.39.200.6', '' ],
			[ false, true,	'HTTP_X_FORWARDED_FOR', '106.39.200.5,106.39.200.6', '106.39.200.5' ],
			[ false, false,	'HTTP_X_FORWARDED_FOR', '106.39.200.5,106.39.200.6', '' ],
		];

		foreach ( $arrVarList as $arrData )
		{
			$bMustBePublic	= $arrData[ 0 ];
			$bPlayWithProxy	= $arrData[ 1 ];
			$sKey		= $arrData[ 2 ];
			$sValue		= $arrData[ 3 ];
			$sExpect	= $arrData[ 4 ];

			$_SERVER	=
			[
				$sKey	=> $sValue,
			];

			echo "\r\n";
			echo "+ KEY:    " . $sKey . "\r\n  VALUE:  " . $sValue . "\r\n";

			echo "  PARAM:  MustBePublic=" . ( $bMustBePublic ? "true" : "false" ) . ", ";
			echo "PlayWithProxy=" . ( $bPlayWithProxy ? "true" : "false" ) . "\r\n";
			$sClientIP	= delib\CLib::GetClientIP( $bMustBePublic, $bPlayWithProxy );
			echo "  GOT IP: \"" . $sClientIP . "\"\r\n  EXPECT: \"" . $sExpect . "\"\r\n";

			$this->assertTrue( 0 == strcasecmp( $sExpect, $sClientIP ) );
		}

		//	...
		$_SERVER	= [];

		foreach ( $arrVarList as $arrData )
		{
			$sKey		= $arrData[ 2 ];
			$sValue		= $arrData[ 3 ];

			//	...
			$_SERVER[ $sKey ] = $sValue;
		}
		unset( $_SERVER['HTTP_VDATA_FORWARDED_FOR'] );

		//	...
		$sClientIP	= delib\CLib::GetClientIP( false, true );
		echo "\r\n+ Play With Proxy=true\r\n";
		print_r( $_SERVER );
		echo "\tRESULT:\t" . $sClientIP . "\r\n";
		$this->assertTrue( 0 == strcasecmp( '45.34.23.101', $sClientIP ) );
	}
}
