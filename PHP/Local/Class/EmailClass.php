<?php

class EmailClass{
    public static function AddEmail($acc, $title, $msg){
        $sql = "INSERT INTO email (acc, title, message) 
            VALUES ('$acc', '$title','$msg')";
        MySqlPDB::$pdo->query($sql);
    }

    public static function AddEmailInItem($acc, $mailId, $repl = null){
        $config = ConfigClass::ReadConfig('Mail');
        $title = $config[$mailId]['Title'];
        $msg = $config[$mailId]['Msg'];
        $relatedData = $config[$mailId]['Data'];
        if($relatedData=="N") $relatedData = null;

        if($repl) {
            for($i = 0; $i < count($repl); $i++){
                $msg = str_replace("%$i%", $repl[$i], $msg);   
            }
        }

        $sql ="SELECT email_id FROM email WHERE isDiscard=1";
        $result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
        if(empty($result)){
            $sql = "INSERT INTO email (acc, title, message, related_data) 
                VALUES ('$acc', '$title','$msg', '$relatedData')";
        }else{
            $id = $result['email_id'];
            $sql = "UPDATE email SET 
                acc='$acc',
                title='$title',
                message='$msg',
                related_data='$relatedData',
                created_at=CURRENT_TIMESTAMP,
                is_already_read=0,
                is_already_received=0,
                isDiscard=0
                WHERE email_id=$id";
        }
        
        MySqlPDB::$pdo->query($sql);
    }

    // いいねによってまらったポイントのメッセージを送る
    public static function AddFromGoodPointMail($acc){
        $sql = "SELECT from_good_point,from_gooded_point FROM limited WHERE acc='$acc'";
        $result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        $from_good_point = $result['from_good_point'];
        $from_gooded_point = $result['from_gooded_point'];

        // もらったポイントがないとスキップ
        if($from_good_point == 0 && $from_gooded_point == 0)
            return;

        $emailTitle = "いいねによるポイント獲得案内";
        $emailMessage = "「いいね」をしたことで、累計[".$from_good_point."]ポイント獲得しました！
「いいね」をされたことで、累計[".$from_gooded_point."]ポイント獲得しました！

「いいね」の回数は毎日0時にリセットされるので、またいいねをしてみてね！";
        EmailClass::AddEmail($acc,$emailTitle, $emailMessage);

        $sql = "UPDATE limited SET from_good_point = 0, from_gooded_point = 0 WHERE acc = '$acc'";
        MySqlPDB::$pdo->query($sql);
    }
}