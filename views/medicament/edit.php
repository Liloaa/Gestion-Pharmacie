<?php include_once 'header.php'; ?>

<h2>Modifier un médicament</h2>
<?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
    if ($row['numMedoc'] == $this->model->numMedoc) { ?>
        <form method="post">
            <div class="mb-3">
                <label>Numéro (non modifiable)</label>
                <input type="text" name="numMedoc" class="form-control" value="<?php echo $row['numMedoc']; ?>" disabled>
            </div>
            <div class="mb-3">
                <label>Désignation</label>
                <input type="text" name="Design" class="form-control" value="<?php echo $row['Design']; ?>" required>
            </div>
            <div class="mb-3">
                <label>Prix unitaire</label>
                <input type="number" name="prix_unitaire" class="form-control" value="<?php echo $row['prix_unitaire']; ?>" required>
            </div>
            <div class="mb-3">
                <label>Stock</label>
                <input type="number" name="stock" class="form-control" value="<?php echo $row['stock']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
<?php } } ?>

<?php include_once 'footer.php'; ?>