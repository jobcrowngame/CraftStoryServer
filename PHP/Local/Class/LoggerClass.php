<?php
/**
 * ログ
 */
class LoggerClass {

    // ログレベル
    const LOG_LEVEL_ERROR = 0;
    const LOG_LEVEL_WARN = 1;
    const LOG_LEVEL_INFO = 2;
    const LOG_LEVEL_DEBUG = 3;

    private static $singleton;

    /**
     * インスタンスを生成する
     */
    public static function E()
    {
        if (!isset(self::$singleton)) {
            self::$singleton = new LoggerClass();
        }
        return self::$singleton;
    }

    /**
     * コンストラクタ
     */
    private function __construct() {
    }

    /**
     * ERRORレベルのログ出力する
     * @param string $msg メッセージ
     */
    public function error($msg) {
        if(self::LOG_LEVEL_ERROR <= ConfigClass::LOG_LEVEL) {
            $this->out(0, $msg);
        }
    }

    /**
     * WARNレベルのログ出力する
     * @param string $msg メッセージ
     */
    public function warn($msg) {
        if(self::LOG_LEVEL_WARN <= ConfigClass::LOG_LEVEL) {
            $this->out(1, $msg);
        }
    }

    /**
     * INFOレベルのログ出力する
     * @param string $msg メッセージ
     */
    public function info($msg) {
        if(self::LOG_LEVEL_INFO <= ConfigClass::LOG_LEVEL) {
            $this->out(2, $msg);
        }
    }

    /**
     * DEBUGレベルのログ出力する
     * @param string $msg メッセージ
     */
    public function debug($msg) {
        if(self::LOG_LEVEL_DEBUG <= ConfigClass::LOG_LEVEL) {
            $this->out(3, $msg);
        }
    }

    /**
     * ログ出力する
     * @param string $level ログレベル
     * @param string $msg メッセージ
     */
    private function out($level, $msg) {
        if(ConfigClass::IS_LOGFILE) {

            $pid = getmypid();
            $time = $this->getTime();
            $flag = $this->getFlag($level);
            $logMessage = "[{$time}][{$pid}][{$flag}] " . rtrim($msg) . "\n";
            $logFilePath = ConfigClass::LOGDIR_PATH . date('Ymd').'_'.ConfigClass::LOGFILE_NAME . "_".$flag. '.log';

            $result = file_put_contents($logFilePath, $logMessage, FILE_APPEND | LOCK_EX);
            if(!$result) {
                error_log('LogUtil::out error_log ERROR', 0);
            }

            if(ConfigClass::LOGFILE_MAXSIZE < filesize($logFilePath)) {
                // ファイルサイズを超えた場合、リネームしてgz圧縮する
                $oldPath = ConfigClass::LOGDIR_PATH . ConfigClass::LOGFILE_NAME . '_' . date('YmdHis');
                $oldLogFilePath = $oldPath . '.log';
                rename($logFilePath, $oldLogFilePath);
                $gz = gzopen($oldPath . '.gz', 'w9');
                if($gz) {
                    gzwrite($gz, file_get_contents($oldLogFilePath));
                    $isClose = gzclose($gz);
                    if($isClose) {
                        unlink($oldLogFilePath);
                    } else {
                        error_log("gzclose ERROR.", 0);
                    }
                } else {
                    error_log("gzopen ERROR.", 0);
                }

                // 古いログファイルを削除する
                $retentionDate = new DateTime();
                $retentionDate->modify('-' . ConfigClass::LOGFILE_PERIOD . ' day');
                if ($dh = opendir(ConfigClass::LOGDIR_PATH)) {
                    while (($fileName = readdir($dh)) !== false) {
                        $pm = preg_match("/" . preg_quote(ConfigClass::LOGFILE_NAME) . "_(\d{14}).*\.gz/", $fileName, $matches);
                        if($pm === 1) {
                            $logCreatedDate = DateTime::createFromFormat('YmdHis', $matches[1]);
                            if($logCreatedDate < $retentionDate) {
                                unlink(ConfigClass::LOGDIR_PATH . '/' . $fileName);
                            }
                        }
                    }
                    closedir($dh);
                }
            }
        }
    }

    /**
     * 現在時刻を取得する
     * @return string 現在時刻
     */
    private function getTime() {
        $miTime = explode('.',microtime(true));
        $msec = str_pad(substr($miTime[1], 0, 3) , 3, "0");
        $time = date('Y-m-d H:i:s', $miTime[0]) . '.' .$msec;
        return $time;
    }

    private function getFlag($level){
        switch ($level){
            case self::LOG_LEVEL_ERROR: return "ERROR";
            case self::LOG_LEVEL_WARN: return "WARNING";
            case self::LOG_LEVEL_INFO: return "INFO";
            case self::LOG_LEVEL_DEBUG: return "DEBUG";
            default: return "nnn";
        }
    }
}