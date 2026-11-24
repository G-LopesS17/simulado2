<?php
require_once '../verifica_login.php';
require_once '../conexao.php';

$id = intval($_GET['id']);
$stmt = $conn->prepare("DELETE FROM pet WHERE id_pet = ?");
$stmt->bind_param("i", $id);

try {
    $stmt->execute();
    header("Location: index.php");
} catch (mysqli_sql_exception $e) {
    echo "<script>alert('Não é possível excluir um pet que possui agendamentos!'); window.location='index.php';</script>";
}
?>
