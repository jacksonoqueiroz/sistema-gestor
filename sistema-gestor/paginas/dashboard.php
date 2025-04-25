<?php
session_start();

include_once ('conexao/conexao.php');

function checarEstoqueMinimo($pdo) {
    $alertas = [];

    $sql = "SELECT * FROM itens WHERE estoque_atual <= estoque_minimo";
    $stmt = $pdo->query($sql);
    while ($item = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $alertas[] = "Item: {$item['nome']} com estoque crítico!";
    }

    $sql = "SELECT * FROM pecas WHERE estoque_atual <= estoque_minimo";
    $stmt = $pdo->query($sql);
    while ($peca = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $alertas[] = "Peça: {$peca['nome']} com estoque crítico!";
    }

    return $alertas;
}

$alertas = checarEstoqueMinimo($pdo);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Controle de Estoque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h1 class="mb-4">Dashboard de Estoque</h1>

    <?php if ($alertas): ?>
        <div class="alert alert-danger">
            <h4>⚠️ Alertas de Estoque:</h4>
            <ul>
                <?php foreach ($alertas as $alerta): ?>
                    <li><?= htmlspecialchars($alerta) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php else: ?>
        <div class="alert alert-success">Tudo certo no estoque!</div>
    <?php endif; ?>

    <div class="mt-5">
        <a href="relatorios.php" class="btn btn-primary">📑 Relatórios</a>
        <a href="movimentacoes.php" class="btn btn-warning">🔄 Movimentar Estoque</a>
        <a href="pedidos_compra.php" class="btn btn-danger">🛒 Pedidos de Compra</a>
        <a href="recebimento.php" class="btn btn-success">📥 Recebimento</a>
    </div>
</div>
</body>
</html>
