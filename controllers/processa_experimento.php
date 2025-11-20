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

$titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);
$materiais = filter_input(INPUT_POST, 'materiais', FILTER_SANITIZE_SPECIAL_CHARS);
$descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_SPECIAL_CHARS);
$regente_id = $_SESSION['usuario_id']; 

$experimento = new \chemistLab\models\entidades\experimento(
    $titulo, 
    $materiais, 
    $descricao,
    $regente_id 
);

$experimentoDao = new \chemistLab\models\dao\experimentoDao($pdo);

if ($experimentoDao->save($experimento)) {
    $_SESSION['success_message'] = "Experimento cadastrado com sucesso!";
    header("Location: ../views/relatorios.php");
} else {
    $_SESSION['error_message'] = "Erro ao cadastrar experimento. Tente novamente.";
    header("Location: ../views/experimento_cadastrar.php");
}
exit();