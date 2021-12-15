<?php

class CMD1001_1010{
    public static function GetItemList_1001($json){
        $acc = $json->{'acc'};
    
        $que = MySqlPDB::$pdo->query("SELECT * FROM items WHERE acc = '". $acc. "' && isDiscard = 0");
        while($row = $que->fetch(PDO::FETCH_ASSOC)){
            $items[]=array(
                'id'=>$row['id'],
                'itemId'=>$row['itemId'],
                'count'=>$row['count'],
                'newName'=>$row['newName'],
                'equipSite'=>$row['equipSite'],
                'textureName'=>$row['textureName'],
                'islocked'=>$row['islocked'],
                // 'relationData'=>$row['relationData'],
            );
        }
    
        if (empty($items))
            Common::Send("");
        else
            Common::Send($items);
    }

    public static function UseItem_1002($json){
        $acc = $json->{'acc'};
        $guid = $json->{'guid'};
        $count = $json->{'count'};

        try {
            ItemClass::CostItem($acc, $guid, $count);

            Common::Send("");
        }catch (PDOException $e) {
            LoggerClass::E()->error($e);
            Common::error(999);
        }
    }

    public static function RemoveItemByItemId_1003($json){
        $acc = $json->{'acc'};
        $itemId = $json->{'itemId'};
        $count = $json->{'count'};
        ItemClass::RemoveItemByItemId($acc, $itemId, $count);

        Common::Send("");
    }

    public static function AddItem_1004($json) {
        $acc = $json->{'acc'};
        $itemId = $json->{'itemId'};
        $count = $json->{'count'};
        ItemClass::AddItem($acc, $itemId, $count);

        Common::Send("");
    }

    public static function AddItemInData_1005($json){
        $acc = $json->{'acc'};
        $itemId = $json->{'itemId'};
        $count = $json->{'count'};
        $newName = $json->{'newName'};
        $rdata = $json->{'rdata'};
        $textureName = $json->{'textureName'};

        $sql = "INSERT INTO items (acc, itemId, newName, count, relationData, textureName) 
                VALUES ('$acc',$itemId,'$newName',1,'$rdata','$textureName')";
        MySqlPDB::$pdo->query($sql);

        Common::Send("");
    }

    public static function AddItems_1006($json) {
        $acc = $json->{'acc'};
        $items = $json->{'items'};

        $arr = json_decode($items,true);
        
        foreach ($arr as $row){
            $id = $row['itemId'];
            $count = $row['count'];
            ItemClass::AddItem($acc, $id, $count);
        }

        Common::Send("");
    }

    public static function EquipItem_1007($json){
        $acc = $json->{'acc'};
        $itemGuid = $json->{'guid'};
        $site = $json->{'site'};

        MySqlPDB::$pdo->query("UPDATE items SET equipSite = '". $site . "' WHERE id = ". $itemGuid. "");

        Common::Send("");
    }

    public static function Craft_1008($json){
		$acc = $json->{'acc'};
		$craft = $json->{'craftId'};
		$count = $json->{'count'};
    
        $config = ConfigClass::ReadConfig('Craft');
        
        if  ($config[$craft]['Cost1'] > 0){
            ItemClass::RemoveItemByItemId($acc, 
                $config[$craft]['Cost1'],
                $config[$craft]['Cost1Count'] * $count);
        }
        
        if  ($config[$craft]['Cost2'] > 0){
            ItemClass::RemoveItemByItemId($acc,
                $config[$craft]['Cost2'],
                $config[$craft]['Cost2Count'] * $count);
        }

        if  ($config[$craft]['Cost3'] > 0){
            ItemClass::RemoveItemByItemId($acc,
                $config[$craft]['Cost3'],
                $config[$craft]['Cost3Count'] * $count);
        }

        if  ($config[$craft]['Cost4'] > 0){
            ItemClass::RemoveItemByItemId($acc,
                $config[$craft]['Cost4'],
                $config[$craft]['Cost4Count'] * $count);
        }

        ItemClass::AddItem($acc,$config[$craft]['ItemID'], $count);

        Common::Send("");
    }

