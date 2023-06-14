<?php

namespace Src\Controller;

use PDO;

class recuperar {
    public $dados = array(
        "url" => URL,
    );

    public function showVerificar() {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $pdo = \Src\Lib\Database::connection();

        if(!isset($_SESSION)){
            session_start();
        }

        if(isset($_SESSION["id"])){
            $usuario = new \Src\Model\Usuario($_SESSION["id"]);
            $_SESSION["codigoRecuperação"] = \Src\Lib\Mail::passRecovery($usuario);
        }
        else if(isset($_SESSION["mail"])) {
            $email = $_SESSION["mail"];

            $result = $pdo->query("select id from usuario where email = '$email'", PDO::FETCH_ASSOC);

            if($result->rowCount() == 0){
                $usuario["email"] = $email;                
                $usuario["img_path"] = "Default.png"; 
            }
            else {
                foreach($result as $user){
                    $usuario = new \Src\Model\Usuario($user["id"]);
                    $_SESSION["codigoRecuperação"] = \Src\Lib\Mail::passRecovery($usuario);
                }
            }
            
        }
        else {
            header("Location: " . URL . "/recuperar/usuario");
            die();
        }

        $this->dados["usuario"] = $usuario;
        echo $ambiente->render("recuperar.html", $this->dados);
    }

    public function verificar($dados){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $pdo = \Src\Lib\Database::connection();

        if(!isset($_SESSION)){
            session_start();
        }

        if(isset($_SESSION["id"])){
            $usuario = new \Src\Model\Usuario($_SESSION["id"]);
        }
        else if(isset($_SESSION["mail"])) {
            $email = $_SESSION["mail"];

            $result = $pdo->query("select id from usuario where email = '$email'", PDO::FETCH_ASSOC);

            if($result->rowCount() == 0){
                $usuario["email"] = $email;                
                $usuario["img_path"] = "Default.png"; 

                $this->dados["alert"] = "Código inválido";

                $this->dados["usuario"] = $usuario;
                echo $ambiente->render("recuperar.html", $this->dados);
                die();
            }
            else {
                foreach($result as $user){
                    $usuario = new \Src\Model\Usuario($user["id"]);
                }
            }
            
        }

        $codigo = filter_var($dados['codigo'], FILTER_SANITIZE_STRING);

        if($codigo != $_SESSION["codigoRecuperação"]){
            $this->dados["alert"] = "Código inválido";

            $this->dados["usuario"] = $usuario;
            echo $ambiente->render("recuperar.html", $this->dados);
            die();
        }

        header("Location: " . URL . "/recuperar/senha");
    }

    public function showRecuperar(){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $pdo = \Src\Lib\Database::connection();

        if(!isset($_SESSION)){
            session_start();
        }

        if(isset($_SESSION["id"])){
            $usuario = new \Src\Model\Usuario($_SESSION["id"]);
        }
        else if(isset($_SESSION["mail"])) {
            $email = $_SESSION["mail"];
            $result = $pdo->query("select id from usuario where email = '{$email}'", PDO::FETCH_ASSOC);
            
            foreach($result as $user){
                $usuario = new \Src\Model\Usuario($user["id"]);
            }
        }

        $this->dados["usuario"] = $usuario;
        echo $ambiente->render("recuperarSenha.html", $this->dados);
    }

    public function recuperar($dados){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $pdo = \Src\Lib\Database::connection();

        if(!isset($_SESSION)){
            session_start();
        }

        if(isset($_SESSION["id"])){
            $usuario = new \Src\Model\Usuario($_SESSION["id"]);
        }
        else if(isset($_SESSION["mail"])) {
            $email = $_SESSION["mail"];
            $result = $pdo->query("select id from usuario where email = '{$email}'", PDO::FETCH_ASSOC);
            
            foreach($result as $user){
                $usuario = new \Src\Model\Usuario($user["id"]);
            }
        }

        $this->dados["usuario"] = $usuario; 
        
        $senha = filter_var($dados['password'], FILTER_SANITIZE_STRING);
        $senha2 = filter_var($dados['passverify'], FILTER_SANITIZE_STRING);


        if(strlen($senha) < 8){
            $this->dados["alert"] = "A senha deve conter pelo menos 8 caracteres";

            echo $ambiente->render("recuperarSenha.html", $this->dados);
            die();
        }

        if($senha != $senha2){
            $this->dados["alert"] = "As senhas não coincidem";

            echo $ambiente->render("recuperarSenha.html", $this->dados);
            die();
        }

        $usuario->senha = password_hash($senha, PASSWORD_DEFAULT);
        $usuario->update();

        $_SESSION["id"] = $usuario->id;
        $_SESSION['splash'] = 1;

        header("Location: " . URL . "/aventuras");
    }

    public function showUsuario(){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        echo $ambiente->render("recuperarUsuario.html", $this->dados);
    }

    public function usuarioVerificar($form){
        if(!isset($_SESSION)){
            session_start();
        }

        $_SESSION["mail"] = $form['email'];

        header("Location: " . URL . "/recuperar");
    }

}