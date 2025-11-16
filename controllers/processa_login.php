<?php

session_start();

require_once '../config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $cpf = $_POST['cpf'];
    $senha = $_POST['senha'];

    if (empty($cpf) || empty($senha)) {
        $_SESSION['login_error'] = "CPF e Senha são obrigatórios.";
        header("Location: ../views/login.php"); 
        exit();
    }

    try {

        $stmt = $pdo->prepare("SELECT id, nome_completo, senha, tipo_conta FROM usuarios WHERE cpf = :cpf");
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC); 


        if ($usuario && password_verify($senha, $usuario['senha'])) {
            

            $_SESSION['autenticado'] = true;
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome_completo'];
            $_SESSION['usuario_tipo'] = $usuario['tipo_conta']; 


            header("Location: ../views/dashboard.php");
            exit();

        } else {
            $_SESSION['login_error'] = "CPF ou Senha inválidos.";
            header("Location: ../views/login.php"); 
            exit();
        }

    } catch (PDOException $e) {
        $_SESSION['login_error'] = "Erro no sistema. Tente novamente mais tarde.";

        header("Location: ../views/login.php");
        exit();
    }
} else {
    echo "Acesso negado.";
    exit();
}
?>