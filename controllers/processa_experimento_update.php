<?php

session_start();
require_once '../config/db_connect.php';

require_once '../models/entidades/experimento.php';
require_once '../models/dao/experimentoDao.php';

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: ../views/login.php"); exit();
}
if ($_SESSION['usuario_tipo'] !== 'Regente') {
    header("Location: ../views/dashboard.php"); exit();
}

$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);
$materiais = filter_input(INPUT_POST, 'materiais', FILTER_DEFAULT); 
$descricao = filter_input(INPUT_POST, 'descricao', FILTER_DEFAULT); 

if (!$id) {
    header("Location: ../views/relatorios.php");
    exit();
}

try {
    $regente_id = $_SESSION['usuario_id']; 

    $experimento = new \chemistLab\models\entidades\experimento(
        $titulo, 
        $materiais, 
        $descricao,
        $regente_id, 
        $id 
    );

    $experimentoDao = new \chemistLab\models\dao\experimentoDao($pdo);

    if ($experimentoDao->update($experimento)) {
        $_SESSION['success_message'] = "Experimento atualizado com sucesso!";
        header("Location: ../views/relatorios.php"); 
    } else {
        $_SESSION['error_message'] = "Erro ao atualizar experimento. Nenhuma linha alterada.";
        header("Location: ../views/experimento_editar.php?id=" . $id); 
    }

} catch (PDOException $e) {
    $_SESSION['error_message'] = "Erro de banco de dados: " . $e->getMessage();
    header("Location: ../views/experimento_editar.php?id=" . $id);
}
exit();