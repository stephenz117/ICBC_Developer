<?php

namespace ICBC\B2BPay\UtilTools;

use \ICBC\B2BPay\Config\Config;
use IcbcCa;
use RSA;

class IcbcSignature
{
	private $config;
	private $icbcca;
	private $rsa;
	
	function __construct(Config $config, IcbcCa $icbcca, RSA $rsa)
	{
		$this->config = $config;
		$this->icbcca = $icbcca;
		$this->rsa = $rsa;
	}
	
	public function sign($strToSign, $signType, $privateKey, $charset, $password)
	{
		if ($this->config::SIGN_TYPE_CA == $signType)
		{
			return $this->icbcca->sign($strToSign, $privateKey, $password);
		}
		else if ($this->config::SIGN_TYPE_RSA == $signType)
		{
			return $this->rsa->sign($strToSign, $privateKey, $this->config->getConstantSignSha1rsaAlgorithms());
		}
		else if ($this->config::SIGN_TYPE_RSA2 == $signType)
		{
			return $this->rsa->sign($strToSign, $privateKey, $this->config->getConstantSignSha256rsaAlgorithms());
		}
		else
		{
			throw new Exception("Only support CA\RSA signature!");
		}
	}

	public function verify($strToSign, $signType, $publicKey, $charset, $signedStr, $password)
	{
		if ($this->config::SIGN_TYPE_CA == $signType)
		{
			return $this->icbcca->verify($strToSign, $publicKey, $password);
		}
		else if ($this->config::SIGN_TYPE_RSA == $signType)
		{
			return $this->rsa->verify($strToSign, $signedStr, $publicKey, $this->config->getConstantSignSha1rsaAlgorithms());
		}
		else if ($this->config::SIGN_TYPE_RSA2 == $signType)
		{
			return $this->rsa->verify($strToSign, $signedStr, $publicKey, $this->config->getConstantSignSha256rsaAlgorithms());
		}
		else
		{
			throw new Exception("Only support CA or RSA signature verify!");
		}
	}
}

?>