<?php

namespace Src\Lib;

use PDO;

class Sec {
    public static function verifyUser() {
        if(!isset($_SESSION)){
            session_start();
        }

        if(!isset($_SESSION["id"])){
            $_SESSION["alert"] = "Faça login para acessar";
            header("Location: " . URL . "/login");
            die();
        }
    }

    // public static function verifyAventura($id_aventura) {
    //     $pdo = \Src\Lib\Database::connection();

    //     \Src\Lib\Sec::verifyUser();

    //     $id = $_SESSION["id"];

    //     $result = $pdo->query("select id, banido from aventura_usuario where cod_usuario = {$id} and cod_aventura = {$id_aventura}", PDO::FETCH_ASSOC);

    //     if($result->rowCount() == 0){
    //         unset($_SESSION["aventura_id"]);
    //         unset($_SESSION["mestre"]);

    //         header("Location: " . URL . "/error/403");        
    //         die();
    //     }

    //     foreach($result as $aventura){
    //         if($aventura["banido"]){
    //             unset($_SESSION["aventura_id"]);
    //             unset($_SESSION["mestre"]);
                
    //             header("Location: " . URL . "/error/403");
    //             die();
    //         }
    //     }
    // }

    // public static function verifyMestre($id_aventura) {
    //     $pdo = \Src\Lib\Database::connection();

    //     \Src\Lib\Sec::verifyAventura($id_aventura);

    //     $id = $_SESSION["id"];

    //     $result = $pdo->query("select id from aventura_usuario where cod_usuario = {$id} and cod_aventura = {$id_aventura} and mestre = 1", PDO::FETCH_ASSOC);

    //     if($result->rowCount() == 0){
    //         unset($_SESSION["aventura_id"]);
    //         unset($_SESSION["mestre"]);
            
    //         header("Location: " . URL . "/error/403");
    //         die();
    //     }
    // }
}