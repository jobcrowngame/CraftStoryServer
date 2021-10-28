<?php

class CMD0_1000{
    public static function GetVersion_99($IsMaintenance){
		$xml = @simplexml_load_file('../setting.xml');

		Common::Send(array(
			'version'=>(string)$xml->setting[0]->Version,
			'IsMaintenance'=>$IsMaintenance
		));
	}

	public static function CreateNewAccount_100(){
		try	{
			$acc;
			$count = 0;
			do {
				$acc = Common::random(12);
				$result = MySqlPDB::$pdo->query("SELECT * FROM userdata WHERE acc = '". $acc. "'")->fetch(PDO::FETCH_ASSOC);
				$count++;
			} while (!empty($result) || $count < 10);

			// 関連テーブルを生成
			$sql = "INSERT INTO limited (acc) VALUES ('".$acc."')";
			MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

			$sql = "INSERT INTO homedata (acc) VALUES ('".$acc."')";
			MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

			// 新しいユーザーを追加
			$pw = Common::random(12);
			$sql = "INSERT INTO userdata (acc, pw) VALUES ('". $acc. "','". $pw. "')";
			MySqlPDB::$pdo->query($sql);

			// デフォルトのアイテムを追加
			ItemClass::AddItem3($acc, 105,100,1);
			ItemClass::AddItem3($acc, 101,100,2);
			ItemClass::AddItem3($acc, 3001,1,3);
			ItemClass::AddItem3($acc, 10001,1,0);
			ItemClass::AddItem3($acc, 10002,1,0);
			ItemClass::AddItem3($acc, 10003,1,0);

			Common::Send(array(
				'acc'=>$acc,
				'pw'=>$pw
			));
		} catch (Exception $e){
			LoggerClass::E()->error($e);
		}
	}

    public static function Login_101($json){
		try {
			$acc = $json->{'acc'};
			$pw = $json->{'pw'};

			// アカウント検証
			$sql = "SELECT * FROM userdata WHERE acc='$acc'";
			$result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
			if (empty($result)){
				Common::error(101001);
				return;
			}

			// パスワード検証
			if ($pw != $result['pw']){
				Common::error(101002);
				return;
			}

			$uguid = $result['id'];

			// token 更新
			$token = session_create_id();
			MySqlPDB::$pdo->query("UPDATE userdata SET token='$token',updated_at=NOW() WHERE id=$uguid");

			// limited　テーブルにない場合、追加
			$sql = "SELECT * FROM limited WHERE acc='".$acc."'";
			$result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
			if (empty($result)){
				$sql = "INSERT INTO limited (acc) VALUES ('".$acc."')";
				MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
			}

			// サブスクリプションメールを送る
			$sql = "SELECT * FROM userdata 
				INNER JOIN limited ON userdata.acc = limited.acc
				WHERE userdata.acc='$acc'";
			$result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

			$subscription_mail_added01 = $result['subscription_mail_added01'];
			$subscription_mail_added02 = $result['subscription_mail_added02'];
			$subscription_mail_added03 = $result['subscription_mail_added03'];
			$subscriptionLv01 = $result['subscriptionLv01'];
			$subscriptionLv02 = $result['subscriptionLv02'];
			$subscriptionLv03 = $result['subscriptionLv03'];

			if ($subscriptionLv01 == 1 && $subscription_mail_added01 == 0){
				EmailClass::AddEmailInItem($acc, 1);

				$sql = "UPDATE limited SET subscription_mail_added01 = 1 WHERE acc='$acc'";
				MySqlPDB::$pdo->query($sql);
			}
			if ($subscriptionLv02 == 1 && $subscription_mail_added02 == 0){
				EmailClass::AddEmailInItem($acc, 2);

				$sql = "UPDATE limited SET subscription_mail_added02 = 1 WHERE acc='$acc'";
				MySqlPDB::$pdo->query($sql);
			}
			if ($subscriptionLv03 == 1 && $subscription_mail_added03 == 0){
				EmailClass::AddEmailInItem($acc, 3);

				$sql = "UPDATE limited SET subscription_mail_added03 = 1 WHERE acc='$acc'";
				MySqlPDB::$pdo->query($sql);
			}

			// ログインボーナス
			BonusClass::SendLoginBonus($acc);

			// ログイン情報更新
			UserClass::UpdateLoginMission($acc);

			// userdata
			$sql = "SELECT * FROM userdata
					INNER JOIN limited ON userdata.acc = limited.acc
					WHERE userdata.acc='".$acc."'";
			$result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

			Common::Send(array(
				'token'=>$token,
				'myShopLv'=>$result['myShopLv'],
				'guide_end'=>$result['guide_end'],
				'guide_end2'=>$result['guide_end2'],
				'nickname'=>$result['nickname'],
				'comment'=>$result['comment'],
				'email'=>$result['email'],
				'goodNum'=>$result['goodNum_daily'],
				'lv'=>$result['lv'],
				'exp'=>$result['exp'],
			));
		} catch (PDOException $e) {
			LoggerClass::E()->error($e);
		}
	}

	
}