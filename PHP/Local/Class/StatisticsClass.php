<?php

class StatisticsClass{
    public static function AddGacha($acc, $gachaGroup){
        StatisticsClass::CheckTable($acc, $gachaGroup);

        $sql = "UPDATE statistics_user SET gacha=gacha + 1 WHERE acc='$acc' AND gachaGroup='$gachaGroup'";
        MySqlPDB::$pdo->query($sql);
    }

    public static function CheckTable($acc, $gachaGroup){
        $sql = "SELECT count(*) FROM statistics_user WHERE acc='$acc' AND gachaGroup='$gachaGroup'";
        $result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        if ($result['count(*)'] == 0){
            $sql = "INSERT INTO statistics_user (acc, gachaGroup) VALUES ('$acc', '$gachaGroup')";
            MySqlPDB::$pdo->query($sql);
        }
    }
    
    public static function GetGacha($acc, $gachaGroup){
        $sql = "SELECT gacha FROM statistics_user WHERE acc='$acc' AND gachaGroup='$gachaGroup'";
        return MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }
}