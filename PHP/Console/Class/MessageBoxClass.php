<?php

class MessageBoxClass {
    public static function Send($post){
        $title = $post["title"];
        $text = $post["text"];
        $userOption = $post["userOption"];
        $userId = $post["userId"];
        $itemOption = $post["itemOption"];
        $items = $post["items"];
        $itemCount = $post["itemCount"];
        $inputkey = $post["key"];
        if (PrivateKey::CheckKey($inputkey) == 1){
            return 1;
        }

        if ($userOption == 1){
            if ($itemOption == 1){
                $sql = "SELECT acc FROM userdata";
                $result = MySqlPDB::$pdo->query($sql);

                $row = $result->fetch(PDO::FETCH_ASSOC);
                $sql = "INSERT INTO email (acc,title,message) VALUES ('".$row['acc']."','$title','$text')";

                while($row = $result->fetch(PDO::FETCH_ASSOC)){
                    $userId = $row['acc'];
                    $sql =$sql.",('$userId','$title','$text')";
                }
                MySqlPDB::$pdo->query($sql);
            }else{
                $sql = "SELECT acc FROM userdata";
                $result = MySqlPDB::$pdo->query($sql);

                $related_data = $items."^".$itemCount;
                $row = $result->fetch(PDO::FETCH_ASSOC);
                $sql = "INSERT INTO email (acc,title,message,related_data) VALUES ('".$row['acc']."','$title','$text','$related_data')";

                while($row = $result->fetch(PDO::FETCH_ASSOC)){
                    $userId = $row['acc'];
                    $sql =$sql.",('$userId','$title','$text','$related_data')";
                }
                MySqlPDB::$pdo->query($sql);
            }
        }else{
            if ($itemOption == 1){
                $sql = "INSERT INTO email (acc,title,message) 
                    VALUES ('$userId','$title','$text')";
                MySqlPDB::$pdo->query($sql);
            }else{
                $related_data = $items."^".$itemCount;
                $sql = "INSERT INTO email (acc,title,message,related_data) 
                    VALUES ('$userId','$title','$text','$related_data')";
                MySqlPDB::$pdo->query($sql);
            }
        }
    }
}