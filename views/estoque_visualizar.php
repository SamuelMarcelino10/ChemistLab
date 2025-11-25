<?php

//verificacao inicial
session_start();
require_once '../config/db_connect.php'; 

require_once '../models/dao/equipamentoDao.php'; 

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    $_SESSION['login_error'] = "Acesso negado. Por favor, faça o login.";
    header("Location: login.php");
    exit();
}

//logica de busca
$search_term = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS);
}

//busca item no estoque
try {
    $equipamentoDao = new \chemistLab\models\dao\equipamentoDao($pdo);

    $itens_estoque = $equipamentoDao->findAll($search_term); 

} catch (PDOException $e) {
    $erro_banco = "Erro ao buscar itens do estoque: " . $e->getMessage();
    $itens_estoque = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Status dos Equipamentos</title>
    
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
            
            <h2>Status dos Equipamentos</h2>
            
            <form method="GET" action="estoque_visualizar.php" class="form-group" style="display: flex; gap: 10px;">
                <input type="text" name="search" placeholder="Buscar por Nome ou Tipo..." 
                       value="<?php echo htmlspecialchars($search_term); ?>" style="flex-grow: 1;">
                <button type="submit" class="btn btn-primary" style="width: auto; padding: 10px 15px;">🔍 Buscar</button>
                <?php if ($search_term): ?>
                    <a href="estoque_visualizar.php" class="btn btn-editar" style="width: auto; padding: 10px 15px;">Limpar</a>
                <?php endif; ?>
            </form>
            <br>

            <?php
            if (isset($_SESSION['success_message'])) {
                echo '<div class="message success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
                unset($_SESSION['success_message']);
            }
            if (isset($_SESSION['error_message'])) {
                echo '<div class="message error">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
                unset($_SESSION['error_message']);
            }
            if (isset($erro_banco)) {
                echo '<div class="message error">' . htmlspecialchars($erro_banco) . '</div>';
            }
            ?>

            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Tipo</th>
                        <th>Qtd.</th>
                        <th>Status</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($itens_estoque)): ?>
                        <tr>
                            <td colspan="5">Nenhum item cadastrado ainda.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($itens_estoque as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['nome']); ?></td>
                                <td><?php echo htmlspecialchars($item['tipo']); ?></td>
                                <td><?php echo htmlspecialchars($item['quantidade']); ?></td>
                                <td>
                                    <?php
                                    $status = $item['status_equipamento'];
                                    $classe_status = ''; 

                                    if ($status == 'Disponível') {
                                        $classe_status = 'status-disponivel';
                                    } elseif ($status == 'Em manutenção') {
                                        $classe_status = 'status-manutencao';
                                    } elseif ($status == 'Indisponível') {
                                        $classe_status = 'status-indisponivel';
                                    }
                                    
                                    echo '<span class="status ' . $classe_status . '">' . htmlspecialchars($status) . '</span>';
                                    ?>
                                </td>
                                
                                <td class="acoes">
                                    <a href="estoque_editar.php?id=<?php echo $item['id']; ?>" class="action-link editar">Editar</a>
                                    <a href="../controllers/processa_estoque_delete.php?id=<?php echo $item['id']; ?>" 
                                       class="action-link deletar" 
                                       onclick="return confirm('Tem certeza que deseja excluir este item: <?php echo htmlspecialchars(addslashes($item['nome'])); ?>?');">
                                       Deletar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            
        </div>
    </div>

</body>
</html>
