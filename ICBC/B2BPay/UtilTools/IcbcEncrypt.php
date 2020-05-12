<?php

namespace ICBC\B2BPay\UtilTools;

use \ICBC\B2BPay\Config\Config;
use \ICBC\B2BPay\UtilTools\AES;

class IcbcEncrypt
{
	private $config;
	private $aes;
	
	function __construct(Config $config, AES $aes)
	{
		$this->config = $config;
		$this->aes = $aes;
	}
	
	public function encryptContent($content, $encryptType, $encryptKey, $charset)
	{
		if ($this->config::ENCRYPT_TYPE_AES == $encryptType)
		{
			return $this->aes->AesEncrypt($content, base64_decode($encryptKey));
		}
		else
		{
			throw new Exception("Only support AES encrypt!");
		}
	}

	public function decryptContent($encryptedContent, $encryptType, $encryptKey, $charset)
	{
		if ($this->config::ENCRYPT_TYPE_AES == $encryptType)
		{
			return $this->aes->AesDecrypt($encryptedContent, base64_decode($encryptKey));
		}
		else
		{
			throw new Exception("Only support AES decrypt!");
		}
	}
}

?>