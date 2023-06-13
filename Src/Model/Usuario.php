<?php

namespace Src\Model;

use PDO;

class Usuario {
    public int $id;
    public string $nome;
    public string $email;
    public string $senha;
    public ?string $img_path;
    public int $verificado;

    public function __construct($id){
        $this->id = $id;

        $pdo = \Src\Lib\Database::connection();
            
        $result = $pdo->query("select * from usuario where id = {$id}", PDO::FETCH_ASSOC);

        foreach($result as $user){
            $this->nome = $user["nome"];
            $this->email = $user["email"];
            $this->senha = $user["senha"];
            $this->img_path = $user["img_path"];
            $this->verificado = $user["verificado"];
        }
    }

    public function update(){
        $pdo = \Src\Lib\Database::connection();

        $pdo->query("update usuario set nome = '{$this->nome}', email = '{$this->email}', senha = '{$this->senha}', img_path = '{$this->img_path}', verificado = '{$this->verificado}' where id = {$this->id}");
    }

    public function delete(){
        $pdo = \Src\Lib\Database::connection();

        $result = $pdo->query("select id from ficha where cod_usuario = {$this->id}");
        foreach($result as $fichas){
            $ficha = new \Src\Model\Ficha($fichas["id"]);
            $ficha->delete();
        }

        $result = $pdo->query("select * from aventura_usuario where cod_usuario = {$this->id}", PDO::FETCH_ASSOC);
        foreach($result as $aventura){
            if(!$aventura["mestre"]){
                $pdo->query("delete from aventura_usuario where cod_usuario = {$this->id} and cod_aventura = {$aventura['cod_aventura']}");
            }
            else {
                $quantidade = $pdo->query("select id from aventura_usuario where cod_aventura = {$aventura['cod_aventura']}", PDO::FETCH_ASSOC);

                if($quantidade->rowCount() == 1){
                    $av = new \Src\Model\Aventura($aventura['cod_aventura']);
                    $pdo->query("delete from aventura_usuario where cod_usuario = {$this->id} and cod_aventura = {$av->id}");
                    $av->delete();
                }
                else {
                    $resultUsuarios = $pdo->query("select cod_usuario from aventura_usuario where cod_aventura = {$aventura['cod_aventura']} and mestre = 0  ", PDO::FETCH_ASSOC);
                    foreach($resultUsuarios as $user){
                        $users[$user["cod_usuario"]] = $user["cod_usuario"];
                    }

                    $random_user = array_rand($users);

                    $pdo->query("update aventura_usuario set mestre = 1 where cod_usuario = {$random_user} and cod_aventura = {$aventura['cod_aventura']}");

                    $pdo->query("delete from aventura_usuario where cod_usuario = {$this->id} and cod_aventura = {$aventura['cod_aventura']}");
                }
            }
        }

        $pdo->query("delete from usuario where id = {$this->id}");
    }

    public static function create($nome, $email, $senha){
        $pdo = \Src\Lib\Database::connection();

        $pdo->query("insert into usuario(nome, email, senha) values ('{$nome}', '{$email}', '{$senha}')");

        return $pdo->lastInsertId();
    }
}