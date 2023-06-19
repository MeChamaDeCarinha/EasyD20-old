<?php

// Autoload PSR-4
require './Src/Lib/vendor/autoload.php';


// Configuração do Router
$roteador = new CoffeeCode\Router\Router(URL);
$roteador->namespace("Src\Controller");


//   Homepage
$roteador->group(null); 
$roteador->get("/", "general:showHomepage");


//  Login
$roteador->group("login");
$roteador->get("/", "user:showLogin");
$roteador->post("/", "user:login"); //  Função de login


//  Cadastro
$roteador->group("signup");
$roteador->get("/", "user:showSignup");
$roteador->post("/", "user:signup"); //  Função de cadastro


// Configurações
$roteador->group("settings");
$roteador->get("/", "config:show"); 


// Aventuras
$roteador->group("aventuras");
$roteador->get("/", "aventura:loadAventuras");
$roteador->get("/{id}", "aventura:show");
$roteador->get("/{id}/entrar", "aventura:selectAventura");
$roteador->get("/{id}/configurar", "aventura:showConfigurar");
$roteador->post("/{id}/configurar", "aventura:saveConfig"); //  Função de salvar as configurações da aventura
$roteador->get("/{id}/sair", "aventura:showSair");
$roteador->post("/{id}/sair", "aventura:sair"); //  Função de sair da aventura
$roteador->get("/{id}/excluir", "aventura:showExcluir");
$roteador->post("/{id}/excluir", "aventura:delete"); //  Função de deletar a aventura
$roteador->get("/{id}/mestre/{jogador_id}", "aventura:showMestre");
$roteador->post("/{id}/mestre/{jogador_id}", "aventura:mestre"); //  Função de transferir o cargo de mestre da aventura
$roteador->get("/{id}/expulsar/{jogador_id}", "aventura:showExpulsar");
$roteador->post("/{id}/expulsar/{jogador_id}", "aventura:expulsar"); //  Função de expulsar da aventura
$roteador->get("/{id}/banir/{jogador_id}", "aventura:showBanir");
$roteador->post("/{id}/banir/{jogador_id}", "aventura:banir"); //  Função de banir da aventura
$roteador->get("/{id}/editar", "aventura:showEditar");
$roteador->post("/{id}/editar", "aventura:save"); //  Função de salvar mudanças na aventura
$roteador->get("/criar", "aventura:loadCriar");
$roteador->post("/criar", "aventura:criar"); //  Função de criar aventura
$roteador->get("/entrar", "aventura:loadEntrar");
$roteador->post("/entrar", "aventura:entrar"); //  Função de entrar na aventura
$roteador->get("/exit", "aventura:exit"); // Função de sair da aventura


// Mapas
$roteador->group("mapas");
$roteador->get("/", "mapa:loadMapas");
$roteador->get("/{id}", "mapa:show");
$roteador->get("/{id}/editar", "mapa:showEditar");
$roteador->post("/{id}/editar", "mapa:save"); //  Função de salvar mudanças no mapa
$roteador->get("/novo", "mapa:showNovo");
$roteador->post("/novo", "mapa:new"); //  Função de criar mapa
$roteador->get("/{id}/esconder", "mapa:turnHidden"); //  Função de alterar visibilidade do mapa
$roteador->get("/{id}/excluir", "mapa:delete"); //  Função de deletar mapa



// Fichas
$roteador->group("fichas");
$roteador->get("/", "ficha:loadFichas");
$roteador->get("/{ficha_id}", "ficha:showFicha");
$roteador->get("/{ficha_id}/editar", "ficha:showEditar");
$roteador->post("/{ficha_id}/editar", "ficha:save"); //  Função de salvar mudanças na ficha


// Atributos
$roteador->get("/{ficha_id}/atributos/{atributo_id}", "atributo:showEditar");
$roteador->post("/{ficha_id}/atributos/{atributo_id}", "atributo:save"); //  Função de salvar mudanças no atributo
$roteador->get("/{ficha_id}/atributos/novo", "atributo:showNovo");
$roteador->post("/{ficha_id}/atributos/novo", "atributo:new"); //  Função de criar atributo
$roteador->get("/{ficha_id}/atributos/{atributo_id}/excluir", "atributo:delete"); //  Função de deletar atributo


// Habilidades
$roteador->get("/{ficha_id}/habilidades/{habilidade_id}", "habilidade:showEditar");
$roteador->post("/{ficha_id}/habilidades/{habilidade_id}", "habilidade:save"); //  Função de salvar mudanças na habilidade
$roteador->get("/{ficha_id}/habilidades/novo", "habilidade:showNova");
$roteador->post("/{ficha_id}/habilidades/novo", "habilidade:new"); //  Função de criar habilidade
$roteador->get("/{ficha_id}/habilidades/{habilidade_id}/excluir", "habilidade:delete"); //  Função de deletar habilidade


// Itens
$roteador->get("/{ficha_id}/itens/{item_id}", "item:show");
$roteador->get("/{ficha_id}/itens/{item_id}/editar", "item:showEditar");
$roteador->post("/{ficha_id}/itens/{item_id}/editar", "item:save"); //  Função de salvar mudanças no item
$roteador->get("/{ficha_id}/itens/novo", "item:showNovo");
$roteador->post("/{ficha_id}/itens/novo", "item:new"); //  Função de criar item
$roteador->get("/{ficha_id}/itens/{item_id}/excluir", "item:delete"); //  Função de deletar item


// Dados
$roteador->group("dados");
$roteador->get("/", "general:showDados");


// Ajuda
$roteador->group("tutorial");
$roteador->get("/", "general:showTutorial");


// Perfil
$roteador->group("perfil");
$roteador->get("/", "user:show");
$roteador->get("/editar", "user:showEditar");
$roteador->post("/editar", "user:save"); // Função de salvar mudanças na conta  
$roteador->get("/senha", "user:showSenha");
$roteador->post("/senha", "user:changePass"); // Função de mudar senha da conta
$roteador->get("/verificar", "user:showVerificar");
$roteador->post("/verificar", "user:verificar"); // Função de verificar email da conta
$roteador->get("/deletar", "user:showDelete");
$roteador->post("/deletar", "user:delete"); // Funcção para deletar conta

$roteador->get("/sair", "user:logout"); // Função de sair da conta


// Recuperar
$roteador->group("recuperar");
$roteador->get("/", "recuperar:showVerificar");
$roteador->post("/", "recuperar:verificar"); // Função de verificar código de recuperaração
$roteador->get("/usuario", "recuperar:showUsuario"); 
$roteador->post("/usuario", "recuperar:usuarioVerificar"); // Função de verificar se existe conta com o email
$roteador->get("/senha", "recuperar:showRecuperar");
$roteador->post("/senha", "recuperar:recuperar"); // Função de mudar a senha


// Erro
$roteador->group("error");
$roteador->get("/{error}", "error:show");


$roteador->dispatch();

if($roteador->error()) {
    $roteador->redirect("/error/{$roteador->error()}");
}