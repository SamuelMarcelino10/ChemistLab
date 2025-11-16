<?php

session_start();
require_once '../config/db_connect.php';

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: ../views/login.php"); exit();
}
if ($_SESSION['usuario_tipo'] !== 'Regente') {
    header("Location: ../views/dashboard.php"); exit();
}

$item_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$item_id) {
    $_SESSION['error_message'] = "ID do item não fornecido.";
    header("Location: ../views/estoque_visualizar.php");
    exit();
}

try {
    $sql = "DELETE FROM estoque WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $item_id, PDO::PARAM_INT);
    $stmt->execute();

    $_SESSION['success_message'] = "Item excluído com sucesso!";
    header("Location: ../views/estoque_visualizar.php");
    exit();

} catch (PDOException $e) {
    $_SESSION['error_message'] = "Erro no sistema ao excluir o item: " . $e->getMessage();
    header("Location: ../views/estoque_visualizar.php");
    exit();
}
?>