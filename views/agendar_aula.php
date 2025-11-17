<?php

session_start();

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    $_SESSION['login_error'] = "Acesso negado. Por favor, faça o login.";
    header("Location: login.php");
    exit();
}
if ($_SESSION['usuario_tipo'] !== 'Regente') {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Agendar Nova Aula</title>
    
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
            
            <h2>Agendar Nova Aula</h2>
            
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

            <form action="../controllers/processa_agendamento.php" method="POST">
                <div class="form-group">
                    <label for="data_aula">Data:</label>
                    <input type="date" id="data_aula" name="data_aula" required>
                </div>

                <div class="form-group">
                    <label for="nome_professor">Professor(a):</label>
                    <input type="text" id="nome_professor" name="nome_professor" required>
                </div>
                
                <div class="form-group">
                    <label for="nome_experimento">Nome do Experimento:</label>
                    <input type="text" id="nome_experimento" name="nome_experimento" required>
                </div>

                <div class="form-group">
                    <label for="turno">Turno:</label>
                    <select id="turno" name="turno" required>
                        <option value="">Selecione o turno</option>
                        <option value="Matutino">Matutino</option>
                        <option value="Vespertino">Vespertino</option>
                        <option value="Noturno">Noturno</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-editar">Salvar Agendamento</button>
                </div>
            </form>

            <a href="calendario.php" class="link-voltar">Voltar para o Calendário</a>
        </div>
    </div>

</body>
</html>