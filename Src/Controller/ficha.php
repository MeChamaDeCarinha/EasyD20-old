<?php

namespace Src\Controller;

use PDO;

class ficha {

    public $dados = array(
        "url" => URL
    );

    public function loadFichas() {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        \Src\Lib\Sec::verifyUser();

        $player_id = $_SESSION["id"];
        if(!isset($_SESSION["aventura_id"])){
            $this->dados["alert"] = "Nenhuma aventura foi selecionada";

            $this->dados["user"] = \Src\Lib\Dashboard::queryUser();
            $this->dados["aven"] = \Src\Lib\Dashboard::queryAventuras();
            echo $ambiente->render("fichas.html", $this->dados);
            die();
        }
        $aventura_id = $_SESSION["aventura_id"];
        $mestre = $_SESSION["mestre"];

        $pdo = \Src\Lib\Database::connection();

        if($mestre){
            $ficha_mestre = $pdo->query("select ficha_mestre from aventura where id = {$aventura_id}", PDO::FETCH_ASSOC);
            
            foreach($ficha_mestre as $aventura){
                if($aventura["ficha_mestre"]){
                    $result = $pdo->query("select id from ficha where cod_aventura = {$aventura_id}", PDO::FETCH_ASSOC);
                }
                else {
                    $result = $pdo->query("select id from ficha where cod_aventura = {$aventura_id} and cod_usuario != {$player_id}", PDO::FETCH_ASSOC);
                }
            }

            if($result->rowCount() == 0){
                $this->dados["alert"] = "Sua aventura ainda não tem jogadores";

                $this->dados["user"] = \Src\Lib\Dashboard::queryUser();
                $this->dados["aven"] = \Src\Lib\Dashboard::queryAventuras();
                echo $ambiente->render("fichas.html", $this->dados);
                die();
            }
        }
        else {
            $result = $pdo->query("select id from ficha where cod_usuario = {$player_id} and cod_aventura = {$aventura_id}", PDO::FETCH_ASSOC);

            foreach($result as $id){
                $ficha_id = $id["id"];
            }

            header("Location: " . URL . "/fichas/{$ficha_id}");
        }


        $fichas = array();

        foreach($result as $ficha){
            $fichas[] = new \Src\Model\Ficha($ficha["id"]);
        }

        $this->dados["fichas"] = $fichas;

        $this->dados["user"] = \Src\Lib\Dashboard::queryUser();
        $this->dados["aven"] = \Src\Lib\Dashboard::queryAventuras();
        echo $ambiente->render("fichas.html", $this->dados);
    }

    public function showFicha($url){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $pdo = \Src\Lib\Database::connection();

        \Src\Lib\Sec::verifyUser();
        $id = $_SESSION["id"];
        $ficha_id = $url["ficha_id"];

        $result = $pdo->query("select id, cod_aventura from ficha where id = {$ficha_id}", PDO::FETCH_ASSOC);

        if($result->rowCount() == 0){
            header("Location: " . URL . "/aventuras");
            die();
        }

        foreach($result as $ficha){
            $this->dados["ficha"] = new \Src\Model\Ficha($ficha["id"]);
            $this->dados["aventura"] = new \Src\Model\Aventura($ficha["cod_aventura"]);
            $mestre = $pdo->query("select id from aventura_usuario where cod_usuario = {$id} and cod_aventura = {$ficha['cod_aventura']} and mestre = 1", PDO::FETCH_ASSOC);
            if($mestre->rowCount()){
                $this->dados["mestre"] = 1;
            }
        }

        $this->dados["user"] = \Src\Lib\Dashboard::queryUser();
        $this->dados["aven"] = \Src\Lib\Dashboard::queryAventuras();
        echo $ambiente->render("ficha.html", $this->dados);
    }

    public function showEditar($url){
        \Src\Lib\Sec::verifyUser();

        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $ficha_id = $url["ficha_id"];

        $this->dados["ficha"] = new \Src\Model\Ficha($ficha_id);
        echo $ambiente->render("fichaEditar.html", $this->dados);
    }

    public function save($url){
        \Src\Lib\Sec::verifyUser();
        
        $ficha_id = $url["ficha_id"];
        $nome = filter_var($url['nome'], FILTER_SANITIZE_STRING);
        $idade = filter_var($url['idade'], FILTER_SANITIZE_NUMBER_INT);
        $altura = $url['altura'];
        $peso = $url['peso'];
        $raca = filter_var($url['raca'], FILTER_SANITIZE_STRING);
        $classe = filter_var($url['classe'], FILTER_SANITIZE_STRING);
        $nivel = filter_var($url['nivel'], FILTER_SANITIZE_NUMBER_INT);
        $pontos_exp = filter_var($url['pontos_exp'], FILTER_SANITIZE_NUMBER_INT);
        $dinheiro = $url['dinheiro'];

        $ficha = new \Src\Model\Ficha($ficha_id);

        $ficha->nome = $nome;

        if(empty($idade)){
            $ficha->idade = 0;
        }
        else{
            $ficha->idade = $idade;
        }

        if(empty($altura)){
            $ficha->altura = 0;
        }
        else{
            $ficha->altura = $altura;
        }

        if(empty($peso)){
            $ficha->peso = 0;
        }
        else {
            $ficha->peso = $peso;
        }

        $ficha->raca = $raca;
        $ficha->classe = $classe;

        if(empty($nivel)){
            $ficha->nivel = 0;
        }
        else{
            $ficha->nivel = $nivel;
        }

        if(empty($pontos_exp)){
            $ficha->pontos_exp = 0;
        }
        else{
            $ficha->pontos_exp = $pontos_exp;
        }

        if(empty($dinheiro)){
            $ficha->dinheiro = 0;
        }
        else{
            $ficha->dinheiro = $dinheiro;
        }

        $ficha->update();

        header("Location: " . URL . "/fichas/{$ficha_id}");
    }
}