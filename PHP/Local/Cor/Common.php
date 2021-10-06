<?php

class Common{
    public static function Send($msg){
        $result=array(
            'error'=>0,
            'result'=>$msg
        );
        $result = json_encode($result, JSON_NUMERIC_CHECK);
        // echo $result."<br>";
        $cdata = CryptClass::encryptRJ256($result);
        echo $cdata;

        return $result;
    }

    public static function error($errCode){
        $result=array(
            'error'=>$errCode,
            'result'=>""
        );
        $result = json_encode($result, JSON_NUMERIC_CHECK);
        // echo $result."<br>";
        $cdata = CryptClass::encryptRJ256($result);
        echo $cdata;
    }
    
    public static function IsLogin($pdo, $token, $acc){
        $stmt = $pdo->query("SELECT session FROM userdata
            WHERE acc = '". $acc. "' & session = '". $token. "'");
        return  $stmt != null;
    }

    public static function GetItemsByBonus($bonus){
        $config = ConfigClass::ReadConfig('Bonus')[$bonus];
        $items = array();
        $items = Common::GetItemsByBonusIDandCount($items, $config['Bonus1'], $config['BonusCount1']);
        $items = Common::GetItemsByBonusIDandCount($items, $config['Bonus2'], $config['BonusCount2']);
        $items = Common::GetItemsByBonusIDandCount($items, $config['Bonus3'], $config['BonusCount3']);
        $items = Common::GetItemsByBonusIDandCount($items, $config['Bonus4'], $config['BonusCount4']);
        $items = Common::GetItemsByBonusIDandCount($items, $config['Bonus5'], $config['BonusCount5']);
        $items = Common::GetItemsByBonusIDandCount($items, $config['Bonus6'], $config['BonusCount6']);
        return $items;
    }

    public static function GetItemsByBonusIDandCount($items, $bonusId, $count) {
        if($bonusId > 0){
            if (empty($items[$bonusId])){
                $items[$bonusId] = $count;
            }else{
                $items[$bonusId] += $count;
            }
        }
        return $items;
    }

    public static function random($length = 8)
	{
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

		$retstr = '';
		$data = openssl_random_pseudo_bytes($length);
		$num_chars = strlen($chars);
		for ($i = 0; $i < $length; $i++)
		{
			$retstr .= substr($chars, ord(substr($data, $i, 1)) % $num_chars, 1);
		}
		return $retstr;
	}
}
