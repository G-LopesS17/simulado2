<?php
$servidor = "localhost:3306";
$nome = "root";
$senha = "";
$banco = "saep_db";

$conn = new mysqli($servidor, $nome, $senha, $banco);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
