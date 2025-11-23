<?php

session_start();
require_once '../config/db_connect.php';

require_once '../models/entidades/experimento.php'; 
require_once '../models/dao/experimentoDao.php'; 

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: login.php"); exit();
}
if ($_SESSION['usuario_tipo'] !== 'Regente') {
    header("Location: dashboard.php"); exit();
}

$exp_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$exp_id) {
    header("Location: relatorios.php");
    exit();
}

$experimento = null;

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
    <title>Editar Experimento</title>
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
            <h2>Editar Experimento</h2>
            
            <form action="../controllers/processa_experimento_update.php" method="POST">
                
                <input type="hidden" name="id" value="<?php echo $experimento->getId(); ?>">

                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($experimento->getTitulo()); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="materiais">Materiais (um por linha):</label>
                    <textarea id="materiais" name="materiais" rows="5"><?php echo htmlspecialchars($experimento->getMateriais()); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição (Passo a passo):</label>
                    <textarea id="descricao" name="descricao" rows="8"><?php echo htmlspecialchars($experimento->getDescricao()); ?></textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-editar">Salvar Alterações</button>
                </div>
            </form>
            
            <a href="experimento_visualizar.php?id=<?php echo $experimento->getId(); ?>" class="link-voltar">Cancelar e Voltar</a>
        </div>
    </div>

</body>
</html>