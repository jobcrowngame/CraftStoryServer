<?php

class MySqlPDB{
	public static $pdo;
	//PDO MySQL接続
	public static function connectDB(){

		try {
			$xml = @simplexml_load_file('setting.xml');
			
			//ユーザ名やDBアドレスの定義
			$dsn = $xml->setting[0]->DBDNS;
			$username = $xml->setting[0]->DBUser;
			$password = $xml->setting[0]->DBPW;

			try {
				MySqlPDB::$pdo = new PDO($dsn, $username, $password);
				MySqlPDB::$pdo -> query("SET NAMES utf8");
				MySqlPDB::$pdo -> setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
			} catch (PDOException $e) {
				exit('' . $e->getMessage());
			}

			return MySqlPDB::$pdo;
		} catch (PDOException $e){
			var_dump($e->getMessage());
		}
	}
}