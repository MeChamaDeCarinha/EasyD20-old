<?php

namespace Src\Lib;

use PDO;

class Database {
    public static function connection(){
        $url = DB_URL;
        $user = DB_USER;
        $password = DB_SENHA;
        $database = DB;

        $pdo = new PDO("mysql:host={$url};dbname={$database}", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}