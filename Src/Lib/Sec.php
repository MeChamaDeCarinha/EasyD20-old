<?php

namespace Src\Lib;

use PDO;

class Sec {
    public static function verify() {
        if(!isset($_SESSION)){
            session_start();
        }

        if(!isset($_SESSION["id"])){
            header("Location: " . URL . "/login");
            session_destroy();
            die();
        }
    }
}