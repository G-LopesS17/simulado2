<?php
require_once '../verifica_login.php';
require_once '../conexao.php';

$id = intval($_GET['id']);
$stmt = $conn->prepare("DELETE FROM tutor WHERE id_tutor = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php");
} else {
    echo "<script>alert('Não é possível excluir um tutor que possui pets ou agendamentos vinculados!'); window.location='index.php';</script>";
}
?>
