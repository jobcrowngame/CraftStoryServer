<?php

class CMD1031_1040{
    public static function DeleteItem_1031($json){
        $acc = $json->{'acc'};
        $guid = $json->{'guid'};

        ItemClass::DeleteItemByGuid($guid);

        Common::Send("");
    }

    public static function DeleteItemList_1032($json){
        $acc = $json->{'acc'};
        $items = $json->{'items'};

        $arr = json_decode($items,true);
        foreach ($arr as $row){
            $guid = $row['guid'];
            ItemClass::DeleteItemByGuid($guid);
        }
        
        Common::Send("");
    }

    public static function Follow_1033($json){
        $acc = $json->{'acc'};
        $targetId = $json->{'guid'};

        $userId = UserClass::GetUserDataByAcc($acc)['id'];

        $xml = @simplexml_load_file('setting.xml');
        $maxfollow = $xml->setting[0]->MaxFriendFollow;
        $maxfollower = $xml->setting[0]->MaxFriendFollower;

        // フォローを更新
        $friend = FriendClass::GetFriendDataByAcc($acc);
        $follow = $friend['follow'];
        if (empty($follow)){
            $newFollow = $targetId;
        }
        else{
            // 数チェック
            $arr = explode(",", $follow);
            if (count($arr) >= $maxfollow){
                Common::error(1033003);
                return;
            }

            // 重複チェック
            foreach ($arr as $row){
                if ($row == $targetId){
                    Common::error(1033001);
                    return;
                }
            }

            // データー追記
            $newFollow = $follow.",".$targetId;
        }
        $sql = "UPDATE friend SET follow='$newFollow' WHERE acc='$acc';";

        // ターゲットユーザーのフォロワーを更新
        $friend = FriendClass::GetFriendDataById($targetId);
        $friendAcc= $friend['acc'];
        $follower = $friend['follower'];
        if (empty($follower)){
            $newFollower = $userId;
        }
        else{
            // 数チェック
            $arr = explode(",", $follower);
            if (count($arr) >= $maxfollower){
                Common::error(1033004);
                return;
            }

            // 重複チェック
            foreach ($arr as $row){
                if ($row == $userId){
                    Common::error(1033002);
                    return;
                }
            }

            // データー追記
            $newFollower = $follower.",".$targetId;
        }
        $sql = $sql."UPDATE friend SET follower='$newFollower' WHERE acc='$friendAcc';";

        MySqlPDB::$pdo->exec($sql);
        Common::Send("");
    }

    public static function DeFollow_1034($json){
        $acc = $json->{'acc'};
        $targetId = $json->{'guid'};

        $userId = UserClass::GetUserDataByAcc($acc)['id'];

        // フォローを更新
        $follow = FriendClass::GetFriendDataByAcc($acc)['follow'];
        $arr = explode(",", $follow);
        if (count($arr) > 1){
            foreach ($arr as $row){
                if ($row == $targetId){
                    continue;
                }

                if (empty($newFollow)){
                    $newFollow = $row;
                }else{
                    $newFollow = $newFollow.",".$row;
                }
            }
        }else{
            $newFollow = null;
        }
        $sql = "UPDATE friend SET follow='$newFollow' WHERE acc='$acc';";


        // ターゲットユーザーのフォロワーを更新
        $friend = FriendClass::GetFriendDataById($targetId);
        $friendAcc= $friend['acc'];
        $follower = $friend['follower'];
        $arr = explode(",", $follower);
        if (count($arr) > 1){
            foreach ($arr as $row){
                if ($row == $userId){
                    continue;
                }

                if (empty($newFollower)){
                    $newFollower = $row;
                }else{
                    $newFollower = $newFollower.",".$row;
                }
            }
        }else{
            $newFollower = null;
        }
        $sql = $sql."UPDATE friend SET follower='$newFollower' WHERE acc='$friendAcc';";

        MySqlPDB::$pdo->exec($sql);
        Common::Send("");
    }

    // フォローデーターをもらう
    public static function ReadFollow_1035($json){
        $acc = $json->{'acc'};

        $follow = FriendClass::GetFriendDataByAcc($acc)['follow'];
        if (empty($follow)){
            Common::Send("");
            return;
        }

        $arr = explode(",", $follow);
        foreach ($arr as $row){
            $user = UserClass::GetUserDataById($row);
            $nickname = $user['nickname'];
            $comment = $user['comment'];
            $loginTime = $user['updated_at'];

            $result[]=array(
                'guid'=>$row,
                'nickname'=>$nickname,
                'comment'=>$comment,
                'loginTime'=> $loginTime,
            );
        }

        Common::Send($result);
    }

    public static function ReadFollower_1036($json){
        $acc = $json->{'acc'};

        $follower = FriendClass::GetFriendDataByAcc($acc)['follower'];
        if (empty($follower)){
            Common::Send("");
            return;
        }

        $arr = explode(",", $follower);
        foreach ($arr as $row){
            $user = UserClass::GetUserDataById($row);
            $nickname = $user['nickname'];
            $comment = $user['comment'];
            $loginTime = $user['updated_at'];

            $result[]=array(
                'guid'=>$row,
                'nickname'=>$nickname,
                'comment'=>$comment,
                'loginTime'=> $loginTime,
            );
        }

        Common::Send($result);
    }

    public static function UpdateComment_1037($json){
		$acc = $json->{'acc'};
		$comment = $json->{'comment'};

		$sql = "UPDATE userdata SET comment = '$comment' WHERE acc ='$acc'";
		MySqlPDB::$pdo->query($sql);

		Common::Send("");
	}

    public static function SearchFriend_1038($json){
		$userAcc = $json->{'userAcc'};

        $result = UserClass::GetUserDataByAcc($userAcc);
        if(empty($result)){
            Common::error(1038001);
            return;
        }

        $guid = $result['id'];
        $nickname = $result['nickname'];
        $comment = $result['comment'];
        $loginTime = $result['updated_at'];

        Common::Send(array(
            'guid'=>$guid,
            'nickname'=>$nickname,
            'comment'=>$comment,
            'loginTime'=> $loginTime,
        ));
    }

    public static function GetBlueprintPreviewData_1039($json){
        $myshopId = $json->{'myshopId'};

        $sql = "SELECT data FROM myshop WHERE myshopid=$myshopId";
        $result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        if (empty($result)){
            Common::error(1039001);
            return;
        }

        Common::Send(array(
            'data'=>$result['data'],
        ));
    }

    public static function GetFriendHomeData_1040($json){
        $userGuid = $json->{'userGuid'};

        $sql = "SELECT homedata FROM homedata
                INNER JOIN userdata ON homedata.acc = userdata.acc
			    WHERE userdata.id=$userGuid";
        $result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        Common::Send(array(
            'homedata'=>$result['homedata'],
        ));
    }
}