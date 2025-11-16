<?php


session_start();
require_once '../config/db_connect.php'; 

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: ../views/login.php"); exit();
}
if ($_SESSION['usuario_tipo'] !== 'Regente') {
    header("Location: ../views/dashboard.php"); exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $data_aula = $_POST['data_aula'];
    $nome_professor = $_POST['nome_professor'];
    $nome_experimento = $_POST['nome_experimento'];
    $turno = $_POST['turno'];
    $regente_id = $_SESSION['usuario_id']; 

    if (empty($data_aula) || empty($nome_professor) || empty($nome_experimento) || empty($turno)) {
        $_SESSION['error_message'] = "Todos os campos são obrigatórios.";
        header("Location: ../views/agendar_aula.php");
        exit();
    }
    
    if (strtotime($data_aula) < strtotime(date('Y-m-d'))) {
         $_SESSION['error_message'] = "Não é possível agendar em uma data passada.";
        header("Location: ../views/agendar_aula.php");
        exit();
    }

    try {
        $sql = "INSERT INTO agendamentos (data_aula, turno, nome_professor, nome_experimento, regente_id) 
                VALUES (:data_aula, :turno, :nome_prof, :nome_exp, :regente_id)";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->bindParam(':data_aula', $data_aula);
        $stmt->bindParam(':turno', $turno);
        $stmt->bindParam(':nome_prof', $nome_professor);
        $stmt->bindParam(':nome_exp', $nome_experimento);
        $stmt->bindParam(':regente_id', $regente_id, PDO::PARAM_INT);
        
        $stmt->execute();

        $_SESSION['success_message'] = "Aula agendada com sucesso!";
        header("Location: ../views/agendar_aula.php"); 
        exit();

    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Erro no sistema ao agendar: " . $e->getMessage();
        header("Location: ../views/agendar_aula.php");
        exit();
    }
} else {
    header("Location: ../views/dashboard.php");
    exit();
}
?>