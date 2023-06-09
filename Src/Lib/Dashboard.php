<?php

namespace Src\Lib;

use PDO;

class Dashboard {
    public static function queryUser() {
        $pdo = \Src\Lib\Database::connection();

        if(!isset($_SESSION)){
            session_start();
        }

        $user = new \Src\Model\Usuario($_SESSION["id"]);

        $dados = array(
            "nome" => $user->nome,
            "img_path" => $user->img_path
        );

        $result = $pdo->query("select * from aventura_usuario where cod_usuario = {$_SESSION["id"]}", PDO::FETCH_ASSOC);

        foreach($result as $aventura){
            $dados["aven"] = new \Src\Model\Aventura($aventura["cod_aventura"]);
        }

        return $dados;
    }

    public static function queryAventuras() {
        $pdo = \Src\Lib\Database::connection();

        if(!isset($_SESSION)){
            session_start();
        }

        $dados = array();

        $result = $pdo->query("select * from aventura_usuario where cod_usuario = {$_SESSION["id"]}", PDO::FETCH_ASSOC);

        foreach($result as $aventura){
            $dados[] = new \Src\Model\Aventura($aventura["cod_aventura"]);
        }

        return $dados;
    }
}