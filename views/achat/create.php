<?php include_once 'header.php'; ?>

<h2>Ajouter un achat</h2>
<form method="post">
    <div class="mb-3">
        <label>Numéro d'achat</label>
        <input type="text" name="numAchat" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Nom du client</label>
        <input type="text" name="nomClient" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Date</label>
        <input type="date" name="dateAchat" class="form-control" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" required >
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
                <tr>
                    <td>  <!-- Le formulaire utilise des champs sous forme de tableau (numMedoc[], nbr[]) pour permettre plusieurs médicaments. Un tableau dynamique permet d’ajouter ou de supprimer des lignes de médicaments avec JavaScript. -->
                        <select name="numMedoc[]" class="form-control" required>
                            <option value="">Sélectionner un médicament</option>
                            <?php while ($row = $medicaments->fetch(PDO::FETCH_ASSOC)) { ?>
                                <option value="<?php echo $row['numMedoc']; ?>"><?php echo $row['Design']; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>
                        <input type="number" name="nbr[]" class="form-control" min="1" required>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row">Supprimer</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-primary" id="addMedicament">Ajouter un médicament</button>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
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
                while ($row = $medicaments->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . $row['numMedoc'] . '">' . $row['Design'] . '</option>';
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

<?php include_once 'footer.php'; ?>