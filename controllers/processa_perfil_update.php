<?php
//mesma logica do processa_agendamento_update.php
session_start();
require_once '../config/db_connect.php';

require_once '../models/entidades/usuario.php'; 
require_once '../models/dao/usuarioDao.php';

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: ../views/login.php"); exit();
}

$id = $_SESSION['usuario_id'];
$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

if (!$nome || !$email) {
    $_SESSION['error_message'] = "Nome ou E-mail inválidos.";
    header("Location: ../views/perfil_editar.php");
    exit();
}

try {
    $usuarioDao = new \chemistLab\models\dao\usuarioDao($pdo);
    
    $usuario_atual = $usuarioDao->findById($id);

    if (!$usuario_atual) {
        $_SESSION['error_message'] = "Usuário não encontrado.";
        header("Location: ../views/perfil_editar.php");
        exit();
    }
    
    $usuario_para_atualizar = new \chemistLab\models\entidades\usuario(
        $nome, 
        $email, 
        $usuario_atual->getCpf(),
        $usuario_atual->getTipoConta(),
        null, 
        $id 
    );

    if ($usuarioDao->update($usuario_para_atualizar)) {
        $_SESSION['success_message'] = "Perfil atualizado com sucesso!";
        $_SESSION['usuario_nome'] = $nome; 
    } else {
        $_SESSION['error_message'] = "Erro ao atualizar perfil. Tente novamente.";
    }

} catch (PDOException $e) {
    $_SESSION['error_message'] = "Erro de banco de dados: " . $e->getMessage();
}

header("Location: ../views/perfil.php");
exit();