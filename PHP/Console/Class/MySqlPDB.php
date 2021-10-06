<?php

class MySqlPDB{
	public static $pdo;
	//PDO MySQL接続
	public function connectDB(){

		try {
			$xml = @simplexml_load_file('setting.xml');
			
			//ユーザ名やDBアドレスの定義
			$dsn = $xml->setting[0]->DBDNS;
			$username = $xml->setting[0]->DBUser;
			$password = $xml->setting[0]->DBPW;

			try {
				$this::$pdo = new PDO($dsn, $username, $password);
				$this::$pdo -> query("SET NAMES utf8");
			} catch (PDOException $e) {
				exit('' . $e->getMessage());
			}

			return $this::$pdo;
		} catch (PDOException $e){
			var_dump($e->getMessage());
		}
	}
}