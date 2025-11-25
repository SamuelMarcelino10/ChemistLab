<?php

//inicia sessao
session_start();

//limpa variaveis
session_unset();

//apaga temp de login
session_destroy();

//redireciona p login
header("Location: ../views/login.php");
exit();
?>