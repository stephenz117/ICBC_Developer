<?php

namespace ICBC\B2BPay\Config;

class Config
{
	public const VERSION = "1.0.0";
	
	public const APIVERSION = "v2_20170324";
	
	public const SIGN_TYPE = "sign_type";

	public const SIGN_TYPE_RSA = "RSA";
	
	public const SIGN_TYPE_RSA2 = "RSA2";

	public const SIGN_TYPE_SM2 = "SM2";
	
	public const SIGN_TYPE_CA = "CA";

	public const SIGN_SHA1RSA_ALGORITHMS = "SHA1WithRSA";

	public const SIGN_SHA256RSA_ALGORITHMS = "SHA256WithRSA";

	public const ENCRYPT_TYPE_AES = "AES";

	public const APP_ID = "app_id";

	public const FORMAT = "format";

	public const TIMESTAMP = "timestamp";

	public const SIGN = "sign";

	public const APP_AUTH_TOKEN = "app_auth_token";

	public const CHARSET = "charset";

	public const NOTIFY_URL = "notify_url";

	public const RETURN_URL = "return_url";

	public const ENCRYPT_TYPE = "encrypt_type";
	
	public const BIZ_CONTENT_KEY = "biz_content";

	public const DATE_TIME_FORMAT = "Y-m-d H:i:s";

    public const DATE_TIMEZONE = "Asia/Shanghai";

	public const CHARSET_UTF8 = "UTF-8";

	public const CHARSET_GBK = "GBK";

	public const FORMAT_JSON = "json";

	public const FORMAT_XML = "xml";

	public const CA = "ca";
	
	public const PASSWORD = "password";
	
	public const RESPONSE_BIZ_CONTENT = "response_biz_content";

	public const MSG_ID = "msg_id";

	public const VERSION_HEADER_NAME = "APIGW-VERSION";
}

?>