<?php

namespace App\lib\Database;

abstract class Conexao{
    private static $conn;

    public static function Connect(){
        if(!self::$conn){
            self::$conn = new \PDO("mysql:host=".HOST.";dbname=".DBNAME,USER,PASSWORD);   
            self::$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$conn->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);  

        }
        return self::$conn;

    }
} 



