<?php

session_start(); //inicia sessao
require_once '../config/db_connect.php'; 

require_once '../models/entidades/usuario.php'; 
require_once '../models/dao/usuarioDao.php'; 

//recebe dados do form
$cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_SPECIAL_CHARS);
$senha_digitada = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_SPECIAL_CHARS);

//validacao campo vazio
if (empty($cpf) || empty($senha_digitada)) {
    $_SESSION['login_error'] = "Preencha todos os campos.";
    header("Location: ../views/login.php");
    exit();
}

try {
    $usuarioDao = new \chemistLab\models\dao\usuarioDao($pdo);

    //busca o cpf
    $usuario = $usuarioDao->findByCpf($cpf);

    //salva no banco
    if ($usuario && password_verify($senha_digitada, $usuario->getSenha())) {
        $_SESSION['autenticado'] = true;
        $_SESSION['usuario_id'] = $usuario->getId();
        $_SESSION['usuario_nome'] = $usuario->getNomeCompleto();
        $_SESSION['usuario_tipo'] = $usuario->getTipoConta(); 
        
        header("Location: ../views/dashboard.php");
    } else {
        $_SESSION['login_error'] = "CPF ou senha incorretos.";
        header("Location: ../views/login.php");
    }

} catch (PDOException $e) {
    $_SESSION['login_error'] = "Erro de conexão com o banco: " . $e->getMessage();
    header("Location: ../views/login.php");
}
exit();