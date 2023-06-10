<?php

namespace Src\Controller;

use PDO;

class habilidade {

    public $dados = array(
        "url" => URL
    );

    public function showEditar($url) {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $ficha_id = $url["ficha_id"];
        $habilidade_id = $url["habilidade_id"];

        $this->dados["ficha"] = $ficha_id;
        $this->dados["habilidade"] = new \Src\Model\Habilidade($habilidade_id);

        echo $ambiente->render("habilidadeEditar.html", $this->dados);
    }

    public function showNova($url) {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $this->dados["ficha"] = $url["ficha_id"];

        echo $ambiente->render("habilidadeCriar.html", $this->dados);
    }

    public function save($url){
        $ficha_id = $url["ficha_id"];
        $id = $url["habilidade_id"];
        $nome = filter_var($url['nome'], FILTER_SANITIZE_STRING);
        $forca = filter_var($url['forca'], FILTER_SANITIZE_NUMBER_INT);
        $nivel = filter_var($url['nivel'], FILTER_SANITIZE_NUMBER_INT);

        $habilidade = new \Src\Model\Habilidade($id);
        
        $habilidade->nome = $nome;
        $habilidade->forca = $forca;
        $habilidade->nivel = $nivel;

        $habilidade->update();

        header("Location: " . URL . "/fichas/$ficha_id");
    }

    public function new($url){
        $ficha_id = $url["ficha_id"];
        $nome = filter_var($url['nome'], FILTER_SANITIZE_STRING);
        $forca = filter_var($url['forca'], FILTER_SANITIZE_NUMBER_INT);
        $nivel = filter_var($url['nivel'], FILTER_SANITIZE_NUMBER_INT);

        \Src\Model\Habilidade::create($ficha_id, $nome, $forca, $nivel);

        header("Location: " . URL . "/fichas/$ficha_id");
    }

    public function delete($url){
        $ficha_id = $url["ficha_id"];
        $habilidade = new \Src\Model\Habilidade($url["habilidade_id"]);

        $habilidade->delete();
        
        header("Location: " . URL . "/fichas/$ficha_id");
    }
}