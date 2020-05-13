<?php

namespace ICBC\B2BPay\Contracts;

interface AuthInterface
{
	public function sign;
	
	public function verify;
}


?>