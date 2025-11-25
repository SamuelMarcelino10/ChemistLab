<?php


session_start();
require_once '../config/db_connect.php';

//verificacao inicial
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: login.php"); exit();
}

$usuario_id = $_SESSION['usuario_id'];
$nome_usuario = "";
$email_usuario = "";
$cpf_usuario = "";
$tipo_conta = "";

//busca dados do perfil no banco
try {
    $stmt = $pdo->prepare("SELECT nome_completo, email, cpf, tipo_conta FROM usuarios WHERE id = :id");
    $stmt->bindParam(':id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $nome_usuario = $usuario['nome_completo'];
        $email_usuario = $usuario['email'];
        $cpf_usuario = $usuario['cpf'];
        $tipo_conta = $usuario['tipo_conta'];
    }
} catch (PDOException $e) {
    $erro_banco = "Erro ao buscar dados: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    
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
        <a href="estoque_cadastrar.php">Cadastrar Equipamento</a>
        <a href="perfil.php" class="active">Perfil</a>
        <a href="../controllers/processa_logout.php" class="logout">Sair</a>
    </div>

    <div class="content">
        <div class="card">
            <h2>Editar Perfil</h2>
            
            <?php
            if (isset($_SESSION['error_message'])) {
                echo '<div class="message error">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
                unset($_SESSION['error_message']);
            }
            if (isset($erro_banco)) {
                echo '<div class="message error">' . htmlspecialchars($erro_banco) . '</div>';
            }
            ?>

            <form action="../controllers/processa_perfil_update.php" method="POST">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome_usuario); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email_usuario); ?>" required>
                </div>

                <div class="form-group">
                    <label for="cpf">CPF:</label>
                    <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($cpf_usuario); ?>" disabled>
                </div>
                
                <div class="form-group">
                    <label for="tipo_conta">Tipo de conta:</label>
                    <input type="text" id="tipo_conta" name="tipo_conta" value="<?php echo htmlspecialchars($tipo_conta); ?>" disabled>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-editar">Salvar Alterações</button>
                </div>
            </form>
            
            <a href="perfil.php" class="link-voltar">Cancelar</a>
        </div>
    </div>

</body>
</html>