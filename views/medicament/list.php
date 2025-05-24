<?php include_once 'header.php'; ?>

<h2>Liste des médicaments</h2>
<div class="row mb-3">
    <div class="col-md-6">
        <a href="index.php?action=create" class="btn btn-success">Ajouter</a>
        <a href="index.php?controller=medicament&action=lowStock" class="btn btn-warning">Voir les ruptures de stock</a>
    </div>
    <div class="col-md-6">
        <form method="get" class="d-flex">
            <input type="hidden" name="controller" value="medicament">
            <input type="text" name="search" class="form-control me-2" placeholder="Rechercher par désignation" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </form>
    </div>
</div>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Numéro</th>
            <th>Désignation</th>
            <th>Prix unitaire</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($stmt->rowCount() > 0) { ?>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?php echo $row['numMedoc']; ?></td>
                    <td><?php echo $row['Design']; ?></td>
                    <td><?php echo $row['prix_unitaire'] . " Ar"; ?></td>
                    <td><?php echo $row['stock']; ?></td>
                    <td>
                        <a href="index.php?action=edit&numMedoc=<?php echo $row['numMedoc']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="index.php?action=delete&numMedoc=<?php echo $row['numMedoc']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Confirmer la suppression ?');">Supprimer</a>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr><td colspan="5">Aucun médicament trouvé.</td></tr>
        <?php } ?>
    </tbody>
</table>

<?php include_once 'footer.php'; ?>