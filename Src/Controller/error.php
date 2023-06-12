<?php

namespace Src\Controller;

class error {
    public $dados = array(
        "url" => URL,
    );

    public function show($url) {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $this->dados["error"] = $url["error"];
        echo $ambiente->render("error.html", $this->dados);
    }
}