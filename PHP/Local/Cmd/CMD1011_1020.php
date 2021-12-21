<?php

class CMD1011_1020{
    public static function GetCoins_1011($json){
        $acc = $json->{'acc'};

        $result = MySqlPDB::$pdo->query("SELECT * FROM userdata WHERE acc='". $acc. "'")->fetch(PDO::FETCH_ASSOC);
        Common::Send(array(
            'coin1'=>$result['coin1'],
            'coin2'=>$result['coin2'],
            'coin3'=>$result['coin3']
        ));
    }

    public static function GetBonusOne_1012($json){
        $acc = $json->{'acc'};
        $bonusId = $json->{'bonusId'};

        BonusClass::AddBonus($acc, $bunusId);
        Common::Send("");
    }

    public static function LevelUpMyShop_1013($json){
        $acc = $json->{'acc'};

		$sql = "SELECT * FROM userdata WHERE acc = '".$acc."';";
		$user = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

		// check Cost
		$xml = @simplexml_load_file('setting.xml');
		$cost = 0;

		if	($user['myShopLv'] == 0)
			$cost = $xml->setting[0]->MyShopLvUPCost1;
		else if ($user['myShopLv'] == 1)
			$cost = $xml->setting[0]->MyShopLvUPCost2;

		if ($user['coin1'] < $cost){
			Common::error(1010001);
			return;
		}

		// check max lv
		$myshopLv = $user['myShopLv'];
		if ($myshopLv == 2){
			Common::error(1013001);
			return;
		}

		// Update lv
		$newLv = ++$myshopLv;
		$sql = "UPDATE userdata SET myShopLv = ".$newLv." WHERE acc ='".$acc."'";
		MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

		// cost coin
		ShopClass::CostCoin($acc, 'coin1', $cost);

		// select new lv
		$sql = "SELECT * FROM userdata WHERE acc = '".$acc."';";
		$user = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

		Common::Send(array(
			'myShopLv'=>$user['myShopLv'],
			'coin1'=>$user['coin1']
		));
	}

    public static function UploadBlueprint_1014($json) {
        $acc = $json->{'acc'};
		$nickName = $json->{'nickName'};
		$itemGuid = $json->{'itemGuid'};
		$site = $json->{'site'};
		$price = $json->{'price'};
		$texture = $json->{'texture'};

        $sql = "SELECT * FROM items WHERE id=$itemGuid";
        $rerult = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        $itemid = $rerult['itemId'];
        $itemdata = $rerult['relationData'];
        $newname = $rerult['newName'];

        $sql = "SELECT * FROM myshop WHERE isDiscard=1";
        $rerult = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        if(empty($rerult)){
            $sql = "INSERT INTO myshop (acc, nickname, itemid, newname, data, site, price, icon) 
                VALUES ('$acc','$nickName', $itemid, '$newname', '$itemdata', $site, $price, '$texture')";
        }else{
            $myshopid = $rerult['myshopid'];
            $sql = "UPDATE myshop SET 
                acc='$acc', 
                nickname='$nickName', 
                itemid=$itemid, 
                newname='$newname', 
                icon='$texture', 
                data='$itemdata', 
                site=".$site.", 
                price=".$price.", 
                created_at= NOW(), 
                isDiscard=0 
                WHERE myshopid= '$myshopid'";
        }
        MySqlPDB::$pdo->query($sql);

        $result = StatisticsClass::GetTotalUploadBlueprintCount($acc);
        $totalUploadBlueprintCount = empty($result['totalUploadBlueprintCount']) ? 0 : $result['totalUploadBlueprintCount'];
        if($totalUploadBlueprintCount == 0) {
            EmailClass::AddEmailInItem($acc, 2003);
        }
        StatisticsClass::AddTotalUploadBlueprintCount($acc);

        Common::Send("");
    }

    public static function UpdateNickName_1015($json){
		$acc = $json->{'acc'};
		$nickName = $json->{'nickName'};

		$sql = "UPDATE userdata SET nickname = '".$nickName."' WHERE acc ='".$acc."'";
		MySqlPDB::$pdo->query($sql);

		Common::Send("");
	}

