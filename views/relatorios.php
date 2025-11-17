<?php


session_start();
require_once '../config/db_connect.php'; 
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    $_SESSION['login_error'] = "Acesso negado. Por favor, faça o login.";
    header("Location: login.php");
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT id, titulo, descricao FROM experimentos ORDER BY id DESC");
    $stmt->execute();
    $experimentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $erro_banco = "Erro ao buscar experimentos: " . $e->getMessage();
    $experimentos = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatórios de Experimentos</title>
    
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
        <a href="relatorios.php" class="active">Relatórios</a>
        <a href="calendario.php">Calendário</a>
        <a href="estoque_visualizar.php">Equipamentos (Status)</a>
        <a href="estoque_cadastrar.php">Cadastrar Equipamento</a>
        <a href="perfil.php">Perfil</a>
        <a href="../controllers/processa_logout.php" class="logout">Sair</a>
    </div>

    <div class="content">
        <div class="card">
            <h2>Relatórios</h2>
            <p>Gerencie os experimentos realizados no laboratório.</p>

            <?php
            if (isset($_SESSION['success_message'])) {
                echo '<div class="message success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
                unset($_SESSION['success_message']);
            }
            if (isset($_SESSION['error_message'])) {
                echo '<div class="message error">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
                unset($_SESSION['error_message']);
            }
            ?>

            <a href="experimento_cadastrar.php" class="btn btn-success">Adicionar Experimento</a>
        </div>

        <div class="card">
            <h3>Experimentos Cadastrados</h3>
            
            <?php if (isset($erro_banco)): ?>
                <div class="message error"><?php echo htmlspecialchars($erro_banco); ?></div>
            <?php endif; ?>

            <ul class="item-lista">
                <?php if (empty($experimentos)): ?>
                    <li class="item">Nenhum experimento cadastrado.</li>
                <?php else: ?>
                    <?php foreach ($experimentos as $exp): ?>
                        <li class="item">
                            <h4><?php echo htmlspecialchars($exp['titulo']); ?></h4>
                            <p><strong>Descrição:</strong> <?php echo nl2br(htmlspecialchars($exp['descricao'])); ?></p>
                            
                            <a href="../controllers/processa_experimento_delete.php?id=<?php echo $exp['id']; ?>"
                               class="action-link-delete"
                               onclick="return confirm('Tem certeza que deseja excluir este experimento: <?php echo htmlspecialchars(addslashes($exp['titulo'])); ?>?');">
                               Deletar Experimento
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>

</body>
</html>