<?php
/**
 * 設定クラス
 */
class ConfigClass {
    const IS_LOGFILE = true; // ログファイル出力フラグ true=出力あり/false=なし
    const LOG_LEVEL = 3; // ログレベル 0=ERROR/1=WARN/2=INFO/3=DEBUG
    const LOGDIR_PATH = './logs/'; // ログファイル出力ディレクトリ
    const LOGFILE_NAME = 'console'; // ログファイル名
    const LOGFILE_MAXSIZE = 10485760; // ログファイル最大サイズ（Byte）
    const LOGFILE_PERIOD = 30; // ログ保存期間（日）

    public static function ReadConfig($url) {
        $url = 'Json/'. $url. '.json';
        $json = file_get_contents($url);
        $arr = json_decode($json,true);

        $Dic;
        $json_count = count($arr);
        for($i=$json_count-1;$i>=0;$i--){
            $Dic[$arr[$i]["ID"]] = $arr[$i];
        }

        return $Dic;
    }

    public static function ReadConfigDefault($url) {
        $url = 'Json/'. $url. '.json';
        $json = file_get_contents($url);
        $arr = json_decode($json,true);

        return $arr;
    }
}

?>