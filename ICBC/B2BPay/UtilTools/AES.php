<?php

namespace ICBC\B2BPay\UtilTools;

class AES
{
    public function AesEncrypt($plaintext, $key = null)
    {
        $plaintext = trim($plaintext);
        if ($plaintext == '') return '';
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
		
        $padding = $size - strlen($plaintext) % $size;
        $plaintext .= str_repeat(chr($padding), $padding);
		
        $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        $key=self::substr($key, 0, mcrypt_enc_get_key_size($module));
        $iv = str_repeat("\0", $size);
        mcrypt_generic_init($module, $key, $iv);
		
        $encrypted = mcrypt_generic($module, $plaintext);
		
        mcrypt_generic_deinit($module);
        mcrypt_module_close($module);
        return base64_encode($encrypted);
    }
	
    private static function strlen($string)
    {
        return extension_loaded('mbstring') ? mb_strlen($string,'8bit') : strlen($string);
    }
	
    private static function substr($string, $start, $length)
    {
        return extension_loaded('mbstring') ? mb_substr($string,$start,$length,'8bit') : substr($string,$start,$length);
    }
    
    public function AesDecrypt($encrypted, $key = null)
    {
        if ($encrypted == '') return '';
        $ciphertext_dec = base64_decode($encrypted);
        $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        $key=self::substr($key, 0, mcrypt_enc_get_key_size($module));
        
        $iv = str_repeat("\0", 16);
        mcrypt_generic_init($module, $key, $iv);
		
        $decrypted = mdecrypt_generic($module, $ciphertext_dec);
		
        mcrypt_generic_deinit($module);
        mcrypt_module_close($module);
        $a = rtrim($decrypted,"\0");
		
        return rtrim($decrypted,"\0");
    }
}

?>