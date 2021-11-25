<?php

class NoticeClass {
    public static function Add($post)
    {
        $category = $post['category'];
        $newflag = $post['newflag'];
        $activedate = $post['activedate'];
        $priority = $post['priority'];
        if(!$priority) $priority = 'null';
        $pickup = $post['pickup'];
        if(!$pickup) $pickup = 'null';
        $title = $post['title'];
        $titleIcon = $post['titleIcon'];
        $detailIcon = $post['detailIcon'];
        $url = $post['url'];
        $text = $post['text'];
        $inputkey = $post["key"];
        if (PrivateKey::CheckKey($inputkey) == 1){
            return 1;
        }

        $sql = "INSERT INTO notice (category,newflag,activedate,priority,pickup,title,titleIcon,detailIcon,url,text) 
            VALUES ($category,$newflag,'$activedate',$priority,$pickup,'$title','$titleIcon','$detailIcon','$url','$text')";
        MySqlPDB::$pdo->query($sql);
    }

    public static function GetList(){
        $sql = "SELECT * FROM notice";
        return MySqlPDB::$pdo->query($sql);
    }

    public static function Get($id){
        $id = $id['id'];
        $sql = "SELECT * FROM notice WHERE id='$id'";
        return MySqlPDB::$pdo->query($sql);
    }

    public static function Edit($post){
        $id = $post['id'];
        $category = $post['category'];
        $newflag = $post['newflag'];
        $activedate = $post['activedate'];
        $priority = $post['priority'];
        if(!$priority) $priority = 'null';
        $pickup = $post['pickup'];
        if(!$pickup) $pickup = 'null';
        $title = $post['title'];
        $titleIcon = $post['titleIcon'];
        $detailIcon = $post['detailIcon'];
        $url = $post['url'];
        $text = $post['text'];
        $type = $post['type'];
        $inputkey = $post["key"];
        if (PrivateKey::CheckKey($inputkey) == 1){
            return 1;
        }

        if  ($type == 1){
            NoticeClass::Update($post);
        }else{
            NoticeClass::Delete($post);
        }
    }

    public static function Update($post){
        $id = $post['id'];
        $category = $post['category'];
        $newflag = $post['newflag'];
        $activedate = $post['activedate'];
        $priority = $post['priority'];
        if(!$priority) $priority = 'null';
        $pickup = $post['pickup'];
        if(!$pickup) $pickup = 'null';
        $title = $post['title'];
        $titleIcon = $post['titleIcon'];
        $detailIcon = $post['detailIcon'];
        $url = $post['url'];
        $text = $post['text'];

        $sql = "UPDATE notice SET category=$category,newflag=$newflag,activedate='$activedate',priority=$priority,pickup=$pickup,title='$title',
            titleIcon='$titleIcon',detailIcon='$detailIcon',url='$url',text='$text' 
            WHERE id=$id";
        MySqlPDB::$pdo->query($sql);
    }

    public static function Delete($post){
        $id = $post['id'];
        $inputkey = $post["key"];
        if (PrivateKey::CheckKey($inputkey) == 1){
            return 1;
        }

        $sql = "DELETE FROM notice WHERE id=$id";
        MySqlPDB::$pdo->query($sql);
    }
}