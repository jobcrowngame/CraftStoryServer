<?php

class ShopLimitedCountClass{
    public static function AddLimitedCount($acc, $shopId){
        ShopLimitedCountClass::CheckTable($acc, $shopId);

        $sql = "UPDATE shop_limitedcount SET limitedCount=limitedCount + 1 WHERE acc='$acc' AND shopId='$shopId'";
        MySqlPDB::$pdo->query($sql);
    }

    public static function CheckTable($acc, $shopId){
        $sql = "SELECT count(*) FROM shop_limitedcount WHERE acc='$acc' AND shopId='$shopId'";
        $result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        if ($result['count(*)'] == 0){
            $sql = "INSERT INTO shop_limitedcount (acc, shopId, limitedCount) VALUES ('$acc', '$shopId', 0)";
            MySqlPDB::$pdo->query($sql);
        }
    }
    
    public static function GetAllShopLimitedCounts($acc){
        $sql = "SELECT shopId, limitedCount FROM shop_limitedcount WHERE acc='$acc'";
        return MySqlPDB::$pdo->query($sql);
    }
    
    public static function GetShopLimitedCount($acc, $shopId){
        $sql = "SELECT shopId, limitedCount FROM shop_limitedcount WHERE acc='$acc' AND shopId='$shopId'";
        return MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }
}