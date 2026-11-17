<?php
require 'conexao.php';

$tipo = $_GET['tipo'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM produtos WHERE tipo = ? AND ativo = 1");
$stmt->execute([$tipo]);
$produtos = $stmt->fetchAll();

$whatsapp = '5514996937933';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Catálogo</title>
  <link rel="stylesheet" href="estilo.css">
</head>
<body>

  <header>
    <h1>Minha Empresa</h1>
  </header>

  <h2><?= $tipo === 'tabua' ? 'Tábuas' : 'Quadros' ?></h2>

  <div class="lista">
    <?php foreach ($produtos as $p): ?>
      <?php
        $msg = 'Olá, tenho interesse no produto: ' . $p['nome'];
        $link = 'https://wa.me/5514996937933' . $whatsapp . '?text=' . urlencode($msg);
      ?>
      <div class="produto">
        <img src="uploads/<?= htmlspecialchars($p['imagem']) ?>" alt="<?= htmlspecialchars($p['nome']) ?>">
        <h3><?= htmlspecialchars($p['nome']) ?></h3>
        <p>R$ <?= number_format($p['preco'], 2, ',', '.') ?></p>
        <a class="botao-whats" href="<?= $link ?>" target="_blank">Chamar no WhatsApp</a>
      </div>
    <?php endforeach; ?>
  </div>

</body>
</html>