<?php

namespace Src\Controller;

use PDO;

class item {

    public $dados = array(
        "url" => URL
    );

    public function show($url) {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $ficha = new \Src\Model\Ficha($url["ficha_id"]);
        $item = new \Src\Model\Item($url["item_id"]);

        \Src\Lib\Sec::verifyUser();
        
        $this->dados["ficha"] = $ficha;
        $this->dados["item"] = $item;
        $this->dados["mestre"] = $_SESSION["mestre"];
        $this->dados["user"] = \Src\Lib\Dashboard::queryUser();
        $this->dados["aven"] = \Src\Lib\Dashboard::queryAventuras();
        echo $ambiente->render("item.html", $this->dados);
    }

    public function showEditar($url) {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $ficha_id = $url["ficha_id"];
        $ficha = new \Src\Model\Ficha($ficha_id);

        \Src\Lib\Sec::verifyUser();

        $this->dados["ficha"] = $ficha_id;
        $this->dados["item"] = new \Src\Model\Item($url["item_id"]);
        echo $ambiente->render("itemEditar.html", $this->dados);
    }

    public function showNovo($url) {
        \Src\Lib\Sec::verifyUser();

        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $this->dados["ficha"] = $url["ficha_id"];

        echo $ambiente->render("itemCriar.html", $this->dados);
    }

    public function save($url){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $ficha_id = $url["ficha_id"];
        $nome = filter_var($url['name'], FILTER_SANITIZE_STRING);
        $quantidade = filter_var($url['qtd'], FILTER_SANITIZE_NUMBER_INT);
        $preco = $url['preco'];

        \Src\Lib\Sec::verifyUser();

        if(isset($url["imagem"])){
            $img_path = $url["imagem"];
        }

        if(!empty($_FILES["file"]["name"])){
            $file = $_FILES["file"];
            $extensao = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

            if($file["error"]){
                $this->dados["alert"] = "Falha ao enviar arquivo";
        
                $this->dados["ficha"] = $url["ficha_id"];
                $this->dados["item"] = new \Src\Model\Item($url["item_id"]);
                echo $ambiente->render("itemEditar.html", $this->dados);
                die();
            }

            if($file["size"] > 4194304){
                $this->dados["alert"] = "Arquivo maior que 4MB";
                
                $this->dados["ficha"] = $url["ficha_id"];
                $this->dados["item"] = new \Src\Model\Item($url["item_id"]);
                echo $ambiente->render("itemEditar.html", $this->dados);
                die();
            }

            if($extensao != "jpg" && $extensao != "jpeg" && $extensao != "png"){
                $this->dados["alert"] = "Tipo de arquivo não aceito";
        
                $this->dados["ficha"] = $url["ficha_id"];
                $this->dados["item"] = new \Src\Model\Item($url["item_id"]);
                echo $ambiente->render("itemEditar.html", $this->dados);
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

    public function new($url){
        \Src\Lib\Sec::verifyUser();

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

            if($extensao != "jpg" && $extensao != "jpeg" && $extensao != "png"){
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

    public function delete($url){
        \Src\Lib\Sec::verifyUser();
        
        $ficha_id = $url["ficha_id"];
        $item = new \Src\Model\Item($url["item_id"]);

        $item->delete();
        
        header("Location: " . URL . "/fichas/$ficha_id");
    }
}