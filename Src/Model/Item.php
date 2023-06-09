<?php

namespace Src\Model;

use PDO;

class Item {
    public int $id;
    public int $cod_ficha;
    public string $nome;
    public string $img_path;
    public int $quantidade;
    public ?float $preco;

    public function __construct($id){
        $this->id = $id;

        $pdo = \Src\Lib\Database::connection();
            
        $result = $pdo->query("select * from item where id = {$id}", PDO::FETCH_ASSOC);

        foreach($result as $item){
            $this->cod_ficha = $item["cod_ficha"];
            $this->nome = $item["nome"];
            $this->img_path = $item["img_path"];
            $this->quantidade = $item["quantidade"];
            $this->preco = $item["preco"];
        }
    }

    public function update(){
        $pdo = \Src\Lib\Database::connection();

        $pdo->query("update item set nome = '{$this->nome}', img_path = '{$this->img_path}', quantidade = {$this->quantidade}, preco = {$this->preco} where id = {$this->id}");
    }

    public function delete(){
        $pdo = \Src\Lib\Database::connection();

        $pdo->query("delete from item where id = {$this->id}");
    }

    public static function create($cod_ficha, $nome, $img_path, $quantidade, $preco){
        $pdo = \Src\Lib\Database::connection();

        $pdo->query("insert into item(cod_ficha, nome, img_path, quantidade, preco) values ({$cod_ficha}, '{$nome}', '{$img_path}', {$quantidade}, {$preco})");

        return $pdo->lastInsertId();
    }
}