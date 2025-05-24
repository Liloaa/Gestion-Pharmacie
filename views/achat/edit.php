<?php include_once 'header.php'; ?>

<h2>Modifier un achat</h2>
<?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
    if ($row['numAchat'] == $this->model->numAchat) { ?>
        <form method="post">
            <div class="mb-3">
                <label>Numéro d'achat (non modifiable)</label>
                <input type="text" name="numAchat" class="form-control" value="<?php echo $row['numAchat']; ?>" disabled>
            </div>
            <div class="mb-3">
                <label>Nom du client</label>
                <input type="text" name="nomClient" class="form-control" value="<?php echo $row['nomClient']; ?>" required>
            </div>
            <div class="mb-3">
                <label>Date</label>
                <input type="date" name="dateAchat" class="form-control" value="<?php echo htmlspecialchars($achat['dateAchat']); ?>" max="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="mb-3">
                <label>Médicaments</label>
                <table class="table" id="medicamentTable">
                    <thead>
                        <tr>
                            <th>Médicament</th>
                            <th>Nombre</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($detail = $detailsStmt->fetch(PDO::FETCH_ASSOC)) { ?>
                            <tr>
                                <td>  <!-- Le formulaire de modification charge les détails existants de l’achat et permet d’ajouter ou de supprimer des médicaments -->
                                    <select name="numMedoc[]" class="form-control">
                                        <option value="">Sélectionner un médicament</option>
                                        <?php
                                        $medicaments->execute(); // Réinitialiser le curseur
                                        while ($med = $medicaments->fetch(PDO::FETCH_ASSOC)) { ?>
                                            <option value="<?php echo $med['numMedoc']; ?>" <?php if ($med['numMedoc'] == $detail['numMedoc']) echo 'selected'; ?>>
                                                <?php echo $med['Design']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="nbr[]" class="form-control" value="<?php echo $detail['nbr']; ?>" min="1">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-row">Supprimer</button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <button type="button" class="btn btn-primary" id="addMedicament">Ajouter un médicament</button>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>

        <script>
        document.getElementById('addMedicament').addEventListener('click', function() {
            const table = document.getElementById('medicamentTable').getElementsByTagName('tbody')[0];
            const row = table.insertRow();
            row.innerHTML = `
                <td>
                    <select name="numMedoc[]" class="form-control">
                        <option value="">Sélectionner un médicament</option>
                        <?php
                        $medicaments->execute(); // Réinitialiser le curseur
                        while ($med = $medicaments->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="' . $med['numMedoc'] . '">' . $med['Design'] . '</option>';
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <input type="number" name="nbr[]" class="form-control" min="1">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-row">Supprimer</button>
                </td>
            `;
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row')) {
                const row = e.target.closest('tr');
                if (document.getElementById('medicamentTable').getElementsByTagName('tbody')[0].rows.length > 1) {
                    row.remove();
                }
            }
        });
        </script>
<?php } } ?>

<?php include_once 'footer.php'; ?>