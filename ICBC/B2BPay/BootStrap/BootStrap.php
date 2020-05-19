<?php

namespace ICBC\B2BPay\BootStrap;

use \ICBC\B2BPay\Contracts\Container;

class BootStrap
{
	function __construct()
	{
		self::register();
	}
	
	private static function register()
	{
		Container::bind('IcbcClient', 'ICBC\B2BPay\Application\IcbcClient');
		Container::bind('RequestFilter', 'ICBC\B2BPay\Filters\RequestFilter');
		Container::bind('ResponseFilter', 'ICBC\B2BPay\Filters\ResponseFilter');
		
		Container::singleton('Config', 'ICBC\B2BPay\Config\Config');
		Container::singleton('App', 'ICBC\B2BPay\Config\App');
		Container::singleton('AES', 'ICBC\B2BPay\UtilTools\AES');
		Container::singleton('IcbcCa', 'ICBC\B2BPay\UtilTools\IcbcCa');
		Container::singleton('RSA', 'ICBC\B2BPay\UtilTools\RSA');
		Container::singleton('IcbcEncrypt', 'ICBC\B2BPay\UtilTools\IcbcEncrypt');
		Container::singleton('IcbcSignature', 'ICBC\B2BPay\UtilTools\IcbcSignature');
	}
	
	public static function __callStatic($method, $arguments)
    {
		if (strtolower($method) != 'run')
		{
			throw new \Exception("the method called error");
		}
		else
		{
			$bootstrap = new BootStrap();
			$context = Container::make('IcbcClient');
			if (!empty($arguments))
			{
				$argv = array_shift($arguments);
			}
			return $context->execute($argv['common'], $argv['request'], $argv['msgId'], $argv['appAuthToken']);
		}
    }
}

?>