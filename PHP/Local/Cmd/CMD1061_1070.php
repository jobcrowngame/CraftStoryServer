<?php

class CMD1061_1070{
    public static function GetMaxBraveLevel_1061($json){
        $acc = $json->{'acc'};

        $sql = "SELECT maxArrivedFloor FROM statistics_user WHERE acc = '$acc'";
        $result = MySqlPDB::$pdo->query($sql)->fetch();

        Common::Send(array(
            'maxArrivedFloor'=>$result['maxArrivedFloor'],
        ));
    }
}