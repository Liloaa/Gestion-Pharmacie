<?php include_once 'header.php'; ?>

<h2>Liste des entrées</h2>
<a href="index.php?controller=entree&action=create" class="btn btn-success mb-3">Ajouter</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Numéro</th>
            <th>Médicament</th>
            <th>Quantité</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($stmt->rowCount() > 0) { ?>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?php echo $row['numEntree']; ?></td>
                    <td><?php echo $row['numMedoc']; ?></td>
                    <td><?php echo $row['stockEntree']; ?></td>
                    <td><?php echo $row['dateEntree']; ?></td>
                    <td>
                        <a href="index.php?controller=entree&action=edit&numEntree=<?php echo $row['numEntree']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="index.php?controller=entree&action=delete&numEntree=<?php echo $row['numEntree']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Confirmer la suppression ?');">Supprimer</a>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr><td colspan="5">Aucune entrée trouvée.</td></tr>
        <?php } ?>
    </tbody>
</table>

<?php include_once 'footer.php'; ?>