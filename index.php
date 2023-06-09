<?php

require './Src/Lib/vendor/autoload.php';

$roteador = new CoffeeCode\Router\Router(URL);
$roteador->namespace("Src\Controller");

$roteador->group(null); 
$roteador->get("/", "main:loadTemplate"); // OK
$roteador->get("/teste", "teste:teste");

$roteador->group("login"); // OK
$roteador->get("/", "user:loadTemplateLogin"); // OK
$roteador->post("/", "user:login"); // OK

$roteador->group("signup"); // OK
$roteador->get("/", "user:loadTemplateSignup"); // OK
$roteador->post("/", "user:signup"); // OK


$roteador->group("settings"); // OK
$roteador->get("/", "config:show");

$roteador->group("aventuras"); // OK
$roteador->get("/", "aventura:loadAventura"); // OK
$roteador->get("/{id}", "aventura:show"); // OK
$roteador->get("/{id}/entrar", "aventura:selectAventura"); // OK


$roteador->get("/{id}/configurar", "aventura:showConfigurar"); // OK
$roteador->post("/{id}/configurar", "aventura:saveConfig"); // OK


$roteador->get("/{id}/sair", "aventura:showSair"); // OK
$roteador->post("/{id}/sair", "aventura:sair"); // OK

$roteador->get("/{id}/excluir", "aventura:showExcluir"); // OK
$roteador->post("/{id}/excluir", "aventura:excluir"); // OK

$roteador->get("/{id}/mestre/{jogador_id}", "aventura:showMestre"); // OK
$roteador->post("/{id}/mestre/{jogador_id}", "aventura:mestre"); // OK

$roteador->get("/{id}/expulsar/{jogador_id}", "aventura:showExpulsar"); // OK
$roteador->post("/{id}/expulsar/{jogador_id}", "aventura:expulsar"); // OK

$roteador->get("/{id}/banir/{jogador_id}", "aventura:showBanir"); // OK
$roteador->post("/{id}/banir/{jogador_id}", "aventura:banir"); // OK


$roteador->get("/{id}/editar", "aventura:showEditar"); // OK
$roteador->post("/{id}/editar", "aventura:save"); // OK

$roteador->get("/criar", "aventura:loadCriar"); // OK
$roteador->post("/criar", "aventura:criar"); // OK

$roteador->get("/entrar", "aventura:loadEntrar"); // OK
$roteador->post("/entrar", "aventura:entrar"); // OK




$roteador->group("fichas"); // OK
$roteador->get("/", "ficha:loadFicha"); // OK

$roteador->get("/{ficha_id}", "ficha:showFicha"); // OK - TODO => Items

$roteador->get("/{ficha_id}/editar", "ficha:showEditar"); // OK
$roteador->post("/{ficha_id}/editar", "ficha:salvar"); // OK



$roteador->get("/{ficha_id}/atributos/{atributo_id}", "atributo:showEditar"); // OK
$roteador->post("/{ficha_id}/atributos/{atributo_id}", "atributo:salvar"); // OK

$roteador->get("/{ficha_id}/habilidades/{habilidade_id}", "habilidade:showEditar"); // OK
$roteador->post("/{ficha_id}/habilidades/{habilidade_id}", "habilidade:salvar"); // OK


$roteador->get("/{ficha_id}/itens/{item_id}", "item:show"); // OK
$roteador->get("/{ficha_id}/itens/{item_id}/editar", "item:showEditar"); // OK
$roteador->post("/{ficha_id}/itens/{item_id}/editar", "item:salvar"); // OK



$roteador->get("/{ficha_id}/atributos/novo", "atributo:showNovo"); // OK
$roteador->get("/{ficha_id}/habilidades/novo", "habilidade:showNova"); // OK
$roteador->get("/{ficha_id}/itens/novo", "item:showNovo"); // OK

$roteador->post("/{ficha_id}/atributos/novo", "atributo:novo"); // OK
$roteador->post("/{ficha_id}/habilidades/novo", "habilidade:nova"); // OK
$roteador->post("/{ficha_id}/itens/novo", "item:novo"); // OK

$roteador->get("/{ficha_id}/atributos/{atributo_id}/excluir", "atributo:excluir"); // OK
$roteador->get("/{ficha_id}/habilidades/{habilidade_id}/excluir", "habilidade:excluir"); // OK
$roteador->get("/{ficha_id}/itens/{item_id}/excluir", "item:excluir"); // OK


$roteador->group("perfil"); // OK
$roteador->get("/", "user:loadTemplateProfile"); //OK
$roteador->get("/sair", "user:logout"); //OK

$roteador->get("/verificar", "user:loadTemplateVerificar"); //OK
$roteador->post("/verificar", "user:verificar"); //OK

$roteador->get("/senha", "user:loadTemplateSenha"); //OK
$roteador->post("/senha", "user:changePass"); //OK

$roteador->get("/editar", "user:loadTemplateProfileEdit"); //OK
$roteador->post("/editar", "user:editar"); //OK


$roteador->dispatch();