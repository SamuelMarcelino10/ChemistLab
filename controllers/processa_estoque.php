<?php

session_start();
require_once '../config/db_connect.php'; 


if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    $_SESSION['login_error'] = "Acesso negado. Faça o login.";
    header("Location: ../views/login.php");
    exit();
}
if ($_SESSION['usuario_tipo'] !== 'Regente') {
    $_SESSION['error_message'] = "Acesso não autorizado.";
    header("Location: ../views/dashboard.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST['nome'];
    $tipo = $_POST['tipo'];
    $descricao = $_POST['descricao'];
    $quantidade = $_POST['quantidade'];
    $unidade_medida = $_POST['unidade_medida'];
    $status_equipamento = $_POST['status_equipamento'];

    if (empty($nome) || $quantidade < 0) {
        $_SESSION['error_message'] = "Nome e Quantidade (positiva) são obrigatórios.";
        header("Location: ../views/estoque_cadastrar.php");
        exit();
    }

    try {
        $sql = "INSERT INTO estoque (nome, tipo, descricao, quantidade, unidade_medida, status_equipamento) 
                VALUES (:nome, :tipo, :descricao, :quantidade, :unidade, :status)";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT); 
        $stmt->bindParam(':unidade', $unidade_medida);
        $stmt->bindParam(':status', $status_equipamento);
        
        $stmt->execute();

        $_SESSION['success_message'] = "Item '" . htmlspecialchars($nome) . "' cadastrado com sucesso!";
        header("Location: ../views/estoque_cadastrar.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Erro no sistema ao salvar o item: " . $e->getMessage();
        header("Location: ../views/estoque_cadastrar.php");
        exit();
    }

} else {
    header("Location: ../views/dashboard.php");
    exit();
}
?>