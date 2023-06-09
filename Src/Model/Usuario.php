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

        $pdo->query("delete from usuario where id = {$this->id}");
    }

    public static function create($nome, $email, $senha){
        $pdo = \Src\Lib\Database::connection();

        $pdo->query("insert into usuario(nome, email, senha) values ('{$nome}', '{$email}', '{$senha}')");

        return $pdo->lastInsertId();
    }
}