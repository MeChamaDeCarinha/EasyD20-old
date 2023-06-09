<?php

namespace Src\Controller;

class inventario {

    public $dados = array(
        "url" => URL
    );

    public function loadInventario() {
        $ambiente = new \Twig\Environment(new \Twig\Loader\FilesystemLoader("./Src/View"));

        \Src\Controller\user::verify();

        $this->dados["inventarios"] = $this->invetarioQuery();

        echo $ambiente->render("inventario.html", $this->dados);
    }

    public function invetarioQuery() {
        $player_id = $_SESSION["id"];
        $aventura_id = $_SESSION["aventura"];
        $mestrando = $_SESSION["mestrando"];

        $banco = \Src\Lib\Database::conect();

        if($mestrando){
            $sql = "select * from inventario where cod_aventura = $aventura_id";
        }
        else {
            $sql = "select * from inventario where cod_usuario = $player_id and cod_aventura = $aventura_id";
        }

        $result = mysqli_query($banco, $sql);

        if(mysqli_num_rows($result) == 0){
            return null;
        }

        $content = array();

        while($inventario = mysqli_fetch_assoc($result)){
                $content[] = array(
                    "id" => $inventario["id"],
                    "moeda" => $inventario["moeda"],
                );
        }

        return $content;
    }
}