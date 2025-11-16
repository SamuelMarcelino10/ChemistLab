<?php


session_start();
require_once '../config/db_connect.php';

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
    $stmt = $pdo->prepare("SELECT nome_completo, email, cpf, tipo_conta FROM usuarios WHERE id = :id");
    $stmt->bindParam(':id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $nome_usuario = $usuario['nome_completo'];
        $email_usuario = $usuario['email'];
        $cpf_usuario = $usuario['cpf'];
        $tipo_conta = $usuario['tipo_conta'];
        
        $_SESSION['usuario_nome'] = $usuario['nome_completo'];
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

</head>
<body>

    <div class="sidebar">
        <h3>ChemistLab</h3>
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