<?php

namespace ICBC\B2BPay\UtilTools;

class IcbcLog
{
	public static function logHeaderAndContent($headers, $content)
	{
		$logdir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'logs';
		if (!is_dir($logdir))
		{
			mkdir($logdir);
		}
		$logfile = $logdir . DIRECTORY_SEPARATOR . date('Y-m-d') . '.log';
		$filecontent = date('Y-m-d H:i:s') . ' --- ' . $content['msg_id'] . PHP_EOL . 
			' Headers --- ' . $content['msg_id'] . ' : ' . json_encode($headers, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) . PHP_EOL . 
			' Content --- ' . $content['msg_id'] . ' : ' . json_encode($content, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) . PHP_EOL;
		@file_put_contents($logfile, $filecontent, FILE_APPEND);
		return true;
	}
}

?>