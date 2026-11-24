<?php
require_once 'verifica_login.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Início - Sistema Pet</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <nav class="navbar">
        <a href="/simulado/index.php">Início</a>
        <a href="/simulado/tutor/index.php">Tutores</a>
        <a href="/simulado/pet/index.php">Pets</a>
        <a href="/simulado/agendamento/index.php">Agendamentos</a>
        <a href="/simulado/logout.php" class="btn-sair">Sair</a>
    </nav>

    <div class="container">
        <h1>Bem-vindo(a), <?= htmlspecialchars($_SESSION['nome_tutor']) ?>!</h1>
        <p>Utilize o menu superior para gerenciar tutores, pets e realizar novos agendamentos.</p>
    </div>
</body>
</html>
