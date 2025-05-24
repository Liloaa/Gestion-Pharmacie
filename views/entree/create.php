<?php include_once 'header.php'; ?>

<h2>Ajouter une entrée</h2>
<form method="post">
    <div class="mb-3">
        <label>Numéro d'entrée</label>
        <input type="text" name="numEntree" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Médicament</label>
        <select name="numMedoc" class="form-control" required>
            <?php while ($row = $medicaments->fetch(PDO::FETCH_ASSOC)) { ?>
                <option value="<?php echo $row['numMedoc']; ?>"><?php echo $row['Design']; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Quantité</label>
        <input type="number" name="stockEntree" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Date</label>
        <input type="date" name="dateEntree" class="form-control" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
</form>

<?php include_once 'footer.php'; ?>