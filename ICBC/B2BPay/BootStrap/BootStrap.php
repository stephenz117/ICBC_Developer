<?php

namespace ICBC\B2BPay\BootStrap;

use \ICBC\B2BPay\Contracts\Container;

class BootStrap
{
	private $container;
	
	function __construct()
	{
		$this->container = new Container();
		$this->register();
	}
	
	private function register()
	{
		$this->container->bind('IcbcClient', 'ICBC\B2BPay\Application\IcbcClient');
		$this->container->bind('RequestFilter', 'ICBC\B2BPay\Filters\RequestFilter');
		$this->container->bind('ResponseFilter', 'ICBC\B2BPay\Filters\ResponseFilter');
		
		$this->container->singleton('Config', 'ICBC\B2BPay\Config\Config');
		$this->container->singleton('App', 'ICBC\B2BPay\Config\App');
		$this->container->singleton('AES', 'ICBC\B2BPay\UtilTools\AES');
		$this->container->singleton('IcbcCa', 'ICBC\B2BPay\UtilTools\IcbcCa');
		$this->container->singleton('RSA', 'ICBC\B2BPay\UtilTools\RSA');
		$this->container->singleton('IcbcEncrypt', 'ICBC\B2BPay\UtilTools\IcbcEncrypt');
		$this->container->singleton('IcbcSignature', 'ICBC\B2BPay\UtilTools\IcbcSignature');
	}
	
	public static function __callStatic($method, $arguments)
    {
		if (strtolower($method) != 'run')
		{
			throw new \Exception("the method called error");
		}
		else
		{
			$context = new BootStrap();
			$cctx = $context->container->make('IcbcClient');
			if (!empty($arguments))
			{
				$argv = array_shift($arguments);
			}
			return $cctx->execute($argv['common'], $argv['request'], $argv['msgId'], $argv['appAuthToken']);
		}
    }
}

?>