<?php
require_once '../verifica_login.php';
require_once '../conexao.php';

$id = intval($_GET['id']);
$stmt = $conn->prepare("DELETE FROM agendamento WHERE id_agendamento = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: index.php");
exit();
?>
