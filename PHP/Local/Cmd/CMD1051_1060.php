<?php

class CMD1051_1060{    
    // 制限回数取得
    public static function GetShopLimitedCount_1051($json){
        $acc = $json->{'acc'};
        $shopId = $json->{'shopId'};

        $result = ShopLimitedCountClass::GetShopLimitedCount($acc, $shopId);

        $limitedCount = empty($result['limitedCount']) ? 0 : $result['limitedCount'];

        Common::Send($limitedCount);
    }

    public static function GetEquipmentInfo_1052($json){
        $acc = $json->{'acc'};
        $itemGuid = $json->{'itemGuid'};

        $sql = "SELECT * FROM equipment WHERE item_guid=$itemGuid";
        $result = MySqlPDB::$pdo->query($sql)->fetch();

        Common::Send(array(
            'skills'=>$result['skills'],
        ));
    }
}