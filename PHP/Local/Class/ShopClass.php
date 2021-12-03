<?php

class ShopClass{
    public static function AddCoin($acc, $coin, $count){
        if  ($coin == 'coin3'){
            $sql = "UPDATE userdata SET $coin = $coin + $count,totalPoint=totalPoint+$count WHERE acc='$acc'";
        }else{
            $sql = "UPDATE userdata SET $coin = $coin + $count WHERE acc='$acc'";
        }

        MySqlPDB::$pdo->query($sql);
    }

    public static function CostCoin($acc, $coin, $count){
        $sql = "SELECT ".$coin." FROM userdata WHERE acc='".$acc."'";
        $result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        if ($result[$coin] < $count)
            return 1;

        $newCount = $result[$coin] - $count;
        $sql = "UPDATE userdata SET ".$coin." = ".$newCount." WHERE acc='".$acc."'";
        MySqlPDB::$pdo->query($sql);
        return 0;
    }

    public static function GetShopIdByType($type){
        switch  ($type){
            case 1: return 80;
            case 2: return 81;
            case 3: return 82;
        }
    }
}