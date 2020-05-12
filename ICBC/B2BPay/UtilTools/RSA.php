<?php

namespace ICBC\B2BPay\UtilTools;

use \ICBC\B2BPay\Contracts\AuthInterface;
use \ICBC\B2BPay\Config\Config;

class RSA implements AuthInterface
{
	private $config;
	
	function __construct(Config $config)
	{
		$this->config = $config;
	}
	
	public function sign($content, $privateKey, $algorithm)
	{
		if($this->config::SIGN_SHA1RSA_ALGORITHMS == $algorithm)
		{
			openssl_sign($content,$signature,"-----BEGIN PRIVATE KEY-----\n".$privateKey."\n-----END PRIVATE KEY-----", OPENSSL_ALGO_SHA1);
		}
		else if ($this->config::SIGN_SHA256RSA_ALGORITHMS == $algorithm)
		{
			openssl_sign($content,$signature,"-----BEGIN PRIVATE KEY-----\n".$privateKey."\n-----END PRIVATE KEY-----", OPENSSL_ALGO_SHA256);
		}
		else
		{
			throw new Exception("Only support OPENSSL_ALGO_SHA1 or OPENSSL_ALGO_SHA256 algorithm signature!");
		}
		return base64_encode($signature);
	}

	public static function verify($content, $signature, $publicKey, $algorithm)
	{
		if($this->config::SIGN_SHA1RSA_ALGORITHMS == $algorithm)
		{
			return openssl_verify($content,base64_decode($signature),"-----BEGIN PUBLIC KEY-----\n".$publicKey."\n-----END PUBLIC KEY-----", OPENSSL_ALGO_SHA1);
		}
		else if ($this->config::SIGN_SHA256RSA_ALGORITHMS == $algorithm)
		{
			return openssl_verify($content,base64_decode($signature),"-----BEGIN PUBLIC KEY-----\n".$publicKey."\n-----END PUBLIC KEY-----", OPENSSL_ALGO_SHA256);
		}
		else
		{
			throw new Exception("Only support OPENSSL_ALGO_SHA1 or OPENSSL_ALGO_SHA256 algorithm signature verify!");
		}
	}
}

?>