<?php

namespace Src\Controller;

use PDO;

class item {

    public $dados = array(
        "url" => URL
    );

    public function show($url) {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $pdo = \Src\Lib\Database::connection();

        session_start();
        $id = $_SESSION["id"];
        $ficha_id = $url["ficha_id"];

        $result = $pdo->query("select id, cod_aventura from ficha where id = {$ficha_id}", PDO::FETCH_ASSOC);

        if($result->rowCount() == 0){
            header("Location: " . URL . "/aventuras");
            die();
        }

        foreach($result as $ficha){
            $this->dados["ficha"] = new \Src\Model\Ficha($url["ficha_id"]);
            $this->dados["item"] = new \Src\Model\Item($url["item_id"]);
            $this->dados["aventura"] = new \Src\Model\Aventura($ficha["cod_aventura"]);
            $mestre = $pdo->query("select id from aventura_usuario where cod_usuario = {$id} and cod_aventura = {$ficha['cod_aventura']} and mestre = 1", PDO::FETCH_ASSOC);
            if($mestre->rowCount()){
                $this->dados["mestre"] = 1;
            }
        }

        $this->dados["user"] = \Src\Lib\Dashboard::queryUser();
        $this->dados["aven"] = \Src\Lib\Dashboard::queryAventuras();
        echo $ambiente->render("item.html", $this->dados);
    }

    public function showEditar($url) {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $this->dados["ficha"] = $url["ficha_id"];
        $this->dados["item"] = new \Src\Model\Item($url["item_id"]);

        echo $ambiente->render("itemEditar.html", $this->dados);
    }

    public function showNovo($url) {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $this->dados["ficha"] = $url["ficha_id"];

        echo $ambiente->render("itemCriar.html", $this->dados);
    }

    public function salvar($url){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $ficha_id = $url["ficha_id"];
        $nome = filter_var($url['name'], FILTER_SANITIZE_STRING);
        $quantidade = filter_var($url['qtd'], FILTER_SANITIZE_NUMBER_INT);
        $preco = $url['preco'];

        if(isset($url["imagem"])){
            $img_path = $url["imagem"];
        }

        if(!empty($_FILES["file"]["name"])){
            $file = $_FILES["file"];
            $extensao = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

            if($file["error"]){
                $this->dados["alert"] = "Falha ao enviar arquivo";
        
                $this->dados["ficha"] = $url["ficha_id"];
                echo $ambiente->render("itemCriar.html", $this->dados);
                die();
            }

            if($file["size"] > 4194304){
                $this->dados["alert"] = "Arquivo maior que 4MB";
                
                $this->dados["ficha"] = $url["ficha_id"];
                echo $ambiente->render("itemCriar.html", $this->dados);
                die();
            }

            if($extensao != "jpg" && $extensao != "png"){
                $this->dados["alert"] = "Tipo de arquivo não aceito";
        
                $this->dados["ficha"] = $url["ficha_id"];
                echo $ambiente->render("aventuraCriar.html", $this->dados);
                die();
            }

            $img_path = uniqid() . "." . $extensao;
            move_uploaded_file($file["tmp_name"], "Src/View/Images/Items/" . $img_path);
        }

        $item = new \Src\Model\Item($url["item_id"]);

        $item->nome = $nome;
        $item->quantidade = $quantidade;

        if(empty($preco)){
            $item->preco = null;
        }
        else{
            $item->preco = $preco;
        }
        
        if($img_path != "none"){
            $item->img_path = $img_path;
        }

        $item->update();

        header("Location: " . URL . "/fichas/$ficha_id");
    }

    public function novo($url){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $ficha_id = $url["ficha_id"];
        $nome = filter_var($url['name'], FILTER_SANITIZE_STRING);
        $quantidade = filter_var($url['qtd'], FILTER_SANITIZE_NUMBER_INT);
        $preco = $url['preco'];

        if(isset($url["imagem"])){
            $img_path = $url["imagem"];
        }

        if(!empty($_FILES["file"]["name"])){
            $file = $_FILES["file"];
            $extensao = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

            if($file["error"]){
                $this->dados["alert"] = "Falha ao enviar arquivo";
        
                $this->dados["ficha"] = $url["ficha_id"];
                echo $ambiente->render("itemCriar.html", $this->dados);
                die();
            }

            if($file["size"] > 4194304){
                $this->dados["alert"] = "Arquivo maior que 4MB";
                
                $this->dados["ficha"] = $url["ficha_id"];
                echo $ambiente->render("itemCriar.html", $this->dados);
                die();
            }

            if($extensao != "jpg" && $extensao != "png"){
                $this->dados["alert"] = "Tipo de arquivo não aceito";
        
                $this->dados["ficha"] = $url["ficha_id"];
                echo $ambiente->render("aventuraCriar.html", $this->dados);
                die();
            }

            $img_path = uniqid() . "." . $extensao;
            move_uploaded_file($file["tmp_name"], "Src/View/Images/Items/" . $img_path);
        }

        \Src\Model\Item::create($ficha_id, $nome, $img_path, $quantidade, $preco);
        
        header("Location: " . URL . "/fichas/$ficha_id");
    }

    public function excluir($url){
        $ficha_id = $url["ficha_id"];
        $item = new \Src\Model\Item($url["item_id"]);

        $item->delete();
        
        header("Location: " . URL . "/fichas/$ficha_id");
    }
}