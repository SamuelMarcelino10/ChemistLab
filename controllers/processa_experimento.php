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

    $titulo = $_POST['titulo'];
    $materiais = $_POST['materiais'];
    $descricao = $_POST['descricao'];
    $regente_id = $_SESSION['usuario_id']; 

    if (empty($titulo)) {
        $_SESSION['error_message'] = "O Título do experimento é obrigatório.";
        header("Location: ../views/experimento_cadastrar.php");
        exit();
    }

    try {
        $sql = "INSERT INTO experimentos (titulo, materiais, descricao, regente_id) 
                VALUES (:titulo, :materiais, :descricao, :regente_id)";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':materiais', $materiais);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':regente_id', $regente_id, PDO::PARAM_INT);
        
        $stmt->execute();

        $_SESSION['success_message'] = "Experimento '".htmlspecialchars($titulo)."' cadastrado com sucesso!";
        header("Location: ../views/experimento_cadastrar.php"); 
        exit();

    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Erro no sistema ao salvar experimento: " . $e->getMessage();
        header("Location: ../views/experimento_cadastrar.php");
        exit();
    }
} else {
    header("Location: ../views/dashboard.php");
    exit();
}
?>