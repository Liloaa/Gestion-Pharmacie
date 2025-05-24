<?php include_once 'header.php'; ?>

<h2>Médicaments en rupture de stock (stock < 5)</h2>
<a href="index.php?controller=medicament" class="btn btn-primary mb-3">Retour à la liste</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Numéro</th>
            <th>Désignation</th>
            <th>Prix unitaire</th>
            <th>Stock</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($stmt->rowCount() > 0) { ?>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?php echo $row['numMedoc']; ?></td>
                    <td><?php echo $row['Design']; ?></td>
                    <td><?php echo $row['prix_unitaire']; ?></td>
                    <td><?php echo $row['stock']; ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr><td colspan="4">Aucun médicament en rupture de stock.</td></tr>
        <?php } ?>
    </tbody>
</table>

<?php include_once 'footer.php'; ?>