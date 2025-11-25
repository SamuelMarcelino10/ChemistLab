<?php
//mesma logica do processa_agendamento.php
session_start();
require_once '../config/db_connect.php';

require_once '../models/entidades/equipamento.php';
require_once '../models/dao/equipamentoDao.php';

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: ../views/login.php");
    exit();
}
if ($_SESSION['usuario_tipo'] !== 'Regente') {
    header("Location: ../views/dashboard.php");
    exit();
}

$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
$tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS);
$descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_SPECIAL_CHARS); 
$quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_SANITIZE_NUMBER_INT);
$unidade_medida = filter_input(INPUT_POST, 'unidade_medida', FILTER_SANITIZE_SPECIAL_CHARS);
$status = filter_input(INPUT_POST, 'status_equipamento', FILTER_SANITIZE_SPECIAL_CHARS);

$equipamento = new \chemistLab\models\entidades\equipamento(
    $nome, 
    $tipo, 
    $descricao, 
    $quantidade, 
    $unidade_medida, 
    $status
);

$equipamentoDao = new \chemistLab\models\dao\equipamentoDao($pdo);

if ($equipamentoDao->save($equipamento)) {
    $_SESSION['success_message'] = "Item cadastrado com sucesso!";
    header("Location: ../views/estoque_visualizar.php");
} else {
    $_SESSION['error_message'] = "Erro ao cadastrar item. Tente novamente.";
    header("Location: ../views/estoque_cadastrar.php");
}
exit();