<?php

namespace ICBC\B2BPay\Contracts;

interface AuthInterface
{
	public function sign($content, $privateKey, $algorithm);
	
	public function verify($content, $signature, $publicKey, $algorithm);
}


?>