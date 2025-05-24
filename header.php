<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacie - Gestion</title>
    <link href="https://bootswatch.com/5/litera/bootstrap.min.css" rel="stylesheet">
    <!-- Inclure Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Pharmacie</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($_GET['controller']) && $_GET['controller'] == 'medicament' && (!isset($_GET['action']) || $_GET['action'] == 'index')) ? 'active' : ''; ?>" href="index.php?controller=medicament">Médicaments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($_GET['controller']) && $_GET['controller'] == 'medicament' && isset($_GET['action']) && $_GET['action'] == 'lowStock') ? 'active' : ''; ?>" href="index.php?controller=medicament&action=lowStock">Ruptures de stock</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($_GET['controller']) && $_GET['controller'] == 'entree') ? 'active' : ''; ?>" href="index.php?controller=entree">Entrées</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($_GET['controller']) && $_GET['controller'] == 'achat') ? 'active' : ''; ?>" href="index.php?controller=achat">Achats</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">