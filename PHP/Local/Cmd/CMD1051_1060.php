<?php

class CMD1051_1060{    
    // 制限回数取得
    public static function GetShopLimitedCount_1051($json){
        $acc = $json->{'acc'};
        $shopId = $json->{'shopId'};

        $result = ShopLimitedCountClass::GetShopLimitedCount($acc, $shopId);

        $limitedCount = empty($result['limitedCount']) ? 0 : $result['limitedCount'];

        Common::Send($limitedCount);
    }

    public static function GetEquipmentInfo_1052($json){
        $acc = $json->{'acc'};
        $itemGuid = $json->{'itemGuid'};

        $sql = "SELECT * FROM equipment WHERE item_guid=$itemGuid";
        $result = MySqlPDB::$pdo->query($sql)->fetch();

        Common::Send(array(
            'skills'=>$result['skills'],
        ));
    }

    public static function GetEquipmentInfoList_1053($json){
        $acc = $json->{'acc'};

        $sql = "SELECT items.id,items.itemId,items.islocked,equipment.skills FROM items 
            LEFT JOIN equipment ON items.id = equipment.item_guid 
            WHERE items.isDiscard=0 
            AND equipment.isDiscard=0 
            AND items.acc='$acc' 
            AND items.itemId > 10000";
        $result = MySqlPDB::$pdo->query($sql);
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            $skills = $row['skills'];
            $items[]=array(
                'id'=>$row['id'],
                'itemId'=>$row['itemId'],
                'islocked'=>$row['islocked'],
                'skills'=>"".$row['skills'],
            );
        }

        if (empty($items)){
            Common::Send("");
        }else{
            Common::Send($items);
        }
    }

    // 装備データ追加
    public static function AppraisalEquipment_1054($json){
        $acc = $json->{'acc'};
        $itemGuid = $json->{'itemGuid'};
        $equipmentId = $json->{'equipmentId'};

        $config = ConfigClass::ReadConfig('Equipment')[$equipmentId];
        $PondIds = explode(",", $config['PondId']);

        // 重複チェック
        $sql = "SELECT * FROM equipment WHERE item_guid=$itemGuid";
        $result = MySqlPDB::$pdo->query($sql)->fetch();
        if (!empty($result)){
            Common::error(1054001);
            return;
        }

        // スキルランダム追加
        for ($i = 0; $i < count($PondIds); $i++){
            $result = BonusClass::GetRandomBonusByPond($PondIds[$i]);
            if (empty($result))
                continue;

            $bonusList[]=array(
                'bonusId'=>$result[0],
            );
        }

        $skills = "";
        if (!empty($bonusList)){
            $skills = $bonusList[0]['bonusId'];
            for ($i = 1; $i < count($bonusList); $i++) {
                $skills = $skills.','.$bonusList[$i]['bonusId'];
            }
        }

        // 装備データ追加
        ItemClass::AddEquipment($itemGuid,$skills);

        // ロック状態になる（スキルゲット完了）
        $sql = "UPDATE items SET islocked=1 WHERE id=$itemGuid";
        MySqlPDB::$pdo->query($sql);

        Common::Send($skills);
    }

    // 経験値を追加
    public static function AddExp_1055($json){
        $acc = $json->{'acc'};
        $exp = $json->{'exp'};

        // 現在の経験値ゲット
        $sql = "SELECT exp,lv FROM userdata WHERE acc ='$acc'";
        $result = MySqlPDB::$pdo->query($sql)->fetch();
        $curExp = $result['exp'] + $exp;
        $lv = $result['lv'];

        $upLv = 0;
        $config = ConfigClass::ReadConfig('Character')[$lv];
        $nextLvUpExp = $config['LvUpExp'];

        while ($curExp >= $nextLvUpExp){
            $upLv++;
            $curExp -= $nextLvUpExp;

            // 次レベルアップコストExpをゲット
            $config = ConfigClass::ReadConfig('Character')[$lv + $upLv];
            $nextLvUpExp = $config['LvUpExp'];
        }

        // データ更新
        $sql = "UPDATE userdata SET exp=$curExp,lv=lv+$upLv WHERE acc='$acc'";
        MySqlPDB::$pdo->query($sql);
        
        Common::Send(array(
            'lv'=>$lv + $upLv,
            'exp'=>$curExp,
        ));
    }
}