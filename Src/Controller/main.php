<?php

namespace Src\Controller;

class main {
    public $dados = array(
        "url" => URL
    );

    public function loadTemplate() {
        $carregador = new \Twig\Loader\FilesystemLoader("./Src/View");
        $ambiente = new \Twig\Environment($carregador);

        if(!isset($_SESSION)){
            session_start();
        }
        if(isset($_SESSION["id"])){
            $this->dados["user"] = new \Src\Model\Usuario($_SESSION["id"]);
        }

        echo $ambiente->render("home.html", $this->dados);
    }
}