<?php

class PrivateKey {

    public static function CheckKey($inputkey){
        
        $xml = @simplexml_load_file('setting.xml');
			
		$key = $xml->setting[0]->CheckKey;

        if (!empty($inputkey) && $inputkey == $key){
            return 0;
        }else{
            echo "<script type='text/javascript'>alert('正しい暗号キーを入力してください。');</script>";
            return 1;
        }
    }
}