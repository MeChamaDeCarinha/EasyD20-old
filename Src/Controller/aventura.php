<?php

namespace Src\Controller;

use PDO;

class aventura {
    public $dados = array(
        "url" => URL,
    );

    public function loadAventuras() {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));
        $pdo = \Src\Lib\Database::connection();

        session_start();
        $player_id = $_SESSION["id"];
        if(isset($_SESSION["splash"])){
            unset($_SESSION["splash"]);
            $this->dados["splash"] = 1;
        }

        $result = $pdo->query("select cod_aventura from aventura_usuario where cod_usuario = {$player_id} and banido = 0", PDO::FETCH_ASSOC);

        $aventuras = array();

        foreach($result as $aventura){
            $aventuras[] = new \Src\Model\Aventura($aventura["cod_aventura"]);
        }

        $this->dados["aventuras"] = $aventuras;

        $this->dados["user"] = \Src\Lib\Dashboard::queryUser();
        $this->dados["aven"] = \Src\Lib\Dashboard::queryAventuras();
        echo $ambiente->render("aventuras.html", $this->dados);
    }

    public function loadCriar() {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        echo $ambiente->render("aventuraCriar.html", $this->dados);
    }

    public function loadEntrar() {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        echo $ambiente->render("aventuraEntrar.html", $this->dados);
    }

    public function selectAventura($url){
        $pdo = \Src\Lib\Database::connection();

        session_start();
        $player_id = $_SESSION["id"];
        $aventura_id = $url["id"];

        $result = $pdo->query("select mestre from aventura_usuario where cod_usuario = {$player_id} and cod_aventura = {$aventura_id}", PDO::FETCH_ASSOC);

        if($result->rowCount() == 0){
            header("Location: " . URL . "/aventuras");
            die();
        }

        $_SESSION["aventura_id"] = $aventura_id;
        foreach($result as $mestre){
            $_SESSION["mestre"] = $mestre["mestre"];
        }
        

        header("Location: " . URL . "/fichas");
    }

    public function criar($form){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));
        
        $pdo = \Src\Lib\Database::connection();

        session_start();
        $player_id = $_SESSION["id"];

        $usuario = new \Src\Model\Usuario($player_id);
        if(!$usuario->verificado){
            $this->dados["alert"] = "Somente para usuários verificados";
        
            echo $ambiente->render("aventuraCriar.html", $this->dados);
            die();
        }

        $aventura_nome = filter_var($form["name"], FILTER_SANITIZE_STRING);
        $aventura_livro = filter_var($form["livro"], FILTER_SANITIZE_STRING);
        $aventura_descricao = filter_var($form["descricao"], FILTER_SANITIZE_STRING);
        if(isset($form["imagem"])){
            $aventura_imagem = $form["imagem"];
        }

        if(!empty($_FILES["file"]["name"])){
            $file = $_FILES["file"];
            $extensao = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

            if($file["error"]){
                $this->dados["alert"] = "Falha ao enviar arquivo";
        
                echo $ambiente->render("aventuraCriar.html", $this->dados);
                die();
            }

            if($file["size"] > 4194304){
                $this->dados["alert"] = "Arquivo maior que 4MB";
        
                echo $ambiente->render("aventuraCriar.html", $this->dados);
                die();
            }

            if($extensao != "jpg" && $extensao != "png"){
                $this->dados["alert"] = "Tipo de arquivo não aceito";
        
                echo $ambiente->render("aventuraCriar.html", $this->dados);
                die();
            }

            $aventura_imagem = uniqid() . "." . $extensao;
            move_uploaded_file($file["tmp_name"], "Src/View/Images/Cards/" . $aventura_imagem);
        }

        $created_id = \Src\Model\Aventura::create($aventura_nome, $aventura_livro, $aventura_descricao, $aventura_imagem);

        $pdo->query("insert into aventura_usuario(cod_usuario, cod_aventura, mestre) values ({$player_id}, {$created_id}, 1)");

        \Src\Model\Ficha::create($player_id, $created_id);

        header("Location: " . URL . "/aventuras");
    }

    public function entrar($form){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $pdo = \Src\Lib\Database::connection();

        session_start();
        $player_id = $_SESSION["id"];
        $aventura_cod = filter_var($form["codigo"], FILTER_SANITIZE_NUMBER_INT);

        $this->dados["aventura_cod"] = $aventura_cod;


        $result = $pdo->query("select id, publica from aventura where cod = '{$aventura_cod}'", PDO::FETCH_ASSOC);

        if($result->rowCount() == 0){
            $this->dados["alert"] = "Código de aventura não existe";

            echo $ambiente->render("aventuraEntrar.html", $this->dados);
            die();
        }

        foreach($result as $aventura){
            if(!$aventura["publica"]){
                $this->dados["alert"] = "Essa aventura não é pública";

                echo $ambiente->render("aventuraEntrar.html", $this->dados);
                die();
            }
            $aventura_id = $aventura["id"];
        }

        
        $result = $pdo->query("select id, banido from aventura_usuario where cod_usuario = {$player_id} and cod_aventura = {$aventura_id}",  PDO::FETCH_ASSOC);

        if($result->rowCount() != 0){
            foreach($result as $aventura_usuario){
                if($aventura_usuario["banido"]){
                    $this->dados["alert"] = "Você está banido dessa aventura";
    
                    echo $ambiente->render("aventuraEntrar.html", $this->dados);
                    die();
                }
            }

            $this->dados["alert"] = "Você já participa dessa aventura";

            echo $ambiente->render("aventuraEntrar.html", $this->dados);
            die();
        }

        $pdo->query("insert into aventura_usuario(cod_usuario, cod_aventura) values ({$player_id}, {$aventura_id})");

        $pdo->query("insert into ficha(cod_usuario, cod_aventura) values ({$player_id}, {$aventura_id})");

        header("Location: " . URL . "/aventuras");
    }

    public function show($url){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $pdo = \Src\Lib\Database::connection();

        session_start();

        $result = $pdo->query("select cod_usuario, mestre from aventura_usuario where cod_aventura = {$url["id"]} and banido = 0",  PDO::FETCH_ASSOC);

        $usuarios = array();

        foreach($result as $usuario){
            $usuarios[] = new \Src\Model\Usuario($usuario["cod_usuario"]);
            if($usuario["mestre"]){
                $this->dados["mestre_id"] = $usuario["cod_usuario"];
            }
        }

        $this->dados["user_id"] = $_SESSION["id"];

        $this->dados["user"] = \Src\Lib\Dashboard::queryUser();
        $this->dados["aven"] = \Src\Lib\Dashboard::queryAventuras();

        $this->dados["usuarios"] = $usuarios;
        $this->dados["aventura"] = new \Src\Model\Aventura($url["id"]);
        echo $ambiente->render("aventura.html", $this->dados);
    }

    public function save($form){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $aventura_nome = filter_var($form["name"], FILTER_SANITIZE_STRING);
        $aventura_livro = filter_var($form["livro"], FILTER_SANITIZE_STRING);
        $aventura_descricao = filter_var($form["descricao"], FILTER_SANITIZE_STRING);

        if(isset($form["imagem"])){
            $aventura_imagem = $form["imagem"];
        }

        if(!empty($_FILES["file"]["name"])){
            $file = $_FILES["file"];
            $extensao = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

            if($file["error"]){
                $this->dados["alert"] = "Falha ao enviar arquivo";
        
                echo $ambiente->render("aventuraCriar.html", $this->dados);
                die();
            }

            if($file["size"] > 4194304){
                $this->dados["alert"] = "Arquivo maior que 4MB";
        
                echo $ambiente->render("aventuraCriar.html", $this->dados);
                die();
            }

            if($extensao != "jpg" && $extensao != "png"){
                $this->dados["alert"] = "Tipo de arquivo não aceito";
        
                echo $ambiente->render("aventuraCriar.html", $this->dados);
                die();
            }

            $aventura_imagem = uniqid() . "." . $extensao;
            move_uploaded_file($file["tmp_name"], "Src/View/Images/Cards/" . $aventura_imagem);
        }

        $aventura = new \Src\Model\Aventura($form["id"]);

        $aventura->nome = $aventura_nome;
        $aventura->livro = $aventura_livro;
        $aventura->descricao = $aventura_descricao;

        if($aventura_imagem != "none"){
            $aventura->imagem = $aventura_imagem;
        }

        $aventura->update();

        header("Location: " . URL . "/aventuras/{$aventura->id}");
    }

    public function showEditar($url){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $this->dados["aventura"] = new \Src\Model\Aventura($url["id"]);
        echo $ambiente->render("aventuraEditar.html", $this->dados);
    }

    public function delete($url){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $aventura = new \Src\Model\Aventura($url["id"]);
        $aventura_nome = filter_var($url["name"], FILTER_SANITIZE_STRING);

        if($aventura->nome != $aventura_nome){
            $this->dados["alert"] = "O nome não coincide";
            $this->dados["aventura"] = $aventura;
            echo $ambiente->render("aventuraExcluir.html", $this->dados);
            die();
        }

        $aventura->delete();

        header("Location: " . URL . "/aventuras");
    }

    public function showExcluir($url){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $this->dados["aventura"] = new \Src\Model\Aventura($url["id"]);
        echo $ambiente->render("aventuraExcluir.html", $this->dados);
    }

    public function expulsar($url){
        $pdo = \Src\Lib\Database::connection();

        $aventura_id = $url["id"];
        $usuario_id = $url["jogador_id"];

        $pdo->query("delete from aventura_usuario where cod_usuario = {$usuario_id} and cod_aventura = {$aventura_id}");

        $result = $pdo->query("select id from ficha where cod_usuario = {$usuario_id} and cod_aventura = {$aventura_id}");
        foreach($result as $ficha){
            $ficha = new \Src\Model\Ficha($ficha["id"]);
            $ficha->delete();
        }
        
        header("Location: " . URL . "/aventuras/{$aventura_id}");
    }

    public function showExpulsar($url){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $this->dados["usuario"] = new \Src\Model\Usuario($url["jogador_id"]);
        $this->dados["aventura"] = new \Src\Model\Aventura($url["id"]);
        echo $ambiente->render("aventuraExpulsar.html", $this->dados);
    }

    public function saveConfig($url){
        $pdo = \Src\Lib\Database::connection();

        $aventura = new \Src\Model\Aventura($url["id"]);
        if(isset($url["publica"])){
            $aventura->publica = 1;
        }
        else{
            $aventura->publica = 0;
        }
        if(isset($url["editar"])){
            $aventura->editar = 1;
        }
        else{
            $aventura->editar = 0;
        }
        if(isset($url["ficha_mestre"])){
            $aventura->ficha_mestre = 1;
        }
        else{
            $aventura->ficha_mestre = 0;
        }

        $aventura->update();
        
        header("Location: " . URL . "/aventuras/{$aventura->id}");
    }

    public function showConfigurar($url){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $this->dados["aventura"] = new \Src\Model\Aventura($url["id"]);
        echo $ambiente->render("aventuraConfigurar.html", $this->dados);
    }

    public function banir($url){
        $pdo = \Src\Lib\Database::connection();

        $aventura_id = $url["id"];
        $usuario_id = $url["jogador_id"];

        $pdo->query("update aventura_usuario set banido = 1 where cod_usuario = {$usuario_id} and cod_aventura = {$aventura_id}");

        $result = $pdo->query("select id from ficha where cod_usuario = {$usuario_id} and cod_aventura = {$aventura_id}");
        foreach($result as $ficha){
            $ficha = new \Src\Model\Ficha($ficha["id"]);
            $ficha->delete();
        }
        
        header("Location: " . URL . "/aventuras/{$aventura_id}");
    }

    public function showBanir($url){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $this->dados["usuario"] = new \Src\Model\Usuario($url["jogador_id"]);
        $this->dados["aventura"] = new \Src\Model\Aventura($url["id"]);
        echo $ambiente->render("aventuraBanir.html", $this->dados);
    }

    public function mestre($url){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $pdo = \Src\Lib\Database::connection();
        
        $aventura = new \Src\Model\Aventura($url["id"]);
        $usuario = new \Src\Model\Usuario($url["jogador_id"]);
        $jogador_nome = filter_var($url["name"], FILTER_SANITIZE_STRING);

        if($usuario->nome != $jogador_nome){
            $this->dados["alert"] = "O nome não coincide";
            $this->dados["aventura"] = $aventura;
            $this->dados["usuario"] = $usuario;
            echo $ambiente->render("aventuraMestre.html", $this->dados);
            die();
        }

        if(!$usuario->verificado){
            $this->dados["alert"] = "Usuário não verificado";

            $this->dados["aventura"] = $aventura;
            $this->dados["usuario"] = $usuario;
            echo $ambiente->render("aventuraMestre.html", $this->dados);
            die();
        }

        $pdo->query("update aventura_usuario set mestre = 0 where cod_aventura = {$aventura->id} and mestre = 1");
        $pdo->query("update aventura_usuario set mestre = 1 where cod_aventura = {$aventura->id} and cod_usuario = {$usuario->id}");
        
        header("Location: " . URL . "/aventuras/{$aventura->id}");
    }

    public function showMestre($url){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $this->dados["usuario"] = new \Src\Model\Usuario($url["jogador_id"]);
        $this->dados["aventura"] = new \Src\Model\Aventura($url["id"]);
        echo $ambiente->render("aventuraMestre.html", $this->dados);
    }

    public function sair($url){
        $pdo = \Src\Lib\Database::connection();

        session_start();

        $aventura_id = $url["id"];
        $id = $_SESSION["id"];

        $pdo->query("delete from aventura_usuario where cod_usuario = {$id} and cod_aventura = {$aventura_id}");

        $result = $pdo->query("select id from ficha where cod_usuario = {$id} and cod_aventura = {$aventura_id}");
        foreach($result as $ficha){
            $ficha = new \Src\Model\Ficha($ficha["id"]);
            $ficha->delete();
        }
        
        header("Location: " . URL . "/aventuras");
    }

    public function showSair($url){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $this->dados["aventura"] = new \Src\Model\Aventura($url["id"]);
        echo $ambiente->render("aventuraSair.html", $this->dados);
    }
}