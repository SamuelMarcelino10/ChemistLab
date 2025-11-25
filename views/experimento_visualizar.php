<?php

session_start();

//verificacao inicial
require_once '../config/db_connect.php'; 

require_once '../models/entidades/experimento.php'; 
require_once '../models/dao/experimentoDao.php'; 

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: login.php"); exit();
}

$exp_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$exp_id) {
    header("Location: relatorios.php");
    exit();
}

$experimento = null;

//busca experimento no banco
try {
    $experimentoDao = new \chemistLab\models\dao\experimentoDao($pdo);
    $experimento = $experimentoDao->findById($exp_id);

    if (!$experimento) {
        header("Location: relatorios.php");
        exit();
    }
    
} catch (PDOException $e) {
    die("Erro ao buscar experimento: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Detalhes: <?php echo htmlspecialchars($experimento->getTitulo()); ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
</head>
<body>

    <div class="sidebar">
        <a href="dashboard.php">Início</a>
        <a href="relatorios.php" class="active">Relatórios</a>
        <a href="calendario.php">Calendário</a>
        <a href="estoque_visualizar.php">Equipamentos (Status)</a>
        <a href="estoque_cadastrar.php">Cadastrar Equipamento</a>
        <a href="perfil.php">Perfil</a>
        <a href="../controllers/processa_logout.php" class="logout">Sair</a>
    </div>

    <div class="content">
        <div class="card">
            <h2><?php echo htmlspecialchars($experimento->getTitulo()); ?></h2>
            
            <div class="profile-info">
                
                <label>Materiais:</label>
                <p><?php echo nl2br(htmlspecialchars($experimento->getMateriais())); ?></p>

                <label>Descrição (Passo a passo):</label>
                <p><?php echo nl2br(htmlspecialchars($experimento->getDescricao())); ?></p>
            </div>
            
            <a href="experimento_editar.php?id=<?php echo $experimento->getId(); ?>" class="btn btn-editar">Editar Experimento</a>
            <a href="relatorios.php" class="link-voltar">Voltar para Relatórios</a>
        </div>
    </div>

</body>
</html>