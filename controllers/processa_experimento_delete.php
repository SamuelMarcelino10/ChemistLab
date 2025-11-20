<?php

session_start();
require_once '../config/db_connect.php';

require_once '../models/dao/experimentoDao.php';

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: ../views/login.php"); exit();
}
if ($_SESSION['usuario_tipo'] !== 'Regente') {
    header("Location: ../views/dashboard.php"); exit();
}

$item_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$item_id) {
    $_SESSION['error_message'] = "ID do experimento inválido.";
    header("Location: ../views/relatorios.php");
    exit();
}

try {
    $experimentoDao = new \chemistLab\models\dao\experimentoDao($pdo);

    if ($experimentoDao->delete($item_id)) {
        $_SESSION['success_message'] = "Experimento excluído com sucesso!";
    } else {
        $_SESSION['error_message'] = "Erro ao excluir experimento. Tente novamente.";
    }

} catch (PDOException $e) {
    $_SESSION['error_message'] = "Erro de banco de dados: " . $e->getMessage();
}

header("Location: ../views/relatorios.php");
exit();