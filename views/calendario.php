<?php
session_start();
require_once '../config/db_connect.php'; 

require_once '../models/dao/agendamentoDao.php'; 

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    $_SESSION['login_error'] = "Acesso negado. Por favor, faça o login.";
    header("Location: login.php");
    exit();
}

try {
    $agendamentoDao = new \chemistLab\models\dao\agendamentoDao($pdo);

    $agendamentos_futuros = $agendamentoDao->findFuture();
    
} catch (PDOException $e) {
    $erro_banco = "Erro ao buscar agendamentos: " . $e->getMessage();
    $agendamentos_futuros = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Calendário e Agendamentos</title>
    
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
        <a href="calendario.php" class="active">Calendário</a>
        <a href="estoque_visualizar.php">Equipamentos (Status)</a>
        <a href="estoque_cadastrar.php">Cadastrar Equipamento</a>
        <a href="perfil.php">Perfil</a>
        <a href="../controllers/processa_logout.php" class="logout">Sair</a>
    </div>

    <div class="content">
        <div class="card">
            <h2>Agendamentos</h2>
            <p>Gerencie os horários e aulas do laboratório.</p>
            
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
            
            <a href="agendar_aula.php" class="btn btn-primary">Agendar Nova Aula</a>
        </div>

        <div class="card">
            <h3>Próximas Aulas Agendadas</h3>
            
            <?php if (isset($erro_banco)): ?>
                <div class="message error"><?php echo htmlspecialchars($erro_banco); ?></div>
            <?php endif; ?>

            <ul class="item-lista">
                <?php if (empty($agendamentos_futuros)): ?>
                    <li class="item">Nenhuma aula agendada para os próximos dias.</li>
                <?php else: ?>
                    <?php foreach ($agendamentos_futuros as $aula): ?>
                        <li class="item">
                            <h4>
                                <a href="agendamento_visualizar.php?id=<?php echo $aula['id']; ?>" class="action-link editar">
                                    <?php echo htmlspecialchars(date('d/m/Y', strtotime($aula['data_aula']))); ?> - <?php echo htmlspecialchars($aula['turno']); ?>
                                </a>
                            </h4>
                            <p><strong>Professor(a):</strong> <?php echo htmlspecialchars($aula['nome_professor']); ?></p>
                            <p><strong>Experimento:</strong> <?php echo htmlspecialchars($aula['nome_experimento']); ?></p>
                            
                            <a href="../controllers/processa_agendamento_delete.php?id=<?php echo $aula['id']; ?>"
                               class="action-link-delete"
                               onclick="return confirm('Tem certeza que deseja excluir este agendamento?');">
                               Deletar Agendamento
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>

</body>
</html>
