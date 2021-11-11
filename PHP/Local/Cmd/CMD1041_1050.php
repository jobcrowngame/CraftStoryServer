<?php

class CMD1041_1050{
    public static function ExchangePoints_1041($json){
        $acc = $json->{'acc'};
        $point = $json->{'point'};
        $money = $json->{'money'};
        $email = $json->{'email'};

        $result = ShopClass::CostCoin($acc, 'coin3', $point);
        if ($result == 1){
            Common::error(1041001);
            return;
        }

        $sql = "INSERT INTO exchange_points(acc,email,point,money) VALUES('$acc','$email',$point,$money)";
        MySqlPDB::$pdo->query($sql);

        $sql = "SELECT id FROM exchange_points WHERE acc='$acc' ORDER BY id DESC LIMIT 1;";
        $result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
        $guid = $result['id'];

        $sql = "UPDATE userdata SET email='$email' WHERE acc='$acc'";
        MySqlPDB::$pdo->query($sql);

        $mailId = 10000;
        $config = ConfigClass::ReadConfig('Mail');
        $title = $config[$mailId]['Title'];
        $msg = $config[$mailId]['Msg'];
        $msg = sprintf($msg, $guid, $email);
        EmailClass::AddEmail($acc, $title, $msg);

        Common::Send(array(
            'guid'=>$guid,
        ));
    }

    public static function GachaAddBonusAgain_1042($json){
        $acc = $json->{'acc'};
        $gachaId = $json->{'gachaId'};

        $gachaConfig = ConfigClass::ReadConfig('Gacha')[$gachaId];

        // ルーレット
        $RouletteIndex = BonusClass::GetRouletteIndex($acc, $gachaConfig['Roulette']);

        Common::Send(array(
            'index'=>$RouletteIndex
        ));
    }

    public static function GetItemRelationData_1043($json){
        $itemGuid = $json->{'itemGuid'};

        $sql = "SELECT relationData FROM items WHERE id=$itemGuid";
        $result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        Common::Send($result['relationData']);
    }

    // ミッション情報をゲット
    public static function GetMissionInfo_1044($json){
        $acc = $json->{'acc'};

        $sql = "SELECT clear_mission_main,clear_mission_daily,clear_mission_limit,login_number FROM limited WHERE acc='$acc'";
        $result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        $main = empty($result['clear_mission_main']) ? "" : $result['clear_mission_main'];
        $daily = empty($result['clear_mission_daily']) ? "" : $result['clear_mission_daily'];
        $limit = empty($result['clear_mission_limit']) ? "" : $result['clear_mission_limit'];

        Common::Send(array(
            'main'=>$main,
            'daily'=>$daily,
            'limit'=>$limit,
            'loginNumber'=>$result['login_number'],
        ));
    }

    public static function ClearMission_1045($json){
        $acc = $json->{'acc'};
        $missionId = $json->{'missionId'};
        $missionType = $json->{'missionType'};
        $count = $json->{'count'};

        $columName = "";
        if ($missionType == 1) $columName = "clear_mission_daily";
        else if ($missionType == 2) $columName = "clear_mission_main";
        else if ($missionType == 3) $columName = "clear_mission_limit";

        $sql = "SELECT $columName FROM limited WHERE acc='$acc'";
        $result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        if (empty($result[$columName])){
            $arr[0] = $missionId."^".$count."^"."0";
        }else{
            
            $arr = explode(",", $result[$columName]);
            for($i = 0; $i < count($arr); $i++){
                $mission = explode("^", $arr[$i]);
                if ($mission[0] == $missionId){
                    $mission[1] += $count;
                    $newValue = $missionId."^".$mission[1]."^".$mission[2];
                    $arr[$i] = $newValue;
                }
            }
    
            if(empty($newValue)){
                $arr[count($arr)] = $missionId."^".$count."^"."0";
            }
        }

        $newValue = $arr[0];
        for($i = 1; $i < count($arr); $i++){
            $newValue = $newValue.",".$arr[$i];
        }

        $sql = "UPDATE limited SET $columName='$newValue' WHERE acc='$acc'";
        MySqlPDB::$pdo->query($sql);
        
        if ($missionId == 4 && $missionType == 1) {
            StatisticsClass::AddTotalSetBlockCount($acc);
        }

        Common::Send("");
    }

