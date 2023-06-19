<?php

namespace Src\Model;

use PDO;

class Ficha {
    public int $id;
    public int $cod_usuario;
    public int $cod_aventura;
    public ?string $nome;
    public ?float $dinheiro;
    public ?int $idade;
    public ?float $altura;
    public ?int $peso;
    public ?string $raca;
    public ?string $classe;
    public ?int $nivel;
    public ?int $pontos_exp;

    public string $nome_jogador;
    public string $img_jogador;

    public array $atributos;
    public array $habilidades;
    public array $itens;

    public function __construct($id){
        $this->id = $id;

        $pdo = \Src\Lib\Database::connection();
            
        $result = $pdo->query("select * from ficha where id = {$id}", PDO::FETCH_ASSOC);

        if($result->rowCount() == 0){
            header("Location: " . URL . "/erro/404");
        }

        foreach($result as $ficha){
            $this->cod_usuario = $ficha["cod_usuario"];
            $this->cod_aventura = $ficha["cod_aventura"];
            $this->nome = $ficha["nome"];
            $this->dinheiro = $ficha["dinheiro"];
            $this->idade = $ficha["idade"];
            $this->altura = $ficha["altura"];
            $this->peso = $ficha["peso"];
            $this->raca = $ficha["raca"];
            $this->classe = $ficha["classe"];
            $this->nivel = $ficha["nivel"];
            $this->pontos_exp = $ficha["pontos_exp"];
        }

        $this->findJogador();
        $this->findAtributos();
        $this->findHabilidades();
        $this->findItens();
    }

    public function update(){
        $pdo = \Src\Lib\Database::connection();

        $pdo->query("update ficha set 
        nome = '{$this->nome}',
        dinheiro = {$this->dinheiro},
        idade = {$this->idade}, 
        altura = {$this->altura}, 
        peso = {$this->peso}, 
        raca = '{$this->raca}', 
        classe = '{$this->classe}', 
        nivel = {$this->nivel}, 
        pontos_exp = {$this->pontos_exp}
        where id = {$this->id}");
    }

    public function delete(){
        $pdo = \Src\Lib\Database::connection();

        $pdo->query("delete from atributo where cod_ficha = {$this->id}");
        $pdo->query("delete from habilidade where cod_ficha = {$this->id}");
        $pdo->query("delete from item where cod_ficha = {$this->id}");

        $pdo->query("delete from ficha where id = {$this->id}");
    }

    public static function create($cod_usuario, $cod_aventura){
        $pdo = \Src\Lib\Database::connection();

        $pdo->query("insert into ficha(cod_usuario, cod_aventura) values ({$cod_usuario}, {$cod_aventura})");

        return $pdo->lastInsertId();
    }

    public function findJogador(){
        $pdo = \Src\Lib\Database::connection();
            
        $result = $pdo->query("select * from usuario where id = {$this->cod_usuario}", PDO::FETCH_ASSOC);

        foreach($result as $user){
            $this->nome_jogador = $user["nome"];
            $this->img_jogador = $user["img_path"];
        }
    }

    public function findAtributos(){
        $pdo = \Src\Lib\Database::connection();
            
        $result = $pdo->query("select id from atributo where cod_ficha = {$this->id}", PDO::FETCH_ASSOC);

        unset($this->atributos);

        foreach($result as $atributo){
            $this->atributos[$atributo["id"]] = new \Src\Model\Atributo($atributo["id"]);
        }
    }

    public function findHabilidades(){
        $pdo = \Src\Lib\Database::connection();
            
        $result = $pdo->query("select id from habilidade where cod_ficha = {$this->id}", PDO::FETCH_ASSOC);

        unset($this->habilidades);

        foreach($result as $habilidade){
            $this->habilidades[$habilidade["id"]] = new \Src\Model\Habilidade($habilidade["id"]);
        }
    }

    public function findItens(){
        $pdo = \Src\Lib\Database::connection();
            
        $result = $pdo->query("select id from item where cod_ficha = {$this->id}", PDO::FETCH_ASSOC);

        unset($this->itens);

        foreach($result as $item){
            $this->itens[$item["id"]] = new \Src\Model\Item($item["id"]);
        }
    }
}