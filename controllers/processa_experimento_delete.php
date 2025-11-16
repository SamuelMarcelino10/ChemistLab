<?php

session_start();
require_once '../config/db_connect.php';

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: ../views/login.php"); exit();
}
if ($_SESSION['usuario_tipo'] !== 'Regente') {
    header("Location: ../views/dashboard.php"); exit();
}

$experimento_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$experimento_id) {
    $_SESSION['error_message'] = "ID do experimento não fornecido.";
    header("Location: ../views/relatorios.php");
    exit();
}

try {
    $sql = "DELETE FROM experimentos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $experimento_id, PDO::PARAM_INT);
    $stmt->execute();

    $_SESSION['success_message'] = "Experimento excluído com sucesso!";
    header("Location: ../views/relatorios.php");
    exit();

} catch (PDOException $e) {
    $_SESSION['error_message'] = "Erro no sistema ao excluir o experimento: " . $e->getMessage();
    header("Location: ../views/relatorios.php");
    exit();
}
?>