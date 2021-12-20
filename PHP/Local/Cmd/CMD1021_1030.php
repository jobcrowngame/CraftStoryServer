<?php

class CMD1021_1030{
    public static function GetNewEmailCount_1021($json){
        $acc = $json->{'acc'};

        $sql = "SELECT COUNT(*) as num FROM email 
            WHERE isDiscard=0 
                && acc = '$acc' 
                && is_already_read = 0";

        $result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        Common::Send(array(
			'count'=>$result['num'],
		));
    }

    public static function GetRandomBonus_1022($json){
        $id = $json->{'randomBonusId'};

        $config = ConfigClass::ReadConfig('RandomBonus')[$id];

        $arr = array();
        
        for ($i = 0; $i < $config['Count01']; $i++){
            $result = BonusClass::GetRandomBonusByPond($config['Pond01']);
            if  (!empty($result[0])){
                if (array_key_exists($result[0], $arr)){
                    $arr[$result[0]]++;
                }else{
                    $arr[$result[0]] = 1;
                }
            }
        }

        for ($i = 0; $i < $config['Count02']; $i++){
            $result = BonusClass::GetRandomBonusByPond($config['Pond02']);
            if  (!empty($result[0])){
                if (array_key_exists($result[0], $arr)){
                    $arr[$result[0]]++;
                }else{
                    $arr[$result[0]] = 1;
                }
            }
        }

        for ($i = 0; $i < $config['Count03']; $i++){
            $result = BonusClass::GetRandomBonusByPond($config['Pond03']);
            if  (!empty($result[0])){
                if (array_key_exists($result[0], $arr)){
                    $arr[$result[0]]++;
                }else{
                    $arr[$result[0]] = 1;
                }
            }
        }

        foreach (array_keys($arr) as $key){
            $bonusList[]=array(
                'bonus'=>$key,
                'count'=>$arr[$key],
            );
        }

        if (empty($bonusList))
            Common::Send("");
        else
            Common::Send($bonusList);
    }

    public static function GetNoticeList_1023($json){
        $sql = "SELECT * FROM notice WHERE active=1 ORDER BY CASE WHEN priority IS NOT NULL THEN 0 ELSE 1 END, priority, activedate DESC;";
        $result = MySqlPDB::$pdo->query($sql);
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            $items[]=array(
                'id'=>$row['id'],
                'category'=>$row['category'],
                'newflag'=>$row['newflag'],
                'activedate'=>$row['activedate'],
                'priority'=>$row['priority'],
                'pickup'=>$row['pickup'],
                'title'=>$row['title'],
                'titleIcon'=>$row['titleIcon'],
                'detailIcon'=>$row['detailIcon'],
                'url'=>$row['url']
            );
        }

