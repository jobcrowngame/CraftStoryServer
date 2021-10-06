<?php

class UserClass{
	public static function IsLogin($token, $acc){
		$que = MySqlPDB::$pdo->query(
			"SELECT COUNT(*) AS 'num'
				FROM userdata
				WHERE acc = '". $acc. "'")->fetch();

		return $que['num'] > 0;
	}

	public static function GetUserDataById($id){
		$sql = "SELECT * FROM userdata WHERE id=$id";
		return MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
	}
	public static function GetUserDataByAcc($acc){
		$sql = "SELECT * FROM userdata WHERE acc='$acc'";
		return MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
	}

	public static function UpdateLoginMission($acc){
		// 本日ログインしたかのチェック
		$sql = "SELECT clear_mission_main,clear_mission_daily,clear_mission_limit,logined,login_number FROM limited WHERE acc='$acc'";
		$result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
		if($result['logined'] == 1){
			return;
		}

		$loginNum = $result['login_number'];
		$missionDaily = $result['clear_mission_daily'];
		$missionMain = $result['clear_mission_main'];
		$missionLimit = $result['clear_mission_limit'];
		$newLoginNum = $loginNum + 1;

		$sql = "UPDATE limited SET logined=1,login_number=$newLoginNum WHERE acc='$acc'";
		MySqlPDB::$pdo->query($sql);

		$config = ConfigClass::ReadConfig('Mission');
		// 始めの場合
		if($newLoginNum == 1){
			foreach	($config as $row){
				if ($row['Type'] == 1 && $row['RojicType'] == 2){
					$value = $row['ID']."^".$newLoginNum."^0";
					$missionDaily = empty($missionDaily) ? $value : $missionDaily.",".$value;
				}else if ($row['Type'] == 2 && $row['RojicType'] == 2){
					$value = $row['ID']."^".$newLoginNum."^0";
					$missionMain = empty($missionMain) ? $value : $missionMain.",".$value;
				}
			}

			$sql = "UPDATE limited SET clear_mission_daily='$missionDaily',clear_mission_main='$missionMain' WHERE acc='$acc'";
        	MySqlPDB::$pdo->query($sql);
		}else{
			// ディリー
			$arr = explode(",", $missionDaily);
			for($i = 0; $i < count($arr); $i++){
				$mission = explode("^", $arr[$i]);
				if ($config[$mission[0]]['RojicType'] == 2){
					$arr[$i] = $mission[0]."^1^0";
				}else{
					$arr[$i] = $mission[0]."^0^0";
				}
			}
			$newMissionDaily = $arr[0];
			for($i = 1; $i < count($arr); $i++){
				$newMissionDaily = $newMissionDaily.",".$arr[$i];
			}

			// メイン
			$arr = explode(",", $missionMain);
			for($i = 0; $i < count($arr); $i++){
				$mission = explode("^", $arr[$i]);
				if ($config[$mission[0]]['RojicType'] == 2){
					$arr[$i] = $mission[0]."^".$newLoginNum."^".$mission[2];
				}
			}
			$newMissionMain = $arr[0];
			for($i = 1; $i < count($arr); $i++){
				$newMissionMain = $newMissionMain.",".$arr[$i];
			}

			$sql = "UPDATE limited SET clear_mission_daily='$newMissionDaily',clear_mission_main='$newMissionMain' WHERE acc='$acc'";
        	MySqlPDB::$pdo->query($sql);
		}
	}
}