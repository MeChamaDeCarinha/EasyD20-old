<?php

namespace Src\Controller;

use PDO;

class user {

    public $dados = array(
        "url" => URL,
    );

    public function showLogin() {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        if(!isset($_SESSION)){
            session_start();

            if(isset($_SESSION["alert"])){
                $this->dados["alert"] = $_SESSION["alert"];
            }

            session_destroy();
        }
        
        echo $ambiente->render("login.html", $this->dados);
    }

    public function showSignup() {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        if(!isset($_SESSION)){
            session_start();
            session_destroy();
        }   

        echo $ambiente->render("signup.html", $this->dados);
    }

    public function show() {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        \Src\Lib\Sec::verifyUser();

        $user = new \Src\Model\Usuario($_SESSION["id"]);

        $this->dados["usuario"] = $user; 

        $this->dados["user"] = \Src\Lib\Dashboard::queryUser();
        $this->dados["aven"] = \Src\Lib\Dashboard::queryAventuras();
        echo $ambiente->render("perfil.html", $this->dados);
    }

    public function showVerificar() {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        \Src\Lib\Sec::verifyUser();

        $usuario = new \Src\Model\Usuario($_SESSION["id"]);

        $_SESSION["codigoVerificação"] = \Src\Lib\Mail::emailVerify($usuario);

        $this->dados["usuario"] = $usuario;
        echo $ambiente->render("perfilVerificar.html", $this->dados);
    }

    public function showSenha() {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        \Src\Lib\Sec::verifyUser();
        $user = new \Src\Model\Usuario($_SESSION["id"]);

        $this->dados["usuario"] = $user; 

        echo $ambiente->render("perfilMudarSenha.html", $this->dados);
    }

    public function showEditar() {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        \Src\Lib\Sec::verifyUser();
        $this->dados["usuario"] = new \Src\Model\Usuario($_SESSION["id"]); 

        echo $ambiente->render("perfilEditar.html", $this->dados);
    }

    public function showDelete(){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        \Src\Lib\Sec::verifyUser();

        $user = new \Src\Model\Usuario($_SESSION["id"]);

        $this->dados["usuario"] = $user; 
        echo $ambiente->render("perfilDeletar.html", $this->dados);
    }

    public function login($dados){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $pdo = \Src\Lib\Database::connection();

        $nome = filter_var($dados['name'], FILTER_SANITIZE_STRING);
        $senha = filter_var($dados['password'], FILTER_SANITIZE_STRING);

        $result = $pdo->query("select * from usuario where nome = '{$nome}' or email = '{$nome}'", PDO::FETCH_ASSOC);

        if($result->rowCount() != 0){

            foreach($result as $user){
                if(password_verify($senha, $user["senha"])){
                    if(!isset($_SESSION)){
                        session_start();
                    }

                    $_SESSION['id'] = $user['id'];
                    $_SESSION['splash'] = 1;

                    header("Location: " . URL . "/aventuras");
                }
                else {
                    $this->dados["name"] = $nome;
                    $this->dados["alert"] = "Login ou senha incorretos";

                    echo $ambiente->render("login.html", $this->dados);
                }
            }
        }
        else{
            $this->dados["name"] = $nome;
            $this->dados["alert"] = "Login ou senha incorretos";

            echo $ambiente->render("login.html", $this->dados);
        }
    }

    public function signup($dados){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $pdo = \Src\Lib\Database::connection();


        $nome = filter_var($dados['name'], FILTER_SANITIZE_STRING);
        $email = $dados['email'];
        $senha = filter_var($dados['password'], FILTER_SANITIZE_STRING);
        $senha2 = filter_var($dados['passverify'], FILTER_SANITIZE_STRING);


        $resultName = $pdo->query("select * from usuario where nome = '{$nome}'", PDO::FETCH_ASSOC);

        $resultEmail = $pdo->query("select * from usuario where email = '{$email}'", PDO::FETCH_ASSOC);

        foreach($resultName as $user){
            if($resultName->rowCount() != 0){
                $this->dados["name"] = $nome;
                $this->dados["email"] = $email;
                $this->dados["alert"] = "Esse nome já está em uso";
    
                echo $ambiente->render("signup.html", $this->dados);
                die();
            }
        }

        foreach($resultEmail as $user){
            if($resultEmail->rowCount() != 0){
                $this->dados["name"] = $nome;
                $this->dados["email"] = $email;
                $this->dados["alert"] = "Esse email já está em uso";

                echo $ambiente->render("signup.html", $this->dados);
                die();
            }
        }

        if(strlen($senha) < 8){
            $this->dados["name"] = $nome;
            $this->dados["email"] = $email;
            $this->dados["alert"] = "A senha deve conter pelo menos 8 caracteres";

            echo $ambiente->render("signup.html", $this->dados);
            die();
        }

        if($senha != $senha2){
            $this->dados["name"] = $nome;
            $this->dados["email"] = $email;
            $this->dados["alert"] = "As senhas não coincidem";

            echo $ambiente->render("signup.html", $this->dados);
            die();
        }

        $senha = password_hash($senha, PASSWORD_DEFAULT);
    
        $user_id = \Src\Model\Usuario::create($nome, $email, $senha);

        if(!isset($_SESSION)){
            session_start();
        }
        
        $_SESSION['id'] = $user_id;
        $_SESSION['splash'] = 1;

        header("Location: " . URL . "/aventuras");
    }

