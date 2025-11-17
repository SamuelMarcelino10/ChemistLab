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
    <title>Cadastrar Equipamento</title>
    
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
        <a href="calendario.php">Calendário</a>
        <a href="estoque_visualizar.php">Equipamentos (Status)</a>
        <a href="estoque_cadastrar.php" class="active">Cadastrar Equipamento</a>
        <a href="perfil.php">Perfil</a>
        <a href="../controllers/processa_logout.php" class="logout">Sair</a>
    </div>

    <div class="content">
        <div class="card">
            
            <h2>Adicionar Equipamento/Insumo</h2>
            <p>Preencha os campos para registrar um novo item no estoque.</p>

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

            <form action="../controllers/processa_estoque.php" method="POST">
                <div class="form-group">
                    <label for="nome">Nome do Item:</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                
                <div class="form-group">
                    <label for="tipo">Tipo:</label>
                    <select id="tipo" name="tipo">
                        <option value="Equipamento">Equipamento</option>
                        <option value="Insumo">Insumo</option>
                        <option value="Reagente">Reagente</option>
                        <option value="Outro">Outro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição (Ex: Capacidade, Graduação):</label>
                    <textarea id="descricao" name="descricao" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="quantidade">Quantidade (em estoque):</label>
                    <input type="number" id="quantidade" name="quantidade" value="1" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="unidade_medida">Unidade de Medida:</label>
                    <input type="text" id="unidade_medida" name="unidade_medida" placeholder="Ex: ml, g, unidades">
                </div>

                <div class="form-group">
                    <label for="status_equipamento">Status Inicial:</label>
                    <select id="status_equipamento" name="status_equipamento">
                        <option value="Disponível">Disponível</option>
                        <option value="Em manutenção">Em manutenção</option>
                        <option value="Indisponível">Indisponível</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-salvar">Salvar Item</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>