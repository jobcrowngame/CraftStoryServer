<?php

class FriendClass{
    public static function GetFriendDataByAcc($acc){
        $sql = "SELECT * FROM friend WHERE acc='$acc'";
        $result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        if (empty($result)){
            $sql = "INSERT INTO friend (acc) VALUES ('$acc')";
            MySqlPDB::$pdo->query($sql);
        }

        $sql = "SELECT * FROM friend WHERE acc='$acc'";
        return MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public static function GetFriendDataById($id){
        $user = UserClass::GetUserDataById($id);
        $acc = $user['acc'];

        $sql = "SELECT * FROM friend WHERE acc='$acc'";
        $result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        if (empty($result)){
            $sql = "INSERT INTO friend (acc) VALUES ('$acc')";
            MySqlPDB::$pdo->query($sql);
        }

        $sql = "SELECT * FROM friend WHERE acc='$acc'";
        return MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }
}