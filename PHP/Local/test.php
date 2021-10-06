<?php

echo "Test<br>";
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once 'ClassLoader.php';
$pdo = MySqlPDB::connectDB();

$data = '{"token":"5htbbi1eq4julnhdsi9a6l9iam","acc":"Gv3cK4uDn4Ny","page":1,"nickName":"","sortType":2}';
$data = json_decode($data);

// CMD1021_1030::BuySubscription_1027($data);
// CMD1031_1040::DeFollow_1034($data);
// CMD1031_1040::ReadFollow_1035($data);
// CMD1031_1040::ReadFollower_1036($data); 

// CMD1041_1050::ClearMission_1045($data);
CMD1011_1020::Search_1016($data);
// UserClass::UpdateLoginMission("v6hrwzMYhvXU");
// BonusClass::SendLoginBonus("v6hrwzMYhvXU");
// UserClass::UpdateLoginMission("1rXuHaYExtsh");
// CMDOrder::Charge_9000($data);
// $result = StatisticsClass::CheckTable("ZrTX9evfIa2G");
// print_r($result);


// $sql = "
// DELETE FROM friend; 
// INSERT INTO friend(follow) VALUES ('car1'); 
// INSERT INTO friend(follow) VALUES ('car1'); 
// ";

// $pdo->exec($sql);



// $data = "+1yrFgUlVTkSJnmj7n5qd4j5oFnLYVHZQ/5h4bfoDPw=";
// if (!empty($data)){
//     $data = CryptClass::decryptRJ256($data);
//     echo $data."<br>";
// }

// $data = '[{"itemId":1,"count":1},{"itemId":101,"count":2}]';
// Item::AddItems($acc, $data);

// $data = "1";
// $data = "4";
// Bonus::GetBonus($acc,$data);
// Craft::Craft($acc,1,1);

// $data= $token. "^". "1". "^". "2";
// EquipItem($pdo, $data);

// ItemClass::RemoveItemByItemId($acc, 102, 1);
// ItemClass::RemoveItemByGuid($acc, 194, 1);
// ItemClass::AddItem($acc, 1000, 1);
// ItemClass::AddItemInData($acc, 3002, 1, "日本語", 'test data');
// ItemClass::AddLockItemInData($acc, 3002, 1, "日本語", 'test data');
// echo "<br>".ItemClass::GetItemList($acc);

// UserClass::IsLogin($token, $acc);
// $json = '{"acc":"ZmxScCXswqXt","pw":"K0ZzS6LUlOw4"}';
// UserClass::Login(json_decode($json));
// UserClass::CreateNewAccount();
// UserClass::UpdateNickName($acc, "test");
// UserClass::SaveHomeData($acc, '1001,1002');
// UserClass::LoadHomeData($acc);

// CraftClass::Craft($acc, 5, 1);

// ShopClass::Buy($acc, 1000);
// ShopClass::GetCoins($acc);
// ShopClass::Charge($acc, 'pid', 100, 'rid', 'receipt');
// ShopClass::AddCoin($acc, 'coin1', 100);
// ShopClass::CostCoin($acc, 'coin1', 100);
// $data = '{"acc":"KKRj27gQFY51","shopId":80}';
// ShopClass::BuySubscription(json_decode($data));
// $data ='{"acc":"ZmxScCXswqXt"}';
// ShopClass::GetSubscriptionInfo(json_decode($data));

// MyShopClass::LevelUpMyShop($acc);
// MyShopClass::UploadBlueprint($acc, 'aaa', 435, 1, 200);
// MyShopClass::Search($acc, 1, "", 0);
// $data = '{"token":"9qu9p1556m1qhpnjs30l5ssaqo","acc":"J0nWzdoDtjje","guid":1}';
// MyShopClass::BuyMyShopItem(json_decode($data));
// MyShopClass::LoadBlueprint($acc, 1);

// EmailClass::AddEmail($acc, 'test', 'test');
// EmailClass::ReadEmail($acc, 6);
// EmailClass::GetEmail($acc, 1);
// EmailClass::GetNewEmailCount($acc);
// $data = '{"token":"aqsqeo7esch90cedoimciucouu","acc":"eEVLlxwqq0eE","guid":64}';
// EmailClass::ReceiveEmailItem(json_decode($data));

// NoticeClass::GetNoticeList($code);

