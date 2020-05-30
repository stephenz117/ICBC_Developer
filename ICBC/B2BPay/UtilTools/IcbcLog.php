<?php

namespace ICBC\B2BPay\UtilTools;

class IcbcLog
{
	public static function logHeaderAndContent($headers, $content)
	{
		$logdir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'logs';
		if (!is_dir($logdir))
		{
			mkdir($logdir, '0777');
		}
		$logfile = $logdir . DIRECTORY_SEPARATOR . date('Y-m-d') . '.log';
		$filecontent = date('Y-m-d H:i:s') . ' --- ' . $content['biz_content']['partnerSeq'] . PHP_EOL . 
			' Headers --- ' . $content['biz_content']['partnerSeq'] . ' : ' . json_encode($headers) . PHP_EOL . 
			' Content --- ' . $content['biz_content']['partnerSeq'] . ' : ' . json_encode($content) . PHP_EOL;
		@file_put_contents($logfile, $filecontent, FILE_APPEND);
		return true;
	}
}

?>