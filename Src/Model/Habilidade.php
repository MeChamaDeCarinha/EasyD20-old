<?php

namespace Src\Model;

use PDO;

class Habilidade {
    public int $id;
    public int $cod_ficha;
    public string $nome;
    public int $forca;
    public int $nivel;

    public function __construct($id){
        $this->id = $id;

        $pdo = \Src\Lib\Database::connection();
            
        $result = $pdo->query("select * from habilidade where id = {$id}", PDO::FETCH_ASSOC);

        if($result->rowCount() == 0){
            header("Location: " . URL . "/erro/404");
        }

        foreach($result as $habilidade){
            $this->cod_ficha = $habilidade["cod_ficha"];
            $this->nome = $habilidade["nome"];
            $this->forca = $habilidade["forca"];
            $this->nivel = $habilidade["nivel"];
        }
    }

    public function update(){
        $pdo = \Src\Lib\Database::connection();

        $pdo->query("update habilidade set nome = '{$this->nome}', forca = {$this->forca}, nivel = {$this->nivel} where id = {$this->id}");
    }

    public function delete(){
        $pdo = \Src\Lib\Database::connection();

        $pdo->query("delete from habilidade where id = {$this->id}");
    }

    public static function create($cod_ficha, $nome, $forca, $nivel){
        $pdo = \Src\Lib\Database::connection();

        $pdo->query("insert into habilidade(cod_ficha, nome, forca, nivel) values ({$cod_ficha}, '{$nome}', {$forca}, {$nivel})");

        return $pdo->lastInsertId();
    }
}