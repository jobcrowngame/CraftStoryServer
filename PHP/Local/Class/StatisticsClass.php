<?php

class StatisticsClass{
    public static function AddGacha($acc, $gachaGroup){
        StatisticsClass::CheckUserGachaTable($acc, $gachaGroup);

        $sql = "UPDATE statistics_user_gacha SET gacha=gacha + 1 WHERE acc='$acc' AND gachaGroup='$gachaGroup'";
        MySqlPDB::$pdo->query($sql);
    }

    public static function CheckUserGachaTable($acc, $gachaGroup){
        $sql = "SELECT count(*) FROM statistics_user_gacha WHERE acc='$acc' AND gachaGroup='$gachaGroup'";
        $result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        if ($result['count(*)'] == 0){
            $sql = "INSERT INTO statistics_user_gacha (acc, gachaGroup) VALUES ('$acc', '$gachaGroup')";
            MySqlPDB::$pdo->query($sql);
        }
    }
    
    public static function GetGacha($acc, $gachaGroup){
        $sql = "SELECT gacha FROM statistics_user_gacha WHERE acc='$acc' AND gachaGroup='$gachaGroup'";
        return MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }
    
    public static function UpdateMaxArrivedFloor($acc, $arrivedFloor){
        StatisticsClass::CheckStatisticsTable($acc);
        $sql = "UPDATE statistics_user SET maxArrivedFloor = $arrivedFloor WHERE acc='$acc' AND maxArrivedFloor <= $arrivedFloor";
        MySqlPDB::$pdo->query($sql);
    }
    
    public static function UpdateLastFloorCount($acc, $arrivedFloor){
        $lastFloor = @simplexml_load_file('setting.xml')->setting[0]->LastFloor;
        if ($lastFloor <= $arrivedFloor){
            StatisticsClass::CheckStatisticsTable($acc);
            $sql = "UPDATE statistics_user SET lastFloorCount=lastFloorCount+1 WHERE acc='$acc'";
            MySqlPDB::$pdo->query($sql);
        }
    }

    public static function CheckStatisticsTable($acc){
        $sql = "SELECT count(*) FROM statistics_user WHERE acc='$acc'";
        $result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        if ($result['count(*)'] == 0){
            $sql = "INSERT INTO statistics_user (acc) VALUES ('$acc')";
            MySqlPDB::$pdo->query($sql);
        }
    }
    
    private static function GetLastFloor(){
        $config = ConfigClass::ReadConfig('Map');
    }

    public static function GetTotalSetBlockCount($acc) {
        $sql = "SELECT totalSetBlockCount FROM statistics_user WHERE acc='$acc'";
        return MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }    

    public static function AddTotalSetBlockCount($acc){
        StatisticsClass::CheckStatisticsTable($acc);
        $sql = "UPDATE statistics_user SET totalSetBlockCount=totalSetBlockCount + 1 WHERE acc='$acc'";
        MySqlPDB::$pdo->query($sql);
    }
}