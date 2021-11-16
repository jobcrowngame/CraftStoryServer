<?php

class ClassLoader{
    // オートロードで調べるディレクトを格納する変数
    protected $dir;
    // オートロード実行メソッド
    public function register()
    {
        // loadClassメソッドを実行する
        spl_autoload_register(array($this, 'loadClass'));
    }

    // 探索するディレクトリを登録
    public function registerDir($dir)
    {
        $this->dirs[] = $dir;
    }
    // 未定義のクラスをnewした場合呼び出される
    // $classはその時のクラス名
    public function loadClass($class)
    {
        // 登録していたディレクトリにインスタンス化しようとしたクラスファイルが存在しているか確認
        foreach($this->dirs as $dir){
            $file = $dir. '/' . $class . '.php';

            // 存在していればrequireし、未定義エラーを回避
            if(is_readable($file)){
                require_once $file;

                return;
            }
        }
    }
}

$loader = new ClassLoader();
$loader->registerDir(dirname(__FILE__).'/Class');
$loader->registerDir(dirname(__FILE__).'/Cmd');
$loader->registerDir(dirname(__FILE__).'/Cor');
$loader->register();
?>