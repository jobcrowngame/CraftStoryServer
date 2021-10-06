<?php

class EmailClass{
    public static function AddEmail($acc, $title, $msg){
        $sql = "INSERT INTO email (acc, title, message) 
            VALUES ('$acc', '$title','$msg')";
        MySqlPDB::$pdo->query($sql);
    }

    public static function AddEmailInItem($acc, $mailId){
        $config = ConfigClass::ReadConfig('Mail');
        $title = $config[$mailId]['Title'];
        $msg = $config[$mailId]['Msg'];
        $relatedData = $config[$mailId]['Data'];

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
}