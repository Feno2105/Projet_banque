<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Projet Banque' ?></title>
</head>
<body>
    <?php include __DIR__ . '/fragment/navigation.php'; ?>
    <main>
        <?php include $content; ?>
    </main>
</body>
</html>