<?php

//pegar config
require_once 'config.php';

//conecta no banco
try {
    $dsn = "pgsql:host=" . DB_HOST . ";dbname=" . DB_NAME;
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Erro de conexão com o banco de dados: " . $e->getMessage());
}

?>