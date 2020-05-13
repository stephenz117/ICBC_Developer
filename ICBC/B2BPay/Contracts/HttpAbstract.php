<?php

namespace ICBC\B2BPay\Contracts;

abstract class HttpAbstract
{
	protected static $version_header_name;
	protected static $api_version;
	
	protected static function doGet($url, $params, $charset)
	{
		$headers = array();
		$headers[self::$version_header_name] = self::$api_version;
		$getUrl = self::buildGetUrl($url, $params, $charset);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $getUrl);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 8000);
		curl_setopt($ch, CURLOPT_TIMEOUT_MS, 30000);

		$response = curl_exec($ch);
		$resinfo = curl_getinfo($ch);
		curl_close($ch);

		if ($resinfo["http_code"] != 200)
		{
			throw new Exception("response status code is not valid. status code: ".$resinfo["http_code"]);
		}

		return $response;
	}

	protected static function doPost($url, $params, $charset)
	{
		$headers = array();
		$headers[] = 'Expect:';
		$headers[self::$version_header_name] = self::$api_version;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 8000);
		curl_setopt($ch, CURLOPT_TIMEOUT_MS, 30000);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

		$response = curl_exec($ch);
		$resinfo = curl_getinfo($ch);
		curl_close($ch);

		if ($resinfo["http_code"] != 200)
		{
			throw new Exception("response status code is not valid. status code: ".$resinfo["http_code"]);
		}
		return $response;
	}

	private static function buildGetUrl($strUrl, $params, $charset)
	{
		if ($params == null || count($params) == 0)
		{
			return $strUrl;
		}
		$buildUrlParams = http_build_query($params);
		if (strrpos($strUrl, '?', 0) != (strlen($strUrl) + 1))
		{
			return $strUrl . '?' . $buildUrlParams;
		}
		return $strUrl . $buildUrlParams;
	}

	protected static function buildOrderedSignStr($path, $params)
	{
		$isSorted = ksort($params);
		$comSignStr = $path . '?';

		$hasParam = false;
		foreach ($params as $key => $value)
		{
			if (empty($key) || empty($value))
			{
				//do nothing
			}
			else
			{
				if ($hasParam)
				{
					$comSignStr = $comSignStr . '&';
				}
				else
				{
					$hasParam = true;
				}
				$comSignStr = $comSignStr . $key . '=' . $value;
			}
		}

		return $comSignStr;
	}
}

?>