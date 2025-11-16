<?php


session_start();
require_once '../config/db_connect.php'; 

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    $_SESSION['login_error'] = "Acesso negado. Por favor, faça o login.";
    header("Location: login.php");
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT id, data_aula, turno, nome_professor, nome_experimento 
                            FROM agendamentos 
                            WHERE data_aula >= CURRENT_DATE 
                            ORDER BY data_aula ASC, turno ASC");
    $stmt->execute();
    $agendamentos_futuros = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

</head>
<body>

    <div class="sidebar">
        <h3>ChemistLab</h3>
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
                            <h4><?php echo htmlspecialchars(date('d/m/Y', strtotime($aula['data_aula']))); ?> - <?php echo htmlspecialchars($aula['turno']); ?></h4>
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