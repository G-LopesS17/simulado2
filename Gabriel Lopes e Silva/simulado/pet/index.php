<?php
require_once '../verifica_login.php';
require_once '../conexao.php';

$sql = "SELECT p.id_pet, p.nome AS nome_pet, p.especie, t.nome AS nome_tutor 
        FROM pet p 
        INNER JOIN tutor t ON p.id_tutor = t.id_tutor";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pets</title>
    <link rel="stylesheet" href="../css/estilo.css">
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
        <h2>Gerenciar Pets</h2>
        <a href="criar.php" class="btn">+ Novo Pet</a>

        <table>
            <tr>
                <th>ID</th>
                <th>Nome do Pet</th>
                <th>Espécie</th>
                <th>Tutor Responsável</th>
                <th>Ações</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id_pet'] ?></td>
                <td><?= htmlspecialchars($row['nome_pet']) ?></td>
                <td><?= htmlspecialchars($row['especie']) ?></td>
                <td><?= htmlspecialchars($row['nome_tutor']) ?></td>
                <td>
                    <a href="editar.php?id=<?= $row['id_pet'] ?>" class="btn btn-editar">Editar</a>
                    <a href="excluir.php?id=<?= $row['id_pet'] ?>" class="btn btn-excluir" onclick="return confirm('Excluir este pet?')">Excluir</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
