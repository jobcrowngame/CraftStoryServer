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
}