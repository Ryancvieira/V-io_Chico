<?php
require 'conexao.php';

$whatsapp = '5514999999999'; // número da empresa: 55 + DDD + número

$categorias = $pdo->query("SELECT * FROM categorias ORDER BY id")->fetchAll();

$slug = $_GET['cat'] ?? '';
$categoria = null;

if ($slug !== '') {
    $stmt = $pdo->prepare("SELECT * FROM categorias WHERE slug = ?");
    $stmt->execute([$slug]);
    $categoria = $stmt->fetch();

    if (!$categoria) {
        header('Location: catalogo.php');
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE ativo = 1 AND categoria_id = ? ORDER BY id DESC");
    $stmt->execute([$categoria['id']]);
} else {
    $stmt = $pdo->query("SELECT * FROM produtos WHERE ativo = 1 ORDER BY id DESC");
}

$produtos = $stmt->fetchAll();
$titulo = $categoria ? $categoria['nome'] : 'Todos os produtos';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo) ?> - Véio Chico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include 'cabecalho.php'; ?>

    <section class="catalogo-section">
        <div class="container">

            <div class="text-center mb-4">
                <h1 class="section-title"><?= htmlspecialchars($titulo) ?></h1>
                <p class="section-subtitle">Entre em contato pelo WhatsApp para pedir o seu.</p>
            </div>

            <div class="filtros">
                <a href="catalogo.php" class="filtro <?= !$categoria ? 'ativo' : '' ?>">Todos</a>
                <?php foreach ($categorias as $c): ?>
                    <a href="catalogo.php?cat=<?= htmlspecialchars($c['slug']) ?>"
                       class="filtro <?= ($categoria && $categoria['id'] == $c['id']) ? 'ativo' : '' ?>">
                        <?= htmlspecialchars($c['nome']) ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <?php if (!$produtos): ?>
                <p class="text-center section-subtitle">Nenhum produto nesta categoria ainda.</p>
            <?php endif; ?>

            <div class="lista">
                <?php foreach ($produtos as $p): ?>
                    <?php
                        $msg  = 'Olá, tenho interesse no produto: ' . $p['nome'];
                        $link = 'https://wa.me/' . $whatsapp . '?text=' . urlencode($msg);
                    ?>
                    <div class="produto">
                        <img src="uploads/<?= htmlspecialchars($p['imagem']) ?>" alt="<?= htmlspecialchars($p['nome']) ?>">
                        <h3><?= htmlspecialchars($p['nome']) ?></h3>
                        <p class="preco">R$ <?= number_format($p['preco'], 2, ',', '.') ?></p>
                        <a class="botao-whats" href="<?= $link ?>" target="_blank" rel="noopener noreferrer">
                            Chamar no WhatsApp
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>

</body>
</html>