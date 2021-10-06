<?php

class CMDOrder{
    public static function SaveHomeData_6000($json){
		$acc = $json->{'acc'};
		$data = $json->{'homedata'};

		$sql = "SELECT * FROM homedata WHERE acc='$acc'";
		$result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

		if	(empty($result)){
			$sql = "INSERT INTO homedata (acc, homedata) VALUES ('$acc', '$data')";
		}else{
			$sql = "UPDATE homedata SET homedata='$data' WHERE acc='$acc'";
		}

		MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

		Common::Send("");
	}

    public static function LoadHomeData_6001($json){
		$acc = $json->{'acc'};

		$sql = "SELECT homedata FROM homedata WHERE acc='$acc'";
		$result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

		if (empty($result['homedata']))
            Common::Send("");
        else
            Common::Send(array(
				'homedata'=>$result['homedata']
			));
	}

    public static function Charge_9000($json){
        $acc = $json->{'acc'};
        $productId = $json->{'productId'};
        $transactionID = $json->{'transactionId'};

        $arr = ConfigClass::ReadConfig('Shop');

        $bonusId;
        $costMoney;
        $json_count = count($arr);

        foreach ($arr as $row){
            if ($row["Name"] == $productId){
                $bonusId = $row['Bonus'];
                $costMoney = $row['CostCount'];
                break;
            }  
        }

        $sql = "INSERT INTO charge (acc, productId, money, transactionID) 
            VALUES ('".$acc."','".$productId."', ".$costMoney.", '".$transactionID."')";
        MySqlPDB::$pdo->query($sql);

        $coinCount;
        $items = Common::GetItemsByBonus($bonusId);
        foreach (array_keys($items) as $key) {
            if($key == 9000){
                $coinCount = $items[$key];
                break;
            }
        }
        ShopClass::AddCoin($acc, 'coin1', $coinCount);

        Common::Send("");
    }
}