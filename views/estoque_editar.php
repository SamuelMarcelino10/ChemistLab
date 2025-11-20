<?php

session_start();
require_once '../config/db_connect.php';

require_once '../models/entidades/equipamento.php';
require_once '../models/dao/equipamentoDao.php';

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: login.php"); exit();
}
if ($_SESSION['usuario_tipo'] !== 'Regente') {
    header("Location: dashboard.php"); exit();
}

$item_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$item_id) {
    header("Location: estoque_visualizar.php");
    exit();
}

$equipamento = null; 

try {
    $equipamentoDao = new \chemistLab\models\dao\equipamentoDao($pdo);

    $equipamento = $equipamentoDao->findById($item_id); 

    if (!$equipamento) {
        header("Location: estoque_visualizar.php");
        exit();
    }
    
} catch (PDOException $e) {
    die("Erro ao buscar item: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Item: <?php echo htmlspecialchars($equipamento->getNome()); ?></title>
    
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
        <a href="estoque_visualizar.php" class="active">Equipamentos (Status)</a>
        <a href="estoque_cadastrar.php">Cadastrar Equipamento</a>
        <a href="perfil.php">Perfil</a>
        <a href="../controllers/processa_logout.php" class="logout">Sair</a>
    </div>

    <div class="content">
        <div class="card">
            <h2>Editar Item: <?php echo htmlspecialchars($equipamento->getNome()); ?></h2>

            <form action="../controllers/processa_estoque_update.php" method="POST">
                
                <input type="hidden" name="id" value="<?php echo $equipamento->getId(); ?>">

                <div class="form-group">
                    <label for="nome">Nome do Item:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($equipamento->getNome()); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="tipo">Tipo:</label>
                    <select id="tipo" name="tipo">
                        <option value="Equipamento" <?php if($equipamento->getTipo() == 'Equipamento') echo 'selected'; ?>>Equipamento</option>
                        <option value="Insumo" <?php if($equipamento->getTipo() == 'Insumo') echo 'selected'; ?>>Insumo</option>
                        <option value="Reagente" <?php if($equipamento->getTipo() == 'Reagente') echo 'selected'; ?>>Reagente</option>
                        <option value="Outro" <?php if($equipamento->getTipo() == 'Outro') echo 'selected'; ?>>Outro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="quantidade">Quantidade:</label>
                    <input type="number" id="quantidade" name="quantidade" value="<?php echo htmlspecialchars($equipamento->getQuantidade()); ?>" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="unidade_medida">Unidade de Medida:</label>
                    <input type="text" id="unidade_medida" name="unidade_medida" value="<?php echo htmlspecialchars($equipamento->getUnidadeMedida()); ?>">
                </div>

                <div class="form-group">
                    <label for="status_equipamento">Status:</label> <select id="status_equipamento" name="status_equipamento">
                        <option value="Disponível" <?php if($equipamento->getStatusEquipamento() == 'Disponível') echo 'selected'; ?>>Disponível</option>
                        <option value="Em manutenção" <?php if($equipamento->getStatusEquipamento() == 'Em manutenção') echo 'selected'; ?>>Em manutenção</option>
                        <option value="Indisponível" <?php if($equipamento->getStatusEquipamento() == 'Indisponível') echo 'selected'; ?>>Indisponível</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-editar">Salvar Alterações</button>
                </div>
            </form>
            
            <a href="estoque_visualizar.php" class="link-voltar">Cancelar e Voltar</a>
        </div>
    </div>

</body>
</html>