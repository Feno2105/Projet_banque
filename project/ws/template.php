<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Projet Banque' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="./assets/css/style.css" rel="stylesheet">
</head>
<body class="bank-app">
    <div class="main-container" style="display: flex;">
        <div>
        <!-- Navigation -->
        <?php include __DIR__ . '/fragment/navigation.php'; ?>
        </div>
        <!-- Contenu principal -->
        <main class="main-content" style="padding: 20px;">
            <div class="content-container">
                <?php include $content; ?>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/main.js"></script>
</body>
</html>