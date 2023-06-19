<?php

namespace Src\Controller;

use PDO;

class mapa {
    public $dados = array(
        "url" => URL,
    );

    public function loadMapas() {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $pdo = \Src\Lib\Database::connection();

        \Src\Lib\Sec::verifyUser();
        
        if(!isset($_SESSION["aventura_id"])){
            $this->dados["alert"] = "Nenhuma aventura foi selecionada";

            $this->dados["user"] = \Src\Lib\Dashboard::queryUser();
            $this->dados["aven"] = \Src\Lib\Dashboard::queryAventuras();
            echo $ambiente->render("mapas.html", $this->dados);
            die();
        }

        $aventura_id = $_SESSION["aventura_id"];

        // \Src\Lib\Sec::verifyAventura($aventura_id);

        $this->dados["mestre"] = $_SESSION["mestre"];

        $result = $pdo->query("select id from mapa where cod_aventura = {$aventura_id}", PDO::FETCH_ASSOC);

        if($result->rowCount() == 0){
            $this->dados["alert"] = "Nenhum mapa disponível";
        
            $this->dados["user"] = \Src\Lib\Dashboard::queryUser();
            $this->dados["aven"] = \Src\Lib\Dashboard::queryAventuras();
            echo $ambiente->render("mapas.html", $this->dados);
            die();
        }

        foreach($result as $mapa){
            $mapas[] = new \Src\Model\Mapa($mapa["id"]);
        }

        $this->dados["mapas"] = $mapas;

        $this->dados["user"] = \Src\Lib\Dashboard::queryUser();
        $this->dados["aven"] = \Src\Lib\Dashboard::queryAventuras();
        echo $ambiente->render("mapas.html", $this->dados);
    }

    public function show($url){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $pdo = \Src\Lib\Database::connection();

        $mapa = new \Src\Model\Mapa($url["id"]);   

        \Src\Lib\Sec::verifyUser();

        $result = $pdo->query("select cod_usuario from aventura_usuario where cod_aventura = {$mapa->cod_aventura} and mestre = 1",  PDO::FETCH_ASSOC);

        if($result->rowCount() == 0){
            header("Location: " . URL . "/erro/404");
        }

        foreach($result as $usuario){
            $this->dados["mestre_id"] = $usuario["cod_usuario"];
        }

        $this->dados["user_id"] = $_SESSION["id"];

        $this->dados["mapa"] = $mapa;

        $this->dados["user"] = \Src\Lib\Dashboard::queryUser();
        $this->dados["aven"] = \Src\Lib\Dashboard::queryAventuras();
        echo $ambiente->render("mapa.html", $this->dados);
    }

    public function showNovo(){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));
        echo $ambiente->render("mapaCriar.html", $this->dados);
    }

    public function showEditar($url){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $this->dados["mapa"] = new \Src\Model\Mapa($url["id"]);
        echo $ambiente->render("mapaEditar.html", $this->dados);
    }

    public function new($form){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        \Src\Lib\Sec::verifyUser();
        
        $aventura_id = $_SESSION["aventura_id"];
        $nome = filter_var($form["name"], FILTER_SANITIZE_STRING);
        $descricao = filter_var($form["descricao"], FILTER_SANITIZE_STRING);

        if(isset($form["imagem"])){
            $img_path = $form["imagem"];
        }

        if(!empty($_FILES["file"]["name"])){
            $file = $_FILES["file"];
            $extensao = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

            if($file["error"]){
                $this->dados["alert"] = "Falha ao enviar arquivo";
        
                echo $ambiente->render("mapaCriar.html", $this->dados);
                die();
            }

            if($file["size"] > 4194304){
                $this->dados["alert"] = "Arquivo maior que 4MB";
        
                echo $ambiente->render("mapaCriar.html", $this->dados);
                die();
            }

            if($extensao != "jpg" && $extensao != "jpeg" && $extensao != "png"){
                $this->dados["alert"] = "Tipo de arquivo não aceito";
        
                echo $ambiente->render("mapaCriar.html", $this->dados);
                die();
            }

            $img_path = uniqid() . "." . $extensao;
            move_uploaded_file($file["tmp_name"], "Src/View/Images/Maps/" . $img_path);
        }

        \Src\Model\Mapa::create($aventura_id, $nome, $descricao, $img_path);

        header("Location: " . URL . "/mapas");
    }

    public function save($form){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $nome = filter_var($form["name"], FILTER_SANITIZE_STRING);
        $descricao = filter_var($form["descricao"], FILTER_SANITIZE_STRING);

        $mapa = new \Src\Model\Mapa($form["id"]);

        \Src\Lib\Sec::verifyUser();
        
        if(!empty($_FILES["file"]["name"])){
            $file = $_FILES["file"];
            $extensao = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

            if($file["error"]){
                $this->dados["alert"] = "Falha ao enviar arquivo";
        
                $this->dados["mapa"] = $mapa;
                echo $ambiente->render("mapaEditar.html", $this->dados);
                die();
            }

            if($file["size"] > 4194304){
                $this->dados["alert"] = "Arquivo maior que 4MB";
                
                $this->dados["mapa"] = $mapa;
                echo $ambiente->render("mapaEditar.html", $this->dados);
                die();
            }

            if($extensao != "jpg" && $extensao != "jpeg" && $extensao != "png"){
                $this->dados["alert"] = "Tipo de arquivo não aceito";
        
                $this->dados["mapa"] = $mapa;
                echo $ambiente->render("mapaEditar.html", $this->dados);
                die();
            }

            $img_path = uniqid() . "." . $extensao;
            move_uploaded_file($file["tmp_name"], "Src/View/Images/Maps/" . $img_path);
        }

        $mapa->nome = $nome;
        $mapa->descricao = $descricao;
        
        if($img_path != "none"){
            $mapa->img_path = $img_path;
        }
        
        $mapa->update();

        header("Location: " . URL . "/mapas/{$mapa->id}");
    }

    public function turnHidden($url){
        $mapa = new \Src\Model\Mapa($url["id"]);

        \Src\Lib\Sec::verifyUser();
        
        $mapa->hidden = !$mapa->hidden;

        $mapa->update();

        header("Location: " . URL . "/mapas/{$mapa->id}");
    }

    public function delete($url) {
        $mapa = new \Src\Model\Mapa($url["id"]);

        \Src\Lib\Sec::verifyUser();

        $mapa->delete();

        header("Location: " . URL . "/mapas");
    }
}
