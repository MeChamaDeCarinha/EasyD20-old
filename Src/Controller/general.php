<?php

namespace Src\Controller;

class general {
    public $dados = array(
        "url" => URL
    );

    public function showHomepage() {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        if(!isset($_SESSION)){
            session_start();
        }
        if(isset($_SESSION["id"])){
            $this->dados["user"] = new \Src\Model\Usuario($_SESSION["id"]);
        }

        echo $ambiente->render("home.html", $this->dados);
    }

    public function showDados() {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $this->dados["user"] = \Src\Lib\Dashboard::queryUser();
        $this->dados["aven"] = \Src\Lib\Dashboard::queryAventuras();
        echo $ambiente->render("dados.html", $this->dados);
    }

    public function showAjuda() {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $this->dados["user"] = \Src\Lib\Dashboard::queryUser();
        $this->dados["aven"] = \Src\Lib\Dashboard::queryAventuras();
        echo $ambiente->render("ajuda.html", $this->dados);
    }
}