<?php

session_start(); 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>ChemistLab - Login</title>
    
    <link rel="stylesheet" href="../assets/css/style.css">

    <link rel="icon" type="image/png" href="../assets/images/logo.png">

</head>
<body class="login-body">
    <div class="login-container">
        
        <h2>Login do Regente</h2>
        <p>"Um espaço onde a curiosidade é o primeiro passo para grandes descobertas"</p>

        <?php
        if (isset($_SESSION['login_error'])) {
            echo '<div class="message error">' . htmlspecialchars($_SESSION['login_error']) . '</div>';
            //apaga mensagem de erro p nao aparecer de novo quando a pagina for atualizada
            unset($_SESSION['login_error']);
        }
        ?>

        <form action="../controllers/processa_login.php" method="POST">
            <div>
                <input type="text" name="cpf" placeholder="CPF (Ex: 000.000.000-00)" required>
            </div>
            <div>
                <input type="password" name="senha" placeholder="Senha" required>
            </div>
            <button type="submit">Entrar</button>
        </form>
        
        </div>
</body>
</html>