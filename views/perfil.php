<?php

session_start();
require_once '../config/db_connect.php';

require_once '../models/entidades/usuario.php'; 
require_once '../models/dao/usuarioDao.php';

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    $_SESSION['login_error'] = "Acesso negado. Por favor, faça o login.";
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$nome_usuario = "";
$email_usuario = "";
$cpf_usuario = "";
$tipo_conta = "";

try {
    $usuarioDao = new \chemistLab\models\dao\usuarioDao($pdo);
    
    $usuario = $usuarioDao->findById($usuario_id);

    if ($usuario) {
        $nome_usuario = $usuario->getNomeCompleto();
        $email_usuario = $usuario->getEmail();
        $cpf_usuario = $usuario->getCpf();
        $tipo_conta = $usuario->getTipoConta();
        
        $_SESSION['usuario_nome'] = $nome_usuario; 
    } else {
        header("Location: ../controllers/processa_logout.php");
        exit();
    }
} catch (PDOException $e) {
    $erro_banco = "Erro ao buscar dados do perfil: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Perfil do Usuário</title>
    
    <link rel="stylesheet" href="../assets/css/style.css">

    <link rel="icon" type="image/png" href="../assets/images/logo.png">

</head>
<body>

    <div class="sidebar">
        <div class="logo">
            <a href="dashboard.php">
                <img src="../assets/images/logo.png" alt="ChemistLab Logo">
                <h3>ChemistLab</h3>
            </a>
        </div>
        <a href="dashboard.php">Início</a>
        <a href="relatorios.php">Relatórios</a>
        <a href="calendario.php">Calendário</a>
        <a href="estoque_visualizar.php">Equipamentos (Status)</a>
        <a href="estoque_cadastrar.php">Cadastrar Equipamento</a>
        <a href="perfil.php" class="active">Perfil</a>
        <a href="../controllers/processa_logout.php" class="logout">Sair</a>
    </div>

    <div class="content">
        <div class="card">
            <h2>Perfil</h2>
            
            <?php
            if (isset($_SESSION['success_message'])) {
                echo '<div class="message success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
                unset($_SESSION['success_message']);
            }
            if (isset($erro_banco)) {
                echo '<div class="message error">' . htmlspecialchars($erro_banco) . '</div>';
            }
            ?>

            <div class="profile-info">
                <label>Nome</label>
                <p><?php echo htmlspecialchars($nome_usuario); ?></p>

                <label>E-mail</label>
                <p><?php echo htmlspecialchars($email_usuario); ?></p>

                <label>CPF (Não pode ser alterado)</label>
                <p><?php echo htmlspecialchars($cpf_usuario); ?></p>

                <label>Tipo de conta (Não pode ser alterado)</label>
                <p><?php echo htmlspecialchars($tipo_conta); ?></p>
            </div>

            <a href="perfil_editar.php" class="btn btn-primary">Editar Perfil</a>
        </div>
    </div>

</body>
</html>