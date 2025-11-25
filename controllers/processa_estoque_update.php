<?php
//mesma logica do processa_agendamento_update.php
session_start();
require_once '../config/db_connect.php';

require_once '../models/entidades/equipamento.php';
require_once '../models/dao/equipamentoDao.php';

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: ../views/login.php"); exit();
}
if ($_SESSION['usuario_tipo'] !== 'Regente') {
    header("Location: ../views/dashboard.php"); exit();
}

$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
$tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS);
$quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_SANITIZE_NUMBER_INT);
$unidade_medida = filter_input(INPUT_POST, 'unidade_medida', FILTER_SANITIZE_SPECIAL_CHARS);
$status = filter_input(INPUT_POST, 'status_equipamento', FILTER_SANITIZE_SPECIAL_CHARS);

if (!$id) {
    header("Location: ../views/estoque_visualizar.php");
    exit();
}

try {
    $equipamentoDao = new \chemistLab\models\dao\equipamentoDao($pdo);
    $equipamento_original = $equipamentoDao->findById($id);

    if (!$equipamento_original) {
        $_SESSION['error_message'] = "Item a ser atualizado não encontrado.";
        header("Location: ../views/estoque_visualizar.php");
        exit();
    }
    
    $equipamento = new \chemistLab\models\entidades\equipamento(
        $nome, 
        $tipo, 
        $equipamento_original->getDescricao(), 
        $quantidade, 
        $unidade_medida, 
        $status,
        $id 
    );

    if ($equipamentoDao->update($equipamento)) {
        $_SESSION['success_message'] = "Item atualizado com sucesso!";
    } else {
        $_SESSION['error_message'] = "Erro ao atualizar item. Nenhuma linha foi alterada.";
    }

} catch (PDOException $e) {
    $_SESSION['error_message'] = "Erro de banco de dados: " . $e->getMessage();
}

header("Location: ../views/estoque_visualizar.php");
exit();