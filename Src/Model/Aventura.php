<?php

namespace Src\Model;

use PDO;

class Aventura {
    public int $id;
    public string $nome;
    public ?string $livro;
    public string $descricao;
    public string $imagem;
    public int $codigo;
    public int $publica;
    public int $editar;
    public int $jogadores;
    public int $ficha_mestre;

    public function __construct($id){
        $this->id = $id;

        $pdo = \Src\Lib\Database::connection();
            
        $result = $pdo->query("select * from aventura where id = {$id}", PDO::FETCH_ASSOC);

        if($result->rowCount() == 0){
            header("Location: " . URL . "/erro/404");
        }

        foreach($result as $aventura){
            $this->nome = $aventura["nome"];
            $this->livro = $aventura["livro"];
            $this->descricao = $aventura["descricao"];
            $this->imagem = $aventura["imagem"];
            $this->codigo = $aventura["cod"];
            $this->publica = $aventura["publica"];
            $this->editar = $aventura["editar"];
            $this->ficha_mestre = $aventura["ficha_mestre"];
        }

        $this->jogadores = $pdo->query("select id from aventura_usuario where cod_aventura = {$this->id} and banido = 0", PDO::FETCH_ASSOC)->rowCount();
    }

    public function update(){
        $pdo = \Src\Lib\Database::connection();

        $pdo->query("update aventura set nome = '{$this->nome}', livro = '{$this->livro}', descricao = '{$this->descricao}', imagem = '{$this->imagem}', publica = {$this->publica}, editar = {$this->editar}, ficha_mestre = {$this->ficha_mestre} where id = {$this->id}");
    }

    public function delete(){
        $pdo = \Src\Lib\Database::connection();

        $result = $pdo->query("select id from ficha where cod_aventura = {$this->id}", PDO::FETCH_ASSOC);

        foreach($result as $ficha){
            $ficha = new \Src\Model\Ficha($ficha["id"]);
            $ficha->delete();
        }

        $pdo->query("delete from mapa where cod_aventura = {$this->id}");

        $pdo->query("delete from aventura_usuario where cod_aventura = {$this->id}");

        $pdo->query("delete from aventura where id = {$this->id}");
    }

    public static function create($nome, $livro, $descricao, $imagem){
        $pdo = \Src\Lib\Database::connection();
        $result = $pdo->query("select cod from aventura", PDO::FETCH_ASSOC);  

        $cods = array();
        foreach($result as $codigos){  
            $cods[] = $codigos["cod"];
        }

        $cod;
        do {
            $used = false;
            $cod = random_int(1, 999999);

            foreach($cods as $codigo){
                if($codigo == $cod){
                    echo "a";
                    $used = true;
                }
            }
        } while ($used == true);

        $formated = str_pad(strval($cod), 6, "0", STR_PAD_LEFT);

        $pdo->query("insert into aventura(nome, livro, descricao, imagem, cod) values ('{$nome}', '{$livro}', '{$descricao}', '{$imagem}', '{$formated}')");

        return $pdo->lastInsertId();
    }
}