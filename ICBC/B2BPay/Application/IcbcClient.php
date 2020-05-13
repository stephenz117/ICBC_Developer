<?php

namespace ICBC\B2BPay\Application;

use \ICBC\B2BPay\Contracts\HttpAbstract;
use \ICBC\B2BPay\Config\Config;
use \ICBC\B2BPay\Config\App;
use \ICBC\B2BPay\UtilTools\IcbcEncrypt;
use \ICBC\B2BPay\UtilTools\IcbcSignature;

class IcbcClient extends HttpAbstract
{
	private $config;
	private $app;
	private $encrypt;
	private $signature;
	private $params;

	function __construct(Config $config, App $app, IcbcEncrypt $encrypt, IcbcSignature $signature)
	{
		$this->config = $config;
		$this->app = $app;
		$this->encrypt = $encrypt;
		$this->signature = $signature;
	}
	
	public function execute($common, $request, $msgId, $appAuthToken)
	{
		$params = $this->prepareParams($common, $request, $msgId, $appAuthToken);
		
		if (strtoupper($request["method"]) == "GET")
		{
			$respStr = parent::doGet($request["serviceUrl"], $params, $common['charset']);
		}
		else if (strtoupper($request["method"]) == "POST")
		{
			$respStr = parent::doPost($request["serviceUrl"], $params, $common['charset']);
		}
		else
		{
			throw new Exception("Only support GET or POST http method!");
		}
		
		$respBizContentStr = json_encode(json_decode($respStr, true)[$this->config::RESPONSE_BIZ_CONTENT], 320);
		$sign = json_decode($respStr, true)[$this->config::SIGN];
		
		$passed = $this->signature->verify($respBizContentStr, $this->config::SIGN_TYPE_RSA, $common['icbcPulicKey'], $common['charset'], $sign);

		if (!$passed)
		{
			throw new Exception("icbc sign verify not passed!");
		}
		if ($request["isNeedEncrypt"])
		{
			$respBizContentStr = $this->encrypt->decryptContent(substr($respBizContentStr, 1 , strlen($respBizContentStr)-2), $common['encryptType'], $common['encryptKey'], $common['charset']);
		}
		return $respBizContentStr;
	}
	
	function prepareParams($common, $request, $msgId, $appAuthToken)
	{
		$bizContentStr = json_encode($request["biz_content"]);

		$path = parse_url($request["serviceUrl"], PHP_URL_PATH);
		
		$params = array();

		if (!empty($request["extraParams"]))
		{
			$params = array_merge($params, $request["extraParams"]);
		}

		$params[$this->config::APP_ID] = $common['appId'];
		$params[$this->config::SIGN_TYPE] = $common['signType'];
		$params[$this->config::CHARSET] = $common['charset'];
		$params[$this->config::FORMAT] = $common['format'];
		$params[$this->config::CA] = $common['ca'];
		$params[$this->config::APP_AUTH_TOKEN] = $appAuthToken;
		$params[$this->config::MSG_ID] = $msgId;

		date_default_timezone_set($this->config::DATE_TIMEZONE);
		$params[$this->config::TIMESTAMP] = date($this->config::DATE_TIME_FORMAT);

		if ($request["isNeedEncrypt"])
		{
			if ($bizContentStr != null)
			{
				$params[$this->config::ENCRYPT_TYPE] = $common['encryptType'];
				$params[$this->config::BIZ_CONTENT_KEY] = $this->encrypt->encryptContent($bizContentStr, $common['encryptType'], $common['encryptKey'], $common['charset']);
			}
		}
		else
		{
			$params[$this->config::BIZ_CONTENT_KEY] = $bizContentStr;
		}

		$strToSign = parent::buildOrderedSignStr($path, $params);

		$signedStr = $this->signature::sign($strToSign, $common['signType'], $common['privateKey'], $common['charset'], $common['password']);

		$params[$this->config::SIGN] = $signedStr;
		return $params;
	}

	function JSONTRANSLATE($array)
	{
		foreach ($array as $key => $value){
			$array[$key] = urlencode($value);
		}
		return json_encode($array);
	}

	function encodeOperations($array)
	{
		foreach ((array)$array as $key => $value)
		{
			if (is_array($value))
			{
				$this->encodeOperations($array[$key]);
			}
			else
			{
				$array[$key] = urlencode(mb_convert_encoding($value, 'UTF-8', 'GBK'));
			}
		}
		return $array;
	}
}

?>