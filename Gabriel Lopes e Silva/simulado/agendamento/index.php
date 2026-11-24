<?php
require_once '../verifica_login.php';
require_once '../conexao.php';

$sql = "SELECT a.id_agendamento, a.data, t.nome AS nome_tutor, p.nome AS nome_pet, p.especie
        FROM agendamento a
        INNER JOIN tutor t ON a.id_tutor = t.id_tutor
        INNER JOIN pet p ON a.id_pet = p.id_pet
        ORDER BY a.data ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Agendamentos</title>
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
        <h2>Agendamentos</h2>
        <a href="criar.php" class="btn">+ Novo Agendamento</a>

        <table>
            <tr>
                <th>ID</th>
                <th>Data</th>
                <th>Tutor</th>
                <th>Pet (Espécie)</th>
                <th>Ações</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id_agendamento'] ?></td>
                <td><?= date('d/m/Y', strtotime($row['data'])) ?></td>
                <td><?= htmlspecialchars($row['nome_tutor']) ?></td>
                <td><?= htmlspecialchars($row['nome_pet']) ?> (<?= htmlspecialchars($row['especie']) ?>)</td>
                <td>
                    <a href="editar.php?id=<?= $row['id_agendamento'] ?>" class="btn btn-editar">Editar</a>
                    <a href="excluir.php?id=<?= $row['id_agendamento'] ?>" class="btn btn-excluir" onclick="return confirm('Cancelar este agendamento?')">Excluir</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