    public static function GetMissionBonus_1046($json){
        $acc = $json->{'acc'};
        $missionId = $json->{'missionId'};
        $missionType = $json->{'missionType'};

        $columName = "";
        if ($missionType == 1) $columName = "clear_mission_daily";
        else if ($missionType == 2) $columName = "clear_mission_main";
        else if ($missionType == 3) $columName = "clear_mission_limit";

        $config = ConfigClass::ReadConfig('Mission');
        $bonus = $config[$missionId]['Bonus'];
        $targetNum = $config[$missionId]['EndNumber'];

        $sql = "SELECT $columName FROM limited WHERE acc='$acc'";
        $result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        if (empty($result[$columName])){
            Common::error(1046001);
            return;
        }else{
            $newValue = "";
            $arr = explode(",", $result[$columName]);
            for($i = 0; $i < count($arr); $i++){
                $mission = explode("^", $arr[$i]);
                if ($mission[0] == $missionId){
                    // 2回もらうことは出来ません
                    if($mission[2] == 1){
                        Common::error(1046002);
                        return;
                    }

                    // 目標に達成しなかった場合、ボーナスはもらいません
                    if ($mission[1]  < $targetNum){
                        Common::error(1046001);
                        return;
                    }

                    // Get状態に変更
                    $newValue = $missionId."^".$mission[1]."^"."1";
                    $arr[$i] = $newValue;
                    break;
                }
            }

            if (empty($newValue)){
                Common::error(1046001);
                return;
            }
        }

        // DBに登録
        $newValue = $arr[0];
        for($i = 1; $i < count($arr); $i++){
            $newValue = $newValue.",".$arr[$i];
        }
        $sql = "UPDATE limited SET $columName='$newValue' WHERE acc='$acc'";
        MySqlPDB::$pdo->query($sql);

        // ボーナスをもらう
        BonusClass::AddBonus($acc, $bonus);

        Common::Send("");
    }

    // マイショップいいね機能
    public static function MyShopGoodEvent_1047($json){
        $acc = $json->{'acc'};
        $targetAcc = $json->{'targetAcc'};

        // 自分の毎日いいね数チェック
        $sql = "SELECT goodNum_daily FROM limited WHERE acc = '$acc'";
        $result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
        if ($result['goodNum_daily'] >= 3){
            Common::error(1047001); 
            return;
        }

        // 自分の毎日いいね数追加
        $sql = "UPDATE limited SET goodNum_daily = goodNum_daily + 1 WHERE acc = '$acc'";
        MySqlPDB::$pdo->query($sql);

        // 目標の総いいね数追加
        $sql = "UPDATE limited SET goodNum_total = goodNum_total + 1 WHERE acc = '$targetAcc'";
        MySqlPDB::$pdo->query($sql);

        Common::Send("");
    }

    // アイテムGUIDで設計図データをゲット
    public static function GetBlueprintPreviewDataByItemGuid_1048($json){
        $acc = $json->{'acc'};
        $guid = $json->{'guid'};

        $sql = "SELECT relationData FROM items WHERE id=$guid";
        $result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        if (empty($result)){
            Common::error(1039001);
            return;
        }

        Common::Send($result['relationData']);
    }
    
    // ガチャ通算回数取得
    public static function GetGacha_1049($json){
        $acc = $json->{'acc'};
        $gachaGroup = $json->{'gachaGroup'};

        $result = StatisticsClass::GetGacha($acc, $gachaGroup);

        $gacha = empty($result['gacha']) ? 0 : $result['gacha'];

        Common::Send($gacha);
    }
    
    // 制限回数取得
    public static function GetAllShopLimitedCounts_1050($json){
        $acc = $json->{'acc'};

        $result = ShopLimitedCountClass::GetAllShopLimitedCounts($acc);

        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            $items[]=array(
                'shopId'=>$row['shopId'],
                'limitedCount'=>$row['limitedCount'],
            );
        }

        if (empty($items))
            Common::Send("");
        else
            Common::Send($items);
    }
}