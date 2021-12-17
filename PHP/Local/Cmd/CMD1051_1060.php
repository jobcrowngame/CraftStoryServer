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

        $config = ConfigClass::ReadConfig('Item');

        $sql = "SELECT items.id,items.itemId,items.islocked,equipment.skills,equipment.isDiscard FROM items 
            LEFT JOIN equipment ON items.id = equipment.item_guid 
            WHERE items.isDiscard=0 
            AND items.acc='$acc'";
        $result = MySqlPDB::$pdo->query($sql);
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            if  ($row['isDiscard'] == 1)
                continue;

            if  (empty($config[$row['itemId']]))
                continue;

            if ($config[$row['itemId']]['Type'] != 5001 && $config[$row['itemId']]['Type'] != 5002)
                continue;

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
    
    // フロア到達
    public static function ArriveFloor_1056($json) {
        $acc = $json->{'acc'};
        $arrivedFloor = $json->{'arrivedFloor'};
        try{
            StatisticsClass::UpdateMaxArrivedFloor($acc, $arrivedFloor);
            StatisticsClass::UpdateLastFloorCount($acc, $arrivedFloor);
            
            Common::Send('');
        } catch (Exception $e){
            LoggerClass::E()->error($e->getMessage(), "\n");
            LoggerClass::E()->error($e);
            Common::error(999);
        }
    }

    // 復活
    public static function Resurrection_1057($json){
        $acc = $json->{'acc'};

        $coin = 'coin1';

        // 設定ファイルからコストをゲット
        $xml = @simplexml_load_file('setting.xml');
        $count = $xml->setting[0]->ResurrectionCost;

        // クラフトシード消耗
        $result = ShopClass::CostCoin($acc, $coin, $count);

        if ($result == 1){
            Common::error(1057001);
        }else{
            Common::Send("");
        }
    }

    // トータル設置済ブロック数を取得
    public static function GetTotalSetBlockCount_1058($json){
        $acc = $json->{'acc'};
        $result = StatisticsClass::GetTotalSetBlockCount($acc);
        $totalSetBlockCount = empty($result['totalSetBlockCount']) ? 0 : $result['totalSetBlockCount'];
        Common::Send($totalSetBlockCount);        
    }

    // タスク
    public static function MainTaskEnd_1059($json){
        $acc = $json->{'acc'};
        $taskId = $json->{'taskId'};

        $config = ConfigClass::ReadConfig('MainTask')[$taskId];
        $clearCount = $config['ClearCount'];
        $bonus = $config['Bonus'];
        $Type = $config['Type'];  
        $Next = $config['Next'];  

        $sql = "SELECT * FROM limited WHERE acc='$acc'";
        $result = MySqlPDB::$pdo->query($sql)->fetch();
        $curTaskId = $result['main_task'];
        $curClearCount = $result['main_task_count'];

        // タスクIDチェック
        if  ($taskId != $curTaskId){
            Common::error(1059001);
            return;
        }

        // チュートリアル以外
        if  ($Type != 1 && $Type != 2 && $Type != 5 && $Type != 11){
             // タスククリア数チェック
            if  ($curClearCount < $clearCount){
                Common::error(1059002);
                return;
            }
        }

        // ボーナス与える
        $bonus = $config['Bonus'];
        BonusClass::AddBonus($acc, $bonus);

        // タスクデータ更新
        $sql = "UPDATE limited SET main_task=$Next,main_task_count=0 WHERE acc='$acc'";
        MySqlPDB::$pdo->query($sql);

        Common::Send("");
    }

    // メインタスククリア数追加
    public static function AddMainTaskClearCount_1060($json){
        $acc = $json->{'acc'};
        $count = $json->{'count'};

         // タスクデータ更新
         $sql = "UPDATE limited SET main_task_count=main_task_count + $count WHERE acc='$acc'";
         MySqlPDB::$pdo->query($sql);
 
         Common::Send("");
    }
}