<?php

namespace ICBC\B2BPay\Filters;

class RequestFilter
{
	public function validRequestParams($request_params = [])
	{
		if (!empty($request_params))
		{
			foreach ($request_params as $request_param)
			{
				if (!$this->validRequestParam($request_param['param_name'], $request_param['param_value'], $request_param['patten'], $request_param['required']))
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
	
	public function validRequestParam($param_name = '', $param_value = '', $patten = [], $required = false)
	{
		if ($required && empty($param_value))
		{
			throw new Exception("the request param {$param_name} cannot be empty");
		}
		else
		{
			//do something for patten
		}
		return true;
	}
}

?>