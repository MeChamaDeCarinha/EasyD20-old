<?php

namespace Src\Controller;

class dashboard {

    public $dados = array(
        "url" => URL
    );

    public function loadTemplate() {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        if(!isset($_SESSION)){
            session_start();
            if(!isset($_SESSION['id'])){
                header("Location: " . URL . "/login");
            }
            else {
                header("Location: " . URL . "/dashboard/aventura");
            }
        }

        echo $ambiente->render("dashboard.html", $this->dados);
    }


}