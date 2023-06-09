<?php

namespace Src\Controller;

use PDO;

class atributo {

    public $dados = array(
        "url" => URL
    );

    public function showEditar($url) {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $ficha_id = $url["ficha_id"];
        $atributo_id = $url["atributo_id"];

        $this->dados["ficha"] = $ficha_id;
        $this->dados["atributo"] = new \Src\Model\Atributo($atributo_id);

        echo $ambiente->render("atributoEditar.html", $this->dados);
    }

    public function showNovo($url) {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $this->dados["ficha"] = $url["ficha_id"];

        echo $ambiente->render("atributoCriar.html", $this->dados);
    }

    public function salvar($url){
        $ficha_id = $url["ficha_id"];
        $id = $url["atributo_id"];
        $nome = filter_var($url['nome'], FILTER_SANITIZE_STRING);
        $valor = filter_var($url['valor'], FILTER_SANITIZE_NUMBER_INT);

        $atributo = new \Src\Model\Atributo($id);
        
        $atributo->nome = $nome;
        $atributo->valor = $valor;

        $atributo->update();

        header("Location: " . URL . "/fichas/$ficha_id");
    }

    public function novo($url){
        $ficha_id = $url["ficha_id"];
        $nome = filter_var($url['nome'], FILTER_SANITIZE_STRING);
        $valor = filter_var($url['valor'], FILTER_SANITIZE_NUMBER_INT);

        \Src\Model\Atributo::create($ficha_id, $nome, $valor);
        
        header("Location: " . URL . "/fichas/$ficha_id");
    }

    public function excluir($url){
        $ficha_id = $url["ficha_id"];
        $atributo = new \Src\Model\Atributo($url["atributo_id"]);

        $atributo->delete();
        
        header("Location: " . URL . "/fichas/$ficha_id");
    }
}