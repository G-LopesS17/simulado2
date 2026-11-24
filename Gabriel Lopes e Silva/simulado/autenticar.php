<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

$cpf_digitado = trim($_POST['cpf']);
$senha_digitada = $_POST['senha'];

$sql = "SELECT id_tutor, nome, cpf, senha FROM tutor";
$result = $conn->query($sql);

$autenticado = false;

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Verifica tanto o CPF formatado (000.000.000-00) quanto a senha em hash
        if (password_verify($cpf_digitado, $row['cpf']) && password_verify($senha_digitada, $row['senha'])) {
            $_SESSION['id_tutor'] = $row['id_tutor'];
            $_SESSION['nome_tutor'] = $row['nome'];
            $_SESSION['ultimo_acesso'] = time();
            $autenticado = true;
            break;
        }
    }
}

if ($autenticado) {
    header("Location: index.php");
    exit();
} else {
    header("Location: login.php?erro=invalido");
    exit();
}
?>