    public static function Search_1016($json){
        $acc = $json->{'acc'};
        $page = $json->{'page'};
        $nickName = $json->{'nickName'};
        $sortType = $json->{'sortType'};

        $xml = @simplexml_load_file('setting.xml');
        $selectCount = $xml->setting[0]->MyShopSearchCount;
        $offset = ($page - 1) * $selectCount;
        $salesTime = $xml->setting[0]->MyShopSalesTime;

        $sql = "SELECT * FROM myshop
            INNER JOIN limited ON myshop.acc = limited.acc
            WHERE isDiscard=0 
                && (created_at + INTERVAL $salesTime day) > NOW() 
                && myshop.acc != '$acc' ";
        // ニックネームを指定
        if (!empty($nickName)){
            $sql = $sql." && nickname='".$nickName."'";
        }

        // ソート
        switch ($sortType){
            case 0:$sql = $sql." ORDER BY price DESC ";break;
            case 1:$sql = $sql." ORDER BY price ";break;
            case 2:$sql = $sql." ORDER BY created_at DESC ";break;
            case 3:$sql = $sql." ORDER BY created_at ";break;
            case 4:$sql = $sql." ORDER BY sellNum DESC ";break;
            case 5:$sql = $sql." ORDER BY goodNum_total DESC ";break;
        }

        $result = MySqlPDB::$pdo->query($sql);
        $maxCount = $result->rowCount();

        $sql = $sql." LIMIT ".$selectCount." OFFSET ".$offset;
        $result = MySqlPDB::$pdo->query($sql);

        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            $items[]=array(
                'targetAcc'=>$row['acc'],
                'myshopid'=>$row['myshopid'],
                'itemId'=>$row['itemid'],
                'nickName'=>$row['nickname'],
                'newName'=>$row['newname'],
                'icon'=>$row['icon'],
                'price'=>$row['price'],
                'goodNum'=>$row['goodNum_total'],
                'created_at'=>$row['created_at'],
            );
        }

        if (empty($items))
            Common::Send("");
        else
            Common::Send(array(
                'items'=>$items,
                'maxCount'=>$maxCount,
            ));
    }

    public static function BuyMyShopItem_1017($json){
        $acc = $json->{'acc'};
        $guid = $json->{'guid'};

        $sql = "SELECT * FROM myshop WHERE myshopid='$guid'";
        $result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        if (ShopClass::CostCoin($acc, 'coin3', $result['price']) == 1){
            Common::error(1017001);
            return;
        }

        ItemClass::AddLockItemInData($acc, $result['itemid'], 1, $result['newname'], $result['data']);

        $cost = 0;
        switch ($result['price']){
            case 110: $cost = 100; break;
            case 330: $cost = 300; break;
            case 550: $cost = 500; break;
            case 1100: $cost = 1000; break;
            case 2200: $cost = 2000; break;
            case 5500: $cost = 5000; break;
            case 11000: $cost = 10000; break;
        }

        // 設計図主人にポイントをくれる
        ShopClass::AddCoin($result['acc'], 'coin3', $cost);
        EmailClass::AddEmailInItem($result['acc'], 2004, [$result['newname'], $cost]);

        // 販売数追加
        $sql ="UPDATE myshop SET sellNum=sellNum+1 WHERE myshopid=$guid";
        MySqlPDB::$pdo->query($sql);

        // 設計図販売記録
        StatisticsClass::BlueprintBusiness($result['acc'], $acc, $result['newname'], $result['price']);

        Common::Send("");
    }

    public static function LoadBlueprint_1018($json){
		$acc = $json->{'acc'};
		$guid = $json->{'site'};
		$isfree = $json->{'isfree'};

        // check Cost
		$xml = @simplexml_load_file('setting.xml');
		$cost = $xml->setting[0]->MyShopLoadBlueprintCost;
        $salesTime = $xml->setting[0]->MyShopSalesTime;

        $sql = "SELECT * FROM userdata WHERE acc='$acc';";
		$user = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        if ($isfree != 1){
            if (ShopClass::CostCoin($acc, 'coin1', $cost) == 1){
                Common::error(1010001);
                return;
            }
        }

        // DBデーターを　isDiscard　にする。
        $sql = "UPDATE myshop SET isDiscard=1 WHERE acc='$acc' && site=$guid";
        MySqlPDB::$pdo->query($sql);

        Common::Send("");
    }

    public static function GetEmail_1019($json){
        $acc = $json->{'acc'};
		$page = $json->{'page'};

        $sql = "SELECT count(*) FROM email WHERE isDiscard=0 AND acc = '$acc'";
        $result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
        $maxCount = $result['count(*)'];

        $xml = @simplexml_load_file('setting.xml');
        $selectCount = $xml->setting[0]->EmailSearchCount;
        $offset = ($page - 1) * $selectCount;

        $sql = "SELECT * FROM email 
            WHERE isDiscard=0 && acc = '$acc' 
            ORDER BY is_already_read, created_at DESC 
            LIMIT $selectCount OFFSET $offset";

        $result = MySqlPDB::$pdo->query($sql);
        $emails = array();
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            $emails[]=array(
                'id'=>$row['email_id'],
                'title'=>$row['title'],
                'message'=>$row['message'],
                'created_at'=>$row['created_at'],
                'is_already_read'=>$row['is_already_read'],
                'related_data'=>$row['related_data'],
                'is_already_received'=>$row['is_already_received'],
            );
        }

        $arr = array(
            'maxCount'=>$maxCount,
            'data'=>$emails,
        );

        Common::Send($arr);
    }

    public static function ReadEmail_1020($json){
        $acc = $json->{'acc'};
		$id = $json->{'guid'};

        $sql = "UPDATE email 
            SET is_already_read = 1
            WHERE email_id = $id";

        MySqlPDB::$pdo->query($sql);

        Common::Send("");
    }
}