        if (empty($items))
            Common::Send("");
        else
            Common::Send($items);
    }

    public static function GetNotice_1024($json){
        $guid = $json->{'guid'};

        $sql = "SELECT * FROM notice WHERE id=$guid";
        $result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        Common::Send(array(
            'text'=>$result['text']
        ));
    }

    public static function GetMyShopInfo_1025($json){
        $acc = $json->{'acc'};

        $sql = "SELECT * FROM myshop WHERE isDiscard=0 && acc = '$acc'";
		$result = MySqlPDB::$pdo->query($sql);

		if (empty($result)){
			$items = "";
		}else{
			while($row = $result->fetch(PDO::FETCH_ASSOC)){
				$items[]=array(
					'myshopid'=>$row['myshopid'],
					'itemId'=>$row['itemid'],
					'data'=>$row['data'],
					'site'=>$row['site'],
					'created_at'=>$row['created_at'],
					'newName'=>$row['newname'],
                    'icon'=>$row['icon']
				);
			}
		}

		if (empty($items)){
			$items = "";
		}

		Common::Send($items);
    }

    public static function ReceiveEmailItem_1026($json){
        $acc = $json->{'acc'};
        $guid = $json->{'guid'};

        $sql = "SELECT related_data,is_already_received FROM email WHERE email_id=$guid";
        $result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        if ($result["is_already_received"] == 1){
            return;
        }

        $data = explode("^", $result['related_data']);
        $items = explode(",", $data[0]);
        $itemCounts = explode(",", $data[1]);
        ItemClass::AddItems2($acc, $items, $itemCounts);

        $sql = "UPDATE email SET is_already_received=1 WHERE email_id=$guid";
        MySqlPDB::$pdo->query($sql);

        Common::Send("");
    }

    // サブスクリプションを買う
    public static function BuySubscription_1027($json){
        $acc = $json->{'acc'};
        $type = $json->{'type'};

        $shopId = ShopClass::GetShopIdByType($type);
        $config = ConfigClass::ReadConfig('Shop');
		$productId = $config[$shopId]['Name'];
		$money = $config[$shopId]['CostCount'];
        $bonusId = $config[$shopId]['Bonus'];

        // サブスクリプションを追加
        $sql = "INSERT INTO subscription (acc, type, productId, money) 
            VALUES ('$acc',$type,'$productId',$money)";
        MySqlPDB::$pdo->query($sql);

        // サブスクリプションボーナスをもらう状態にする
        switch  ($type){
            case 1: $sql = "UPDATE limited SET subscription_mail_added01=1 WHERE acc='$acc'"; break;
            case 2: $sql = "UPDATE limited SET subscription_mail_added02=1 WHERE acc='$acc'"; break;
            case 3: $sql = "UPDATE limited SET subscription_mail_added03=1 WHERE acc='$acc'"; break;
        }
        MySqlPDB::$pdo->query($sql);

        // サブスクリプションのレベルを更新
        switch  ($type){
            case 1: $sql = "UPDATE userdata SET subscriptionLv01=1 WHERE acc='$acc'"; break;
            case 2: $sql = "UPDATE userdata SET subscriptionLv02=1 WHERE acc='$acc'"; break;
            case 3: $sql = "UPDATE userdata SET subscriptionLv03=1 WHERE acc='$acc'"; break;
        }
        MySqlPDB::$pdo->query($sql);

        // ボーナスメールを送る
        switch  ($type){
            case 1: EmailClass::AddEmailInItem($acc, 1); break;
            case 2: EmailClass::AddEmailInItem($acc, 2); break;
            case 3: EmailClass::AddEmailInItem($acc, 3); break;
        }

        Common::Send("");
    }

    // サブスクリプション状態をゲット
    public static function GetSubscriptionInfo_1028($json){
        $acc = $json->{'acc'};

        $sql = "SELECT * FROM userdata WHERE acc='$acc'";
        $result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
        $subscriptionLv01 = $result['subscriptionLv01'];
        $subscriptionLv02 = $result['subscriptionLv02'];
        $subscriptionLv03 = $result['subscriptionLv03'];

        $sql = "SELECT type,created_at FROM subscription WHERE isDiscard=0 && acc='$acc'";
        $result = MySqlPDB::$pdo->query($sql);
        $updateTime01 = "";
        $updateTime02 = "";
        $updateTime03 = "";
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            switch ($row['type']){
                case 1: $updateTime01 = empty($row['created_at']) ? "" : $row['created_at']; break;
                case 2: $updateTime02 = empty($row['created_at']) ? "" : $row['created_at']; break;
                case 3: $updateTime03 = empty($row['created_at']) ? "" : $row['created_at']; break;
            }
        }

        Common::Send(array(
            'subscriptionLv01'=> $subscriptionLv01,
            'subscriptionLv02'=> $subscriptionLv02,
            'subscriptionLv03'=> $subscriptionLv03,
            'updateTime01'=> $updateTime01,
            'updateTime02'=> $updateTime02,
            'updateTime03'=> $updateTime03,
        ));
    }

    public static function GuideEnd_1029($json){
		$acc = $json->{'acc'};
		$guidId = $json->{'guidId'};

        if  ($guidId == 1){
            $columName = "guide_end";
        }else if ($guidId == 2){
            $columName = "guide_end2";
        }else if ($guidId == 3){
            $columName = "guide_end3";
        }else if ($guidId == 4){
            $columName = "guide_end4";
        }else if ($guidId == 6){
            $columName = "guide_end5";
        }

		$sql = "UPDATE limited SET $columName = 1 WHERE acc = '$acc'";
		MySqlPDB::$pdo->query($sql);

        if  ($guidId == 1){
            EmailClass::AddEmailInItem($acc, 2002);
        }else if ($guidId == 3){
            EmailClass::AddEmailInItem($acc, 2001);
        }

        Common::Send("");
	}

    public static function Gacha_1030($json){
        $acc = $json->{'acc'};
        $gachaId = $json->{'gachaId'};
        $gachaGroup = $json->{'gachaGroup'};

        $gachaConfig = ConfigClass::ReadConfig('Gacha')[$gachaId];
        // ランダムボーナスを手にいる
        for ($i = 0; $i < $gachaConfig['GachaCount']; $i++){
            $result = BonusClass::GetRandomBonusByPond($gachaConfig['PondId']);
            $bonusList[]=array(
                'bonusId'=>$result[0],
                'rare'=>$result[1],
            );

            // アイテムをDBに追加
            BonusClass::AddBonus($acc, $result[0]);
        }

        // ルーレット
        $RouletteIndex = BonusClass::GetRouletteIndex($acc, $gachaConfig['Roulette']);

        // coinを消耗
        switch  ($gachaConfig['Cost']){
            case 9000: ShopClass::CostCoin($acc, 'coin1', $gachaConfig['CostCount']); break;
            case 9001: ShopClass::CostCoin($acc, 'coin2', $gachaConfig['CostCount']); break;
            case 9002: ShopClass::CostCoin($acc, 'coin3', $gachaConfig['CostCount']); break;
            case 9003: ItemClass::RemoveItemByItemId($acc, $gachaConfig['Cost'], $gachaConfig['CostCount']); break;
        }

        // 統計
        StatisticsClass::AddGacha($acc, $gachaGroup);

        Common::Send(array(
            'bonusList'=>$bonusList,
            'index'=>$RouletteIndex
        ));
    }
}