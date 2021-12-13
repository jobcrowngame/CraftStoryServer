<?php

class CMD1061_1070{
    public static function GetMaxBraveLevel_1061($json){
        $acc = $json->{'acc'};

        $sql = "SELECT maxArrivedFloor FROM statistics_user WHERE acc = '$acc'";
        $result = MySqlPDB::$pdo->query($sql)->fetch();

        if (empty($result['maxArrivedFloor'])){
            $result['maxArrivedFloor'] = 0;
        }
        
        Common::Send(array(
            'maxArrivedFloor'=>$result['maxArrivedFloor'],
        ));
    }

    // ログインボーナス情報をゲット
    public static function GetLoginBonusInfo_1062($json){
        $acc = $json->{'acc'};

        $sql = "SELECT * FROM limited WHERE acc='$acc'";
        $result = MySqlPDB::$pdo->query($sql)->fetch();

        $loginBonus = $result['loginBonus'];
        $loginBonusStep = $result['loginBonusStep'];

        $arr = array();
        $sql = "SELECT * FROM loginbonus WHERE active = 1 AND start_at < NOW() AND end_at > NOW()";
        $result = MySqlPDB::$pdo->query($sql);

        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            $arr[]=array(
                'id'=>$row['id'],
                'type'=>$row['type'],
                'themeTexture'=>$row['themeTexture'],
                'items'=>$row['items'],
                'itemCounts'=>$row['itemCounts'],
                'start_at'=>$row['start_at'],
                'end_at'=>$row['end_at'],
            );
        }

        Common::Send(array(
            'loginBonus'=>$loginBonus,
            'loginBonusStep'=>$loginBonusStep,
            'arr'=>$arr,
        ));
    }

    public static function GEtLoginBonus_1063($json){
        $acc = $json->{'acc'};
        $id = $json->{'id'};
        $step = $json->{'step'};

        $sql = "SELECT * FROM limited WHERE acc='$acc'";
        $result = MySqlPDB::$pdo->query($sql)->fetch();

        // ログインボーナス記録
        $loginBonus = explode(",", $result['loginBonus']);
        $loginBonusStep = explode(",", $result['loginBonusStep']);

        if (empty($result['loginBonus'])){
            $loginBonus = $id;
            $loginBonusStep = $step + 1;
        }else{
            if(in_array($id, $loginBonus)){
                for ($i = 0; $i < count($loginBonus); $i++){
                    if ($loginBonus[$i] == $id){
                        $loginBonusStep[$i] = $step + 1;
                        break;
                    }
                }
            }else{
                array_push($loginBonus,$id);
                array_push($loginBonusStep,$step + 1);
            }

            $loginBonus = Common::ArrayToString($loginBonus, ",");
            $loginBonusStep = Common::ArrayToString($loginBonusStep, ",");
        }

        $sql = "UPDATE limited SET loginBonus='$loginBonus',loginBonusStep='$loginBonusStep' WHERE acc = '$acc'";
        MySqlPDB::$pdo->query($sql);

        // ボーナスを与える
        $sql = "SELECT * FROM loginbonus WHERE active = 1 AND start_at < NOW() AND end_at > NOW()";
        $result = MySqlPDB::$pdo->query($sql);

        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            if ($row['id'] == $id){
                $itemId = explode(",", $row['items']);
                $count = explode(",", $row['itemCounts']);
                ItemClass::AddItems4($acc, $itemId[$step], $count[$step]);
            }
        }

        Common::Send("");
    }

    public static function GetTotalUploadBlueprintCount_1064($json){
        $acc = $json->{'acc'};
        $result = StatisticsClass::GetTotalUploadBlueprintCount($acc);
        $totalUploadBlueprintCount = empty($result['totalUploadBlueprintCount']) ? 0 : $result['totalUploadBlueprintCount'];
        Common::Send("$totalUploadBlueprintCount");
    }
}