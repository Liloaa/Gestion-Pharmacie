<?php
session_start();
include_once 'header.php';
?>

<h2>Liste des achats</h2>
<div class="alert alert-info" role="alert">
    <strong>Recette totale accumulée :</strong> <?php echo number_format($totalRevenue, 2); ?> Ar
</div>

<h3>Top 5 des médicaments les plus vendus</h3>
<table class="table table-bordered mb-5">
    <thead>
        <tr>
            <th>Numéro</th>
            <th>Désignation</th>
            <th>Quantité totale vendue</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($topMedicaments->rowCount() > 0) { ?>
            <?php while ($row = $topMedicaments->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?php echo $row['numMedoc']; ?></td>
                    <td><?php echo $row['Design']; ?></td>
                    <td><?php echo $row['total_sold']; ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr><td colspan="3">Aucun achat enregistré.</td></tr>
        <?php } ?>
    </tbody>
</table>

<a href="index.php?controller=achat&action=create" class="btn btn-success mb-3">Ajouter</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Numéro</th>
            <th>Client</th>
            <th>Date</th>
            <th>Médicaments</th>
            <th>Actions</th>
            <th>Facture</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($stmt->rowCount() > 0) { ?>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?php echo $row['numAchat']; ?></td>
                    <td><?php echo $row['nomClient']; ?></td>
                    <td><?php echo $row['dateAchat']; ?></td>
                    <td>
                        <?php
                        $detailsStmt = $this->model->readDetails($row['numAchat']);
                        $details = [];
                        while ($detail = $detailsStmt->fetch(PDO::FETCH_ASSOC)) {
                            $details[] = $detail['Design'] . " (" . $detail['nbr'] . ")";
                        }
                        echo implode(", ", $details);
                        ?>
                    </td>
                    <td>
                        <a href="index.php?controller=achat&action=edit&numAchat=<?php echo $row['numAchat']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="index.php?controller=achat&action=delete&numAchat=<?php echo $row['numAchat']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Confirmer la suppression ?');">Supprimer</a>
                    </td>
                    <td>
                        <a href="factures/facture_<?php echo $row['numAchat']; ?>.pdf" target="_blank" class="btn btn-info btn-sm">Voir facture</a>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr><td colspan="6">Aucun achat trouvé.</td></tr>
        <?php } ?>
    </tbody>
</table>

<h3>Recettes mensuelles (5 derniers mois)</h3>
<div class="mb-5">
    <canvas id="monthlyRevenueChart" height="100"></canvas>
</div>

<?php
// Préparer les données pour le graphique
$labels = [];
$data = [];
$allMonths = [];
$currentDate = new DateTime();
for ($i = 4; $i >= 0; $i--) {
    $monthDate = (clone $currentDate)->modify("-$i months");
    $allMonths[$monthDate->format('Y-m')] = 0;
    $labels[] = $monthDate->format('Y-m');
}
// Récupérer les revenus mensuels depuis la base de données
//result = $monthlyRevenue->fetch(PDO::FETCH_ASSOC);
while ($row = $monthlyRevenue->fetch(PDO::FETCH_ASSOC)) {
    $allMonths[$row['month']] = (float)$row['revenue'];
}

foreach ($allMonths as $month => $revenue) {
    $data[] = $revenue;
}
?>

<script>
    const ctx = document.getElementById('monthlyRevenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Recette mensuelle (Ar)',
                data: <?php echo json_encode($data); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Recette (Ar)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Mois'
                    }
                }
            }
        }
    });
</script>

<?php if (!empty($_SESSION['alert'])): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['alert']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['alert']); ?>
<?php endif; ?>

<?php include_once 'footer.php'; ?>