<?php


session_start();

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    $_SESSION['login_error'] = "Acesso negado. Por favor, faça o login.";
    header("Location: login.php");
    exit();
}

$nome_usuario = $_SESSION['usuario_nome'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>ChemistLab - Dashboard</title>
    
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
        <a href="dashboard.php" class="active">Início</a> <a href="relatorios.php">Relatórios</a>
        <a href="calendario.php">Calendário</a>
        <a href="estoque_visualizar.php">Equipamentos (Status)</a>
        <a href="estoque_cadastrar.php">Cadastrar Equipamento</a>
        <a href="perfil.php">Perfil</a>
        <a href="../controllers/processa_logout.php" class="logout">Sair</a>
    </div>

    <div class="content">
        <div class="header">
            <span>Bem-vindo(a), <?php echo htmlspecialchars($nome_usuario); ?>!</span>
        </div>

        <h2>Dashboard</h2>
        
        <div class="card">
            <h3>Acessos Recentes</h3>
            <p>Bem-vindo ao sistema de gerenciamento do laboratório.</p>
            <p>Use o menu ao lado para navegar entre as funcionalidades.</p>
        </div>
    </div>

</body>
</html>