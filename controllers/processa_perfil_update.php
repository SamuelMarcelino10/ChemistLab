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

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $usuario_id = $_SESSION['usuario_id'];

    if (empty($nome) || empty($email)) {
        $_SESSION['error_message'] = "Nome e E-mail são obrigatórios.";
        header("Location: ../views/perfil_editar.php");
        exit();
    }

    try {
        $sql = "UPDATE usuarios SET 
                    nome_completo = :nome, 
                    email = :email
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->bindParam(':id', $usuario_id, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        
        $stmt->execute();

        $_SESSION['success_message'] = "Perfil atualizado com sucesso!";
        header("Location: ../views/perfil.php"); 
        exit();

    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Erro ao atualizar o perfil. O e-mail já pode estar em uso.";
        header("Location: ../views/perfil_editar.php");
        exit();
    }
} else {
    header("Location: ../views/dashboard.php");
    exit();
}
?>