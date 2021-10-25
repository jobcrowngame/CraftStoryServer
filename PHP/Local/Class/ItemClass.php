<?php

class ItemClass{
    public static function AddItems2($acc, $itemIds, $itemCounts){
        for ($i = 0; $i < count($itemIds); $i++){
            $id = $itemIds[$i];
            $count = $itemCounts[$i];

            switch ($id){
                case 9000: ShopClass::AddCoin($acc,'coin1',$count); break;
                case 9001: ShopClass::AddCoin($acc,'coin2',$count); break;
                case 9002: ShopClass::AddCoin($acc,'coin3',$count); break;
                default: ItemClass::AddItem($acc, $id, $count); break;
            }
        }
    }
    
    public static function AddItem3($acc, $itemId, $count, $site) {
        $sql = "INSERT INTO items (acc, itemId, count, equipSite) VALUES ('$acc',$itemId,$count,$site)";
        MySqlPDB::$pdo->query($sql);
    }

    public static function AddItem($acc, $itemId, $count) {
        $maxCount = ConfigClass::ReadConfig('Item')[$itemId]['MaxCount'];
    
        try{
            while($count > $maxCount){
                $sql = "INSERT INTO items (acc, itemId, count) VALUES ('$acc',$itemId,$maxCount)";
                MySqlPDB::$pdo->query($sql);
                $count-=$maxCount;
            }
    
            $que = MySqlPDB::$pdo->query("SELECT * FROM items WHERE acc='$acc' && isDiscard=0 && itemId=$itemId");
            foreach ($que as $row) {
                if ($row['count'] == $maxCount)
                    continue;
    
                if ($count < $maxCount - $row['count']){
                    $addCount = $row['count'] + $count;
                    $sql = "UPDATE items SET count=$addCount WHERE id=".$row['id']."";
                    MySqlPDB::$pdo->query($sql);
                    $count = 0;
                    break;
                }
                else {
                    $addCount = $maxCount - $row['count'];
                    $count -= $addCount;
                    $sql = "UPDATE items SET count=$maxCount WHERE acc='$acc' && isDiscard=0 && itemId=$itemId";
                    MySqlPDB::$pdo->query($sql);
                }
            }
    
            if ($count > 0){
                $sumCount = MySqlPDB::$pdo->query("SELECT Count(*) AS 'num' FROM items WHERE isDiscard=1")->fetch();
                if ($sumCount['num'] > 0) {
                    $discardItems = MySqlPDB::$pdo->query("SELECT * FROM items WHERE isDiscard=1");
                    foreach ($discardItems as $row) {
                        MySqlPDB::$pdo->query("UPDATE items 
                            SET 
                                isDiscard = 0,
                                acc = '$acc',
                                itemId = $itemId,
                                count = $count,
                                equipSite = 0,
                                newName = null, 
                                islocked = 0,
                                relationData = ''
                            WHERE
                                id = ". $row['id']);
                        break;
                    }
                }
                else{
                    $sql = "INSERT INTO items (acc, itemId, count) VALUES ('$acc',$itemId,$count)";
                    MySqlPDB::$pdo->query($sql);
                }
            }
        } catch (PDOException $e) {
            Common::error($e);
        }
    }
   
    public static function AddItemInData2($acc, $itemId, $count, $newName, $rdata){
        try{
            $sql = "INSERT INTO items (acc, itemId, newName, count, relationData) 
                VALUES ('". $acc. "', ". $itemId. ",'". $newName. "', 1,'". $rdata. "')";
            MySqlPDB::$pdo->query($sql);
        } catch (PDOException $e) {
            LoggerClass::E()->error($e);
        }
    }

    public static function AddLockItemInData($acc, $itemId, $count, $newName, $rdata){
        try{
            $sql = "INSERT INTO items (acc, itemId, newName, count, relationData, islocked) 
                VALUES ('". $acc. "', ". $itemId. ",'". $newName. "', 1,'". $rdata. "', 1)";
            MySqlPDB::$pdo->query($sql);
        } catch (PDOException $e) {
            LoggerClass::E()->error($e);
        }
    }
    
    public static function RemoveItemByItemId($acc, $itemId, $count){
        try{
            $sumCount = MySqlPDB::$pdo->query("SELECT SUM(count) AS 'num' FROM items WHERE isDiscard=0 && acc='". $acc. "' && itemId=". $itemId. "")->fetch();
            if($sumCount['num'] < $count){
                return Common::error(1003001);
            }
    
            $que = MySqlPDB::$pdo->query("SELECT * FROM items WHERE isDiscard=0 && acc='". $acc. "' && itemId=". $itemId. "");
            foreach ($que as $row) {
                if ($count < $row['count']){
                    $newCount = $row['count'] - $count;
                    MySqlPDB::$pdo->query("UPDATE items SET count = '". $newCount . "' WHERE id = ". $row['id']. "");
                    $count = 0;
                }else if ($count == $row['count']){
                    MySqlPDB::$pdo->query("UPDATE items SET isDiscard = '1' WHERE id = ". $row['id']. "");
                    $count = 0;
                }else{
                    MySqlPDB::$pdo->query("UPDATE items SET isDiscard = '1' WHERE id = ". $row['id']. "");
                    $count -= $row['count'];
                }
            }
        } catch (PDOException $e) {
            Common::error($e);
        }
    }
    
    // アイテムを消耗
    public static function CostItem($acc, $guid, $count){
        $sql = "SELECT * FROM items WHERE isDiscard=0 && id=$guid";
        $result = MySqlPDB::$pdo->query($sql)->fetch();

        if (empty($result)){
            return 2;
        }

        // 持っているアイテムの数が足りない場合エラーを出す
        if($result['count'] < $count){
            return Common::error(1002001);
        }

        // 変化後の数
        $newCount = $result['count'] - $count;

        if ($newCount == 0){
            // このRowを破棄
            $sql = "UPDATE items SET isDiscard = 1 WHERE id = $guid";
        }else {
            // アイテム数を更新
            $sql = "UPDATE items SET count = $newCount WHERE id = $guid";
        }
        MySqlPDB::$pdo->query($sql);

        return 1;
    }

    // アイテムを削除
    public static function DeleteItemByGuid($guid){
        $sql = "UPDATE items SET isDiscard = 1 WHERE id = $guid";
        MySqlPDB::$pdo->query($sql);
    }

    // 装備データ追加
    public static function AddEquipment($itemGuid, $skills){
        $sql = "SELECT id FROM equipment WHERE isDiscard = 1";
        $result = MySqlPDB::$pdo->query($sql)->fetch();

        print_r($result);

        if  (empty($result)){
            $sql = "INSERT INTO equipment (item_guid, skills) 
                VALUES ($itemGuid, '$skills')";
        }else{
            $id = $result['id'];

            $sql = "UPDATE equipment 
                SET item_guid=$itemGuid,
                    skills=$skills,
                    isDiscard=0
                WHERE id=$id";
        }

        MySqlPDB::$pdo->query($sql);
    }
}