    public static function GetBonus_1009($json){
        $acc = $json->{'acc'};
        $bonus = $json->{'bonusList'};

        try{
            $items = null;
            $datas = explode(",", $bonus);

            if (count($datas) == 1 && $bonus == -1){
                Common::Send('');
                return;
            }

            foreach ($datas as $bonusId) {
                if ($bonusId < 0)
                    continue;

                $config = ConfigClass::ReadConfig('Bonus');
                $items = Common::GetItemsByBonusIDandCount($items, $config[$bonusId]['Bonus1'], $config[$bonusId]['BonusCount1']);
                $items = Common::GetItemsByBonusIDandCount($items, $config[$bonusId]['Bonus2'], $config[$bonusId]['BonusCount2']);
                $items = Common::GetItemsByBonusIDandCount($items, $config[$bonusId]['Bonus3'], $config[$bonusId]['BonusCount3']);
                $items = Common::GetItemsByBonusIDandCount($items, $config[$bonusId]['Bonus4'], $config[$bonusId]['BonusCount4']);
                $items = Common::GetItemsByBonusIDandCount($items, $config[$bonusId]['Bonus5'], $config[$bonusId]['BonusCount5']);
                $items = Common::GetItemsByBonusIDandCount($items, $config[$bonusId]['Bonus6'], $config[$bonusId]['BonusCount6']);
            }
    
            if (is_null($items))
                return;
    
            foreach (array_keys($items) as $key){
                if ($key == 9000) ShopClass::AddCoin($acc, 'coin1', $items[$key]);
                else if ($key == 9001) ShopClass::AddCoin($acc, 'coin2', $items[$key]);
                else if ($key == 9002) ShopClass::AddCoin($acc, 'coin3', $items[$key]);
                else ItemClass::AddItem($acc, $key, $items[$key]);
            }

            Common::Send('');
        } catch (Exception $e){
            LoggerClass::E()->error($e->getMessage(), "\n");
            LoggerClass::E()->error($e);
            Common::error(999);
        }
    }

    public static function Buy_1010($json){
        $acc = $json->{'acc'};
        $shopId = $json->{'shopId'};

        try {
            $config = ConfigClass::ReadConfig('Shop')[$shopId];
            $result = MySqlPDB::$pdo->query("SELECT * FROM userdata WHERE acc='". $acc. "'")->fetch(PDO::FETCH_ASSOC);
            $resultItemCoin = MySqlPDB::$pdo->query(
                "SELECT ifnull(`count`, 0) coinCount FROM items WHERE acc='$acc' AND itemId='".($config['CostItemID'])."' AND isDiscard=0")->fetch(PDO::FETCH_ASSOC);

            // 持っているコインをチェック
            $coinCount = 0;
            switch  ($config['CostItemID']){
                case 9000:  $coinCount = $result['coin1']; break;
                case 9001:  $coinCount = $result['coin2']; break;
                case 9002:  $coinCount = $result['coin3']; break;
                case 9004:  $coinCount = $resultItemCoin['coinCount']; break;
                default: return;
            }
            if ($coinCount < $config['CostCount']){
                switch  ($config['CostItemID']){
                    case 9000:  Common::error(1010001); break;
                    case 9001:  Common::error(1010002); break;
                    case 9002:  Common::error(1010003); break;
                    case 9004:  Common::error(1010004); break;
                    default: LoggerClass::E()->error("[FailureCMD]1010001:".$acc.",".$shopId); return;
                }
                return;
            }

            // 制限回数チェック
            if ($config['CostItemID'] == 9004 && $config['LimitedCount'] != -1) {
                $shopLimitedCount = ShopLimitedCountClass::GetShopLimitedCount($acc, $shopId)['limitedCount'];
                if($shopLimitedCount >= $config['LimitedCount']) {
                    Common::error(1010005);
                    return;
                }
            }

            // アイテム追加
            if ($config['Type'] == 5){ // ショップで買った設計図
                $blueprintId = $config['Relation'];
                $blueprintConfig = ConfigClass::ReadConfig('Blueprint')[$blueprintId];
                ItemClass::AddItemInData2($acc, 3002, 1, $config['Name'], $blueprintConfig['Data']);
            } else {
                $items = Common::GetItemsByBonus($config['Bonus']);
                foreach (array_keys($items) as $key) {
                    ItemClass::AddItems4($acc, $key, $items[$key]);
                }
            }

            // コイン消耗
            switch  ($config['CostItemID']){
                case 9000: ShopClass::CostCoin($acc, 'coin1', $config['CostCount']); break;
                case 9001: ShopClass::CostCoin($acc, 'coin2', $config['CostCount']); break;
                case 9002: ShopClass::CostCoin($acc, 'coin3', $config['CostCount']); break;
                case 9004: ItemClass::RemoveItemByItemId($acc, $config['CostItemID'], $config['CostCount']); break;
            }

            // 制限回数更新
            if ($config['CostItemID'] == 9004 && $config['LimitedCount'] != -1) {
                ShopLimitedCountClass::AddLimitedCount($acc, $shopId);
            }
            Common::Send("");
        }catch (PDOException $e) {
			LoggerClass::E()->error($e);
            Common::error(3306);
		}
    }
}
