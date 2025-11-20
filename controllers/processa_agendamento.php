<?php

session_start();
require_once '../config/db_connect.php';
require_once '../models/entidades/agendamento.php';
require_once '../models/dao/agendamentoDao.php';

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: ../views/login.php"); exit();
}
if ($_SESSION['usuario_tipo'] !== 'Regente') {
    header("Location: ../views/dashboard.php"); exit();
}

$data_aula = filter_input(INPUT_POST, 'data_aula', FILTER_SANITIZE_SPECIAL_CHARS);
$nome_professor = filter_input(INPUT_POST, 'nome_professor', FILTER_SANITIZE_SPECIAL_CHARS);
$nome_experimento = filter_input(INPUT_POST, 'nome_experimento', FILTER_SANITIZE_SPECIAL_CHARS);
$turno = filter_input(INPUT_POST, 'turno', FILTER_SANITIZE_SPECIAL_CHARS);
$regente_id = $_SESSION['usuario_id']; 

$agendamento = new \chemistLab\models\entidades\agendamento(
    $data_aula, 
    $turno, 
    $nome_professor, 
    $nome_experimento,
    $regente_id 
);

$agendamentoDao = new \chemistLab\models\dao\agendamentoDao($pdo);

if ($agendamentoDao->save($agendamento)) {
    $_SESSION['success_message'] = "Aula agendada com sucesso!";
    header("Location: ../views/calendario.php");
} else {
    $_SESSION['error_message'] = "Erro ao agendar a aula. Tente novamente.";
    header("Location: ../views/agendar_aula.php");
}
exit();