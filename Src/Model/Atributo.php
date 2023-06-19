<?php

namespace Src\Model;

use PDO;

class Atributo {
    public int $id;
    public int $cod_ficha;
    public string $nome;
    public int $valor;

    public function __construct($id){
        $this->id = $id;

        $pdo = \Src\Lib\Database::connection();
            
        $result = $pdo->query("select * from atributo where id = {$id}", PDO::FETCH_ASSOC);

        if($result->rowCount() == 0){
            header("Location: " . URL . "/erro/404");
        }

        foreach($result as $atributo){
            $this->cod_ficha = $atributo["cod_ficha"];
            $this->nome = $atributo["nome"];
            $this->valor = $atributo["valor"];
        }
    }

    public function update(){
        $pdo = \Src\Lib\Database::connection();

        $pdo->query("update atributo set nome = '{$this->nome}', valor = {$this->valor} where id = {$this->id}");
    }

    public function delete(){
        $pdo = \Src\Lib\Database::connection();

        $pdo->query("delete from atributo where id = {$this->id}");
    }

    public static function create($cod_ficha, $nome, $valor){
        $pdo = \Src\Lib\Database::connection();

        $pdo->query("insert into atributo(cod_ficha, nome, valor) values ({$cod_ficha}, '{$nome}', {$valor})");

        return $pdo->lastInsertId();
    }
}