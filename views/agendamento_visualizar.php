<?php

session_start();
require_once '../config/db_connect.php'; 

require_once '../models/entidades/agendamento.php'; 
require_once '../models/dao/agendamentoDao.php'; 


//verificacao inicial
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: login.php"); exit();
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
    <title>Detalhes do Agendamento</title>
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
            <h2>Agendamento de Aula</h2>
            
            <div class="profile-info">
                
                <label>Data:</label>
                <p><?php echo htmlspecialchars(date('d/m/Y', strtotime($agendamento->getDataAula()))); ?></p>

                <label>Turno:</label>
                <p><?php echo htmlspecialchars($agendamento->getTurno()); ?></p>

                <label>Professor(a):</label>
                <p><?php echo htmlspecialchars($agendamento->getNomeProfessor()); ?></p>

                <label>Experimento:</label>
                <p><?php echo htmlspecialchars($agendamento->getNomeExperimento()); ?></p>
            </div>
            
            <a href="agendar_aula_editar.php?id=<?php echo $agendamento->getId(); ?>" class="btn btn-editar">Editar Agendamento</a>
            <a href="calendario.php" class="link-voltar">Voltar para o Calendário</a>
        </div>
    </div>

</body>
</html>