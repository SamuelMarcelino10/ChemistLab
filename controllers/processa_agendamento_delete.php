<?php


session_start();
require_once '../config/db_connect.php';

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: ../views/login.php"); exit();
}
if ($_SESSION['usuario_tipo'] !== 'Regente') {
    header("Location: ../views/dashboard.php"); exit();
}

$agendamento_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$agendamento_id) {
    $_SESSION['error_message'] = "ID do agendamento não fornecido.";
    header("Location: ../views/calendario.php");
    exit();
}

try {
    $sql = "DELETE FROM agendamentos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $agendamento_id, PDO::PARAM_INT);
    $stmt->execute();

    $_SESSION['success_message'] = "Agendamento excluído com sucesso!";
    header("Location: ../views/calendario.php");
    exit();

} catch (PDOException $e) {
    $_SESSION['error_message'] = "Erro no sistema ao excluir o agendamento: " . $e->getMessage();
    header("Location: ../views/calendario.php");
    exit();
}
?>