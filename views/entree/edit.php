<?php include_once 'header.php'; ?>

<h2>Modifier une entrée</h2>
<?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
    if ($row['numEntree'] == $this->model->numEntree) { ?>
        <form method="post">
            <div class="mb-3">
                <label>Numéro d'entrée (non modifiable)</label>
                <input type="text" name="numEntree" class="form-control" value="<?php echo $row['numEntree']; ?>" disabled>
            </div>
            <div class="mb-3">
                <label>Médicament</label>
                <select name="numMedoc" class="form-control" required>
                    <?php while ($med = $medicaments->fetch(PDO::FETCH_ASSOC)) { ?>
                        <option value="<?php echo $med['numMedoc']; ?>" <?php if ($med['numMedoc'] == $row['numMedoc']) echo 'selected'; ?>>
                            <?php echo $med['Design']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Quantité</label>
                <input type="number" name="stockEntree" class="form-control" value="<?php echo $row['stockEntree']; ?>" required>
                <input type="hidden" name="oldStockEntree" value="<?php echo $row['stockEntree']; ?>">
            </div>
            <div class="mb-3">
                <label>Date</label>
                <input type="date" name="dateEntree" class="form-control" value="<?php echo htmlspecialchars($achat['dateAchat']); ?>" max="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
<?php } } ?>

<?php include_once 'footer.php'; ?>