// $plaintext= 'eaxI1FOZAKqF^NR6b5TtrVTk0';
// $dd = CryptClass::encryptRJ256($plaintext);
// echo $dd."<br>";
// $dd = CryptClass::decryptRJ256($dd);
// echo $dd;

// $xml = @simplexml_load_file('setting.xml');
// echo $xml->setting[0]->IsMaintenance;

// $aaa = Common::Send(array(
//     'token'=>"aaa",
//     'firstUseMyShop'=>"1"
// ));

// $data = CryptClass::decryptRJ256($aaa);
// echo "<br>".$data;

// $sql = "SELECT * FROM userdata
//             INNER JOIN limited 
//             ON userdata.acc = limited.acc";
// 					// -- WHERE userdata.acc=?";
// $stmt = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

// print_r($stmt);
// echo $stmt['firstUseMyShop'];

// BonusClass::GetRandomBonus(3);

// AllUserAddItem();
// function AllUserAddItem(){
//     $sql = "SELECT acc FROM userdata";
//     $result = MySqlPDB::$pdo->query($sql);

//     while ($acc = $result->fetch(PDO::FETCH_ASSOC)){
//         ItemClass::AddItem($acc['acc'], 1, 2);
//         ItemClass::AddItem($acc['acc'], 2, 2);
//         ItemClass::AddItem($acc['acc'], 101, 300);
//         ItemClass::AddItem($acc['acc'], 102, 300);
//         ItemClass::AddItem($acc['acc'], 103, 300);
//         ItemClass::AddItem($acc['acc'], 104, 300);
//         ItemClass::AddItem($acc['acc'], 105, 300);
//         ItemClass::AddItem($acc['acc'], 106, 300);
//         ItemClass::AddItem($acc['acc'], 107, 300);
//         ItemClass::AddItem($acc['acc'], 108, 300);
//         ItemClass::AddItem($acc['acc'], 109, 300);
//         ItemClass::AddItem($acc['acc'], 110, 300);
//         ItemClass::AddItem($acc['acc'], 111, 300);
//         ItemClass::AddItem($acc['acc'], 112, 300);
//         ItemClass::AddItem($acc['acc'], 113, 300);
//         ItemClass::AddItem($acc['acc'], 114, 300);
//         ItemClass::AddItem($acc['acc'], 115, 300);
//         ItemClass::AddItem($acc['acc'], 116, 300);
//         ItemClass::AddItem($acc['acc'], 117, 300);
//         ItemClass::AddItem($acc['acc'], 1001, 600);
//         ItemClass::AddItem($acc['acc'], 1002, 600);
//         ItemClass::AddItem($acc['acc'], 1003, 600);
//         ItemClass::AddItem($acc['acc'], 1004, 600);
//         ItemClass::AddItem($acc['acc'], 1005, 600);
//         ItemClass::AddItem($acc['acc'], 1006, 600);
//         ItemClass::AddItem($acc['acc'], 1007, 600);
//         ItemClass::AddItem($acc['acc'], 1008, 600);
//         ItemClass::AddItem($acc['acc'], 1009, 600);
//         ItemClass::AddItem($acc['acc'], 1010, 600);
//         ItemClass::AddItem($acc['acc'], 1011, 600);
//         ItemClass::AddItem($acc['acc'], 1012, 600);
//         ItemClass::AddItem($acc['acc'], 1013, 600);
//         ItemClass::AddItem($acc['acc'], 3001, 3);
//     }
// }


// $data = '{"acc":"ZTD1C9jeeK8T","pw":"3xxSPh3bSW1V"}';
// $data = json_decode($data);
// // メンテナンスしているかを確認
// $xml = @simplexml_load_file('setting.xml');
// if ($xml->setting[0]->IsMaintenance == '1') {
//     if ($code == 99){
//         UserClass::GetVersion(1);
//         return;
//     }
//     else if ($code == 101){
//         $acc = $data->{'acc'};
//         $sql = "SELECT * FROM userdata WHERE acc='$acc'";
//         $result = MySqlPDB::$pdo->query($sql)->fetch();

//         echo $result['state'];

//         if (empty($result) || $result['state']!=2){
//             Common::error(998);
//             return;
//         }
//     }
//     else{
//         Common::error(998);
//         return;
//     }
// }