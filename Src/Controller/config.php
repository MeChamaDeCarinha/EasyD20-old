<?php

namespace Src\Controller;

use PDO;

class config {

    public $dados = array(
        "url" => URL
    );


    public function show(){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        \Src\Lib\Sec::verifyUser();

        $this->dados["usuario"] = new \Src\Model\Usuario($_SESSION["id"]);

        $this->dados["user"] = \Src\Lib\Dashboard::queryUser();
        $this->dados["aven"] = \Src\Lib\Dashboard::queryAventuras();
        echo $ambiente->render("config.html", $this->dados);
    }
}