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
	
	function __destruct()
	{
	}
	
	private function register()
	{
		$this->container->bind('IcbcClient', 'ICBC\B2BPay\Application\IcbcClient');
		$this->container->bind('App', 'ICBC\B2BPay\Config\App');
		$this->container->bind('Config', 'ICBC\B2BPay\Config\Config');
		$this->container->bind('AuthInterface', 'ICBC\B2BPay\Contracts\AuthInterface');
		$this->container->bind('HttpAbstract', 'ICBC\B2BPay\Contracts\HttpAbstract');
		$this->container->bind('RequestFilter', 'ICBC\B2BPay\Filters\RequestFilter');
		$this->container->bind('ResponseFilter', 'ICBC\B2BPay\Filters\ResponseFilter');
		$this->container->bind('AES', 'ICBC\B2BPay\UtilTools\AES');
		$this->container->bind('IcbcCa', 'ICBC\B2BPay\UtilTools\IcbcCa');
		$this->container->bind('IcbcEncrypt', 'ICBC\B2BPay\UtilTools\IcbcEncrypt');
		$this->container->bind('IcbcSignature', 'ICBC\B2BPay\UtilTools\IcbcSignature');
		$this->container->bind('RSA', 'ICBC\B2BPay\UtilTools\RSA');
	}
	
	public static function __callStatic($common, $request, $msgId, $appAuthToken)
    {
		$client = $this->container->make('IcbcClient');
		$client->execute($common, $request, $msgId, $appAuthToken);
    }
}

?>