<?php

class StatisticsClass{
    public static function AddGacha($acc){
        StatisticsClass::CheckTable($acc);

        $sql = "UPDATE statistics_user SET gacha=gacha + 1 WHERE acc='$acc'";
        MySqlPDB::$pdo->query($sql);
    }

    public static function CheckTable($acc){
        $sql = "SELECT count(*) FROM statistics_user WHERE acc='$acc'";
        $result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        if ($result['count(*)'] == 0){
            $sql = "INSERT INTO statistics_user (acc) VALUES ('$acc')";
            MySqlPDB::$pdo->query($sql);
        }
    }
}