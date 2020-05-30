<?php

namespace ICBC\B2BPay\Filters;

class ResponseFilter
{
	public function validResponseParams($response_params = [])
	{
		if (!empty($response_params))
		{
			foreach ($response_params as $response_param)
			{
				if (!$this->validResponseParam($response_param['param_name'], $response_param['param_value'], $response_param['patten'], $response_param['required']))
				{
					return false;
				}
			}
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function validResponseParam($param_name = '', $param_value = '', $patten = [], $required = false)
	{
		if ($required && !empty($param_name) && empty($param_value))
		{
			throw new \Exception("the response param {$param_name} cannot be empty");
		}
		else
		{
			//do something for patten
		}
		return true;
	}
}

?>