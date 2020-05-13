<?php

namespace ICBC\B2BPay\UtilTools;

use \ICBC\B2BPay\Contracts\AuthInterface;

class IcbcCa implements AuthInterface
{
	public function sign($content, $privatekey, $password)
	{
		if (!extension_loaded('infosec'))
		{
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
	  		{
				dl('php_infosec.dll');
			}
	   		else
			{
				dl('infosec.so');
			}
	 	}
		else
		{
			throw new \Exception("loaded infosec module failed");
		}
		
		$plaint = $content;
		if(strlen($plaint) <= 0)
		{
			echo "WARNING : no source data input";
			throw new \Exception("no source data input");
		}
		
		$contents = base64_decode($privatekey);
		$key = substr($contents,2);
		
		$pass = $password;
		if(strlen($pass) <= 0)
		{
			echo "WARNING : no key password input";
			throw new \Exception("no key password input");
		}
		else
		{
			$signature = sign($plaint, $key, $pass);
			$code = current($signature);
			$len = next($signature);
			$signcode = base64_encode($code);
			return current($signcode);
		}
	}

	public function verify($content, $publicKey, $password)
	{
	}
}

?>