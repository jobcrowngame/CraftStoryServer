<?php

class BonusClass{
    public static function GetRandomBonusByPond($pondId){
        $bonus = 0;
        $random = rand(0, 1000);
        $config = ConfigClass::ReadConfig('RandomBonusPond')[$pondId];
        $curPercent = 0;

        $curPercent += $config['Percent01'];
        if ($random <= $curPercent){
            $bonusList = explode(",", $config['BonusList01']);
            $bonus = $bonusList[rand(0, count($bonusList) - 1)];
            return array($bonus, $config['Level01']);
        }
        
        $curPercent += $config['Percent02'];
        if  ($config['Percent02'] > 0 && $random <= $curPercent){
            $bonusList = explode(",", $config['BonusList02']);
            $bonus = $bonusList[rand(0, count($bonusList) - 1)];
            return array($bonus, $config['Level02']);
        }

        $curPercent += $config['Percent03'];
        if  ($config['Percent03'] > 0 && $random <= $curPercent){
            $bonusList = explode(",", $config['BonusList03']);
            $bonus = $bonusList[rand(0, count($bonusList) - 1)];
            return array($bonus, $config['Level03']);
        }

        $curPercent += $config['Percent04'];
        if  ($config['Percent04'] > 0 && $random <= $curPercent){
            $bonusList = explode(",", $config['BonusList04']);
            $bonus = $bonusList[rand(0, count($bonusList) - 1)];
            return array($bonus, $config['Level04']);
        }

        $curPercent += $config['Percent05'];
        if  ($config['Percent05'] > 0 && $random <= $curPercent){
            $bonusList = explode(",", $config['BonusList05']);
            $bonus = $bonusList[rand(0, count($bonusList) - 1)];
            return array($bonus, $config['Level05']);
        }

        $curPercent += $config['Percent06'];
        if  ($config['Percent06'] > 0 && $random <= $curPercent){
            $bonusList = explode(",", $config['BonusList06']);
            $bonus = $bonusList[rand(0, count($bonusList) - 1)];
            return array($bonus, $config['Level06']);
        }

        $curPercent += $config['Percent07'];
        if  ($config['Percent07'] > 0 && $random <= $curPercent){
            $bonusList = explode(",", $config['BonusList07']);
            $bonus = $bonusList[rand(0, count($bonusList) - 1)];
            return array($bonus, $config['Level07']);
        }
    }

    // ボーナスを追加させる
    public static function AddBonus($acc, $bonusId){
        $items = Common::GetItemsByBonus($bonusId);
        foreach (array_keys($items) as $key){
            if($key == 9000){
                ShopClass::AddCoin($acc, 'coin1', $items[$key]);
            }else if($key == 9001){
                ShopClass::AddCoin($acc, 'coin2', $items[$key]);
            }else if($key == 9002){
                ShopClass::AddCoin($acc, 'coin3', $items[$key]);
            }
            else{
                ItemClass::AddItem($acc, $key, $items[$key]);
            }
        }
    }
    
    public static function GetRouletteIndex($acc, $rouletteId){
        $random = rand(0, 1000);
        $config = ConfigClass::ReadConfig('Roulette')[$rouletteId];
        $list = explode(",", $config['CellList']);
        $index = 0;
        $curPercent = 0;

        for ($i = 0; $i < count($list); $i++){
            $cellconfig = ConfigClass::ReadConfig('RouletteCell')[$list[$i]];
            $curPercent += $cellconfig['Percent'];
            if ($curPercent >= $random){
                // アイテムをDBに追加
                BonusClass::AddBonus($acc, $cellconfig['Bonus']);

                return $i + 1;
            }
        }

        return 0;
    }

    // ログインボーナス
    public static function SendLoginBonus($acc){
        $sql = "SELECT * FROM limited WHERE acc='$acc'";
		$result = MySqlPDB::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        if ($result['loginbonus_added'] == 0){
            $sql = "SELECT * FROM loginbonus WHERE active = 1";
            $result = MySqlPDB::$pdo->query($sql);
            while($row = $result->fetch(PDO::FETCH_ASSOC)){
                $startTime = strtotime($row['start_at']);
                $endTime = strtotime($row['end_at']);
                $now = time();

                if ($now >= $startTime && $now < $endTime){
                    $loginbonusId = $row['loginbonusid'];
                    $config = ConfigClass::ReadConfig("LoginBonus");
                    $now = date('Y/m/d');

                    foreach($config as $cell){
                        if ($cell['LoginBonusId'] == $loginbonusId && $cell['Time'] == $now){
                            EmailClass::AddEmailInItem($acc, $cell['MailId']);
                        }
                    }
                }
            }

            $sql = "UPDATE limited SET loginbonus_added = 1 WHERE acc='$acc'";
            MySqlPDB::$pdo->query($sql);
        }
    }
}