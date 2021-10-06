<?php

class CryptClass{
    // ini_set('display_errors', 1);
	// error_reporting(E_ALL);

    public static function decryptRJ256($data)
    {
        $method= 'AES-128-CBC';
        $key=   '4h5f2h4d31h4f1gf';
        $iv=    'ggsd5g1h6r3f1h0d';
        $result = openssl_decrypt($data, $method, $key, 0, $iv);
        return $result;
    }


    public static function encryptRJ256($data)
    {
        $method= 'AES-128-CBC';
        $key=   '4h5f2h4d31h4f1gf';
        $iv=    'ggsd5g1h6r3f1h0d';
        $result = openssl_encrypt($data, $method, $key, 0,$iv );
        return $result;
    }
}