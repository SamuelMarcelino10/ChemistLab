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

$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$data_aula = filter_input(INPUT_POST, 'data_aula', FILTER_SANITIZE_SPECIAL_CHARS);
$nome_professor = filter_input(INPUT_POST, 'nome_professor', FILTER_SANITIZE_SPECIAL_CHARS);
$nome_experimento = filter_input(INPUT_POST, 'nome_experimento', FILTER_SANITIZE_SPECIAL_CHARS);
$turno = filter_input(INPUT_POST, 'turno', FILTER_SANITIZE_SPECIAL_CHARS);

if (!$id) {
    header("Location: ../views/calendario.php");
    exit();
}

try {
    $agendamentoDao = new \chemistLab\models\dao\agendamentoDao($pdo);
    $agendamento_original = $agendamentoDao->findById($id);

    if (!$agendamento_original) {
        $_SESSION['error_message'] = "Agendamento não encontrado para edição.";
        header("Location: ../views/calendario.php");
        exit();
    }
    
    $regente_id = $agendamento_original->getRegenteId(); 

    $agendamento = new \chemistLab\models\entidades\agendamento(
        $data_aula, 
        $turno, 
        $nome_professor, 
        $nome_experimento,
        $regente_id, 
        $id
    );

    if ($agendamentoDao->update($agendamento)) {
        $_SESSION['success_message'] = "Agendamento atualizado com sucesso!";
        header("Location: ../views/agendamento_visualizar.php?id=" . $id); 
    } else {
        $_SESSION['error_message'] = "Erro ao atualizar agendamento. Nenhuma linha alterada.";
        header("Location: ../views/agendar_aula_editar.php?id=" . $id); 
    }

} catch (PDOException $e) {
    $_SESSION['error_message'] = "Erro de banco de dados: " . $e->getMessage();
    header("Location: ../views/agendar_aula_editar.php?id=" . $id);
}
exit();