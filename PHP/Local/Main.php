<?php

require_once 'ClassLoader.php';

try{
    $code = $_POST["code"];
    $data = $_POST["data"];

    if (!empty($data)){
        // 暗号化データ解析する
        $jsondata = CryptClass::decryptRJ256($data);

        // jsonデータ解析
        $data = json_decode($jsondata);
    }

    // DB接続
    $pdo = new MySqlPDB();
    $pdo->connectDB();

    // メンテナンスしているかを確認
    $xml = @simplexml_load_file('setting.xml');
    if ($xml->setting[0]->IsMaintenance == '1') {
        if ($code == 99){
            CMD0_1000::GetVersion_99(1);
            return;
        }
        else if ($code == 101){
            $acc = $data->{'acc'};
            $sql = "SELECT * FROM userdata WHERE acc='$acc'";
            $result = MySqlPDB::$pdo->query($sql)->fetch();
    
            if (empty($result) || $result['state']!=2){
                Common::error(998);
                return;
            }
        }
        else if ($code == 100){
            Common::error(998);
            return;
        }
    }
    
    if ($code == 99) {
        CMD0_1000::GetVersion_99(0);
    } else if ($code == 100) {
        LoggerClass::E()->info("[CMD:$code]");
        CMD0_1000::CreateNewAccount_100();
    } else if ($code == 101) {
        $acc = $data->{'acc'};
        LoggerClass::E()->info("[CMD:$code][ACC:$acc]");
        CMD0_1000::Login_101($data);
    } else if ($code == 9999){
        $acc = $data->{'acc'};
        LoggerClass::E()->error("$jsondata");
        Common::Send("");
    } else {
        $acc = $data->{'acc'};
        $token = $data->{'token'};
        if  ($code != 6000){
            LoggerClass::E()->info("[CMD:$code]$jsondata");
        }
        
        if (UserClass::IsLogin($token, $acc)){
            switch ($code) {
                case 1001: CMD1001_1010::GetItemList_1001($data); break;
                case 1002: CMD1001_1010::UseItem_1002($data); break;
                case 1003: CMD1001_1010::RemoveItemByItemId_1003($data); break;
                case 1004: CMD1001_1010::AddItem_1004($data); break;
                case 1005: CMD1001_1010::AddItemInData_1005($data); break;
                case 1006: CMD1001_1010::AddItems_1006($data); break;
                case 1007: CMD1001_1010::EquipItem_1007($data); break;
                case 1008: CMD1001_1010::Craft_1008($data); break;
                case 1009: CMD1001_1010::GetBonus_1009($data); break;
                case 1010: CMD1001_1010::Buy_1010($data); break;
                
                case 1011: CMD1011_1020::GetCoins_1011($data); break;
                case 1012: CMD1011_1020::GetBonusOne_1012($data); break;
                case 1013: CMD1011_1020::LevelUpMyShop_1013($data); break;
                case 1014: CMD1011_1020::UploadBlueprint_1014($data); break;
                case 1015: CMD1011_1020::UpdateNickName_1015($data); break;
                case 1016: CMD1011_1020::Search_1016($data); break;
                case 1017: CMD1011_1020::BuyMyShopItem_1017($data); break;
                case 1018: CMD1011_1020::LoadBlueprint_1018($data); break;
                case 1019: CMD1011_1020::GetEmail_1019($data); break;
                case 1020: CMD1011_1020::ReadEmail_1020($data); break;

                case 1021: CMD1021_1030::GetNewEmailCount_1021($data); break;
                case 1022: CMD1021_1030::GetRandomBonus_1022($data); break;
                case 1023: CMD1021_1030::GetNoticeList_1023($data); break;
                case 1024: CMD1021_1030::GetNotice_1024($data); break;
                case 1025: CMD1021_1030::GetMyShopInfo_1025($data); break;
                case 1026: CMD1021_1030::ReceiveEmailItem_1026($data); break;
                case 1027: CMD1021_1030::BuySubscription_1027($data); break;
                case 1028: CMD1021_1030::GetSubscriptionInfo_1028($data); break;
                case 1029: CMD1021_1030::GuideEnd_1029($data); break;
                case 1030: CMD1021_1030::Gacha_1030($data); break;

                case 1031: CMD1031_1040::DeleteItem_1031($data); break;
                case 1032: CMD1031_1040::DeleteItemList_1032($data); break;
                case 1033: CMD1031_1040::Follow_1033($data); break;
                case 1034: CMD1031_1040::DeFollow_1034($data); break;
                case 1035: CMD1031_1040::ReadFollow_1035($data); break;
                case 1036: CMD1031_1040::ReadFollower_1036($data); break;
                case 1037: CMD1031_1040::UpdateComment_1037($data); break;
                case 1038: CMD1031_1040::SearchFriend_1038($data); break;
                case 1039: CMD1031_1040::GetBlueprintPreviewData_1039($data); break;
                case 1040: CMD1031_1040::GetFriendHomeData_1040($data); break;
                
                case 1041: CMD1041_1050::ExchangePoints_1041($data); break;
                case 1042: CMD1041_1050::GachaAddBonusAgain_1042($data); break;
                case 1043: CMD1041_1050::GetItemRelationData_1043($data); break;
                case 1044: CMD1041_1050::GetMissionInfo_1044($data); break;
                case 1045: CMD1041_1050::ClearMission_1045($data); break;
                case 1046: CMD1041_1050::GetMissionBonus_1046($data); break;
                case 1047: CMD1041_1050::MyShopGoodEvent_1047($data); break;
                case 1048: CMD1041_1050::GetBlueprintPreviewDataByItemGuid_1048($data); break;
                
                
                case 6000: CMDOrder::SaveHomeData_6000($data); break;
                case 6001: CMDOrder::LoadHomeData_6001($data); break;
                case 9000: CMDOrder::Charge_9000($data); break;

                default:LoggerClass::E()->error("Bad CMD ". $code);
            }
        }
    }

    $pdo = null;    //DB切断
}catch (Exception $e) {
    LoggerClass::E()->error($e);
    Common::error(999);
}catch(CryptographicException $e){
    LoggerClass::E()->error($e);
    Common::error(999);
}catch (PDOException $e) {
    LoggerClass::E()->error($e);
    Common::error(999);
}