    public function save($dados){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        $pdo = \Src\Lib\Database::connection();

        \Src\Lib\Sec::verifyUser();

        $user = new \Src\Model\Usuario($_SESSION["id"]);
        $this->dados["usuario"] = $user;
    
        $nome = filter_var($dados['name'], FILTER_SANITIZE_STRING);
        $email = $dados['email'];
        $img_path = $dados['imagem'];

        $senha = filter_var($dados['password'], FILTER_SANITIZE_STRING);

        if(!password_verify($senha, $user->senha)){
            $this->dados["alert"] = "Senha incorreta";
        
            echo $ambiente->render("perfilEditar.html", $this->dados);
            die();
        }

        if(!empty($_FILES["file"]["name"])){
            $file = $_FILES["file"];
            $extensao = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

            if($file["error"]){
                $this->dados["alert"] = "Falha ao enviar arquivo";
        
                echo $ambiente->render("perfilEditar.html", $this->dados);
                die();
            }

            if($file["size"] > 4194304){
                $this->dados["alert"] = "Arquivo maior que 4MB";
        
                echo $ambiente->render("perfilEditar.html", $this->dados);
                die();
            }

            if($extensao != "jpg" && $extensao != "jpeg" && $extensao != "png"){
                $this->dados["alert"] = "Tipo de arquivo não aceito";
        
                echo $ambiente->render("perfilEditar.html", $this->dados);
                die();
            }

            $img_path = uniqid() . "." . $extensao;
            move_uploaded_file($file["tmp_name"], "Src/View/Images/Profiles/" . $img_path);
        } 

        $resultName = $pdo->query("select id from usuario where nome = '{$nome}'", PDO::FETCH_ASSOC);

        $resultEmail = $pdo->query("select id from usuario where email = '{$email}'", PDO::FETCH_ASSOC);

        foreach($resultName as $result){
            if($resultName->rowCount() != 0 && $result["id"] != $user->id){
                $this->dados["alert"] = "Esse nome já está em uso";
    
                echo $ambiente->render("perfilEditar.html", $this->dados);
                die();
            }
        }

        foreach($resultEmail as $result){
            if($resultEmail->rowCount() != 1 && $result["id"] != $user->id){
                $this->dados["alert"] = "Esse email já está em uso";
    
                echo $ambiente->render("perfilEditar.html", $this->dados);
                die();
            }
        }

        if($user->email != $email){
            $user->verificado = 0;
        }

        $user->nome = $nome;
        $user->email = $email;
        if($img_path != "none"){
            $user->img_path = $img_path;
        }

        $user->update();
        
        header("Location: " . URL . "/perfil");
    }

    public function verificar($dados){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        \Src\Lib\Sec::verifyUser();

        $usuario = new \Src\Model\Usuario($_SESSION["id"]);

        $codigo = filter_var($dados['codigo'], FILTER_SANITIZE_STRING);

        if($codigo != $_SESSION["codigoVerificação"]){
            $this->dados["alert"] = "Código inválido";

            $this->dados["usuario"] = $usuario;
            echo $ambiente->render("perfilVerificar.html", $this->dados);
            die();
        }

        $usuario->verificado = 1;
        $usuario->update();

        header("Location: " . URL . "/perfil");
    }

    public function changePass($dados){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        \Src\Lib\Sec::verifyUser();

        $usuario = new \Src\Model\Usuario($_SESSION["id"]);
        $this->dados["usuario"] = $usuario; 

        $atualSenha = filter_var($dados['pass'], FILTER_SANITIZE_STRING);
        
        $senha = filter_var($dados['password'], FILTER_SANITIZE_STRING);
        $senha2 = filter_var($dados['passverify'], FILTER_SANITIZE_STRING);


        if(!password_verify($atualSenha, $usuario->senha)){
            $this->dados["alert"] = "Senha incorreta";
        
            echo $ambiente->render("perfilMudarSenha.html", $this->dados);
            die();
        }


        if(strlen($senha) < 8){
            $this->dados["alert"] = "A senha deve conter pelo menos 8 caracteres";

            echo $ambiente->render("perfilMudarSenha.html", $this->dados);
            die();
        }

        if($senha != $senha2){
            $this->dados["alert"] = "As senhas não coincidem";

            echo $ambiente->render("perfilMudarSenha.html", $this->dados);
            die();
        }

        $usuario->senha = password_hash($senha, PASSWORD_DEFAULT);

        $usuario->update();

        header("Location: " . URL . "/perfil");
    }

    public function delete($form){
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        \Src\Lib\Sec::verifyUser();

        $senha = filter_var($form['senha'], FILTER_SANITIZE_STRING);
        $usuario = new \Src\Model\Usuario($_SESSION["id"]);

        if(!password_verify($senha, $usuario->senha)){
            $this->dados["alert"] = "Senha incorreta";

            $this->dados["usuario"] = $usuario;
            echo $ambiente->render("perfilDeletar.html", $this->dados);
            die();
        }

        $usuario->delete();

        session_destroy();

        header("Location: " . URL);
    }

    public function logout(){
        \Src\Lib\Sec::verifyUser();

        session_destroy();
        header("Location: " . URL);
    }
}