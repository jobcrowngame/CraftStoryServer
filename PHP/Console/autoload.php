<?php

class ClassLoader
{
    public static function loadClass($class)
    {
        $file_name = "Class/{$class}.php";     //クラスが置いてあるディレクトリを指定
        require_once $file_name;
        return true;
    }
}

spl_autoload_register(__NAMESPACE__ . '\ClassLoader::loadClass');