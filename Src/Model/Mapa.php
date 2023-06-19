<?php

namespace Src\Model;

use PDO;

class Mapa {
    public int $id;
    public int $cod_aventura;
    public string $nome;
    public string $descricao;
    public string $img_path;
    public int $hidden;

    public function __construct($id){
        $this->id = $id;

        $pdo = \Src\Lib\Database::connection();
            
        $result = $pdo->query("select * from mapa where id = {$id}", PDO::FETCH_ASSOC);

        if($result->rowCount() == 0){
            header("Location: " . URL . "/erro/404");
        }

        foreach($result as $mapa){
            $this->cod_aventura = $mapa["cod_aventura"];
            $this->nome = $mapa["nome"];
            $this->descricao = $mapa["descricao"];
            $this->img_path = $mapa["img_path"];
            $this->hidden = $mapa["hidden"];
        }
    }

    public function update(){
        $pdo = \Src\Lib\Database::connection();

        $pdo->query("update mapa set nome = '{$this->nome}', descricao = '{$this->descricao}', img_path = '{$this->img_path}', hidden = {$this->hidden} where id = {$this->id}");
    }

    public function delete(){
        $pdo = \Src\Lib\Database::connection();

        $pdo->query("delete from mapa where id = {$this->id}");
    }

    public static function create($cod_aventura, $nome, $descricao, $img_path){
        $pdo = \Src\Lib\Database::connection();

        $pdo->query("insert into mapa(cod_aventura, nome, descricao, img_path) values ({$cod_aventura}, '{$nome}', '{$descricao}', '{$img_path}')");
        
        return $pdo->lastInsertId();
    }
}