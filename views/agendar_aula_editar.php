<?php

session_start();
require_once '../config/db_connect.php';

require_once '../models/entidades/agendamento.php'; 
require_once '../models/dao/agendamentoDao.php'; 

//verificacao inicial
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: login.php"); exit();
}
if ($_SESSION['usuario_tipo'] !== 'Regente') {
    header("Location: dashboard.php"); exit();
}

$agenda_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$agenda_id) {
    header("Location: calendario.php");
    exit();
}

$agendamento = null;

//busca agendamento no banco
try {
    $agendamentoDao = new \chemistLab\models\dao\agendamentoDao($pdo);
    $agendamento = $agendamentoDao->findById($agenda_id);

    if (!$agendamento) {
        header("Location: calendario.php");
        exit();
    }
    
} catch (PDOException $e) {
    die("Erro ao buscar agendamento: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Agendamento</title>
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
            <h2>Editar Agendamento</h2>
            
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
            
            <form action="../controllers/processa_agendamento_update.php" method="POST">
                
                <input type="hidden" name="id" value="<?php echo $agendamento->getId(); ?>">

                <div class="form-group">
                    <label for="data_aula">Data:</label>
                    <input type="date" id="data_aula" name="data_aula" value="<?php echo htmlspecialchars($agendamento->getDataAula()); ?>" required>
                </div>

                <div class="form-group">
                    <label for="nome_professor">Professor(a):</label>
                    <input type="text" id="nome_professor" name="nome_professor" value="<?php echo htmlspecialchars($agendamento->getNomeProfessor()); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="nome_experimento">Nome do Experimento:</label>
                    <input type="text" id="nome_experimento" name="nome_experimento" value="<?php echo htmlspecialchars($agendamento->getNomeExperimento()); ?>" required>
                </div>

                <div class="form-group">
                    <label for="turno">Turno:</label>
                    <select id="turno" name="turno" required>
                        <option value="Matutino" <?php if($agendamento->getTurno() == 'Matutino') echo 'selected'; ?>>Matutino</option>
                        <option value="Vespertino" <?php if($agendamento->getTurno() == 'Vespertino') echo 'selected'; ?>>Vespertino</option>
                        <option value="Noturno" <?php if($agendamento->getTurno() == 'Noturno') echo 'selected'; ?>>Noturno</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-editar">Salvar Alterações</button>
                </div>
            </form>
            
            <a href="agendamento_visualizar.php?id=<?php echo $agendamento->getId(); ?>" class="link-voltar">Cancelar e Voltar</a>
        </div>
    </div>

</body>
</html>