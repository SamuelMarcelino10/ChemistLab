<?php


session_start();
require_once '../config/db_connect.php';

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: ../views/login.php"); exit();
}
if ($_SESSION['usuario_tipo'] !== 'Regente') {
    header("Location: ../views/dashboard.php"); exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST['id']; 
    $nome = $_POST['nome'];
    $tipo = $_POST['tipo'];
    $descricao = $_POST['descricao'];
    $quantidade = $_POST['quantidade'];
    $unidade_medida = $_POST['unidade_medida'];
    $status_equipamento = $_POST['status_equipamento'];

    if (empty($id) || empty($nome) || $quantidade < 0) {
        $_SESSION['error_message'] = "ID, Nome e Quantidade (positiva) são obrigatórios.";
        header("Location: ../views/estoque_visualizar.php");
        exit();
    }

    try {
        $sql = "UPDATE estoque SET 
                    nome = :nome, 
                    tipo = :tipo, 
                    descricao = :descricao, 
                    quantidade = :quantidade, 
                    unidade_medida = :unidade, 
                    status_equipamento = :status
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
        $stmt->bindParam(':unidade', $unidade_medida);
        $stmt->bindParam(':status', $status_equipamento);
        
        $stmt->execute();

        $_SESSION['success_message'] = "Item '" . htmlspecialchars($nome) . "' atualizado com sucesso!";
        header("Location: ../views/estoque_visualizar.php"); 
        exit();

    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Erro no sistema ao atualizar o item: " . $e->getMessage();
        header("Location: ../views/estoque_visualizar.php");
        exit();
    }
} else {
    header("Location: ../views/dashboard.php");
    exit();
}
?>