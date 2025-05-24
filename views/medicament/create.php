<?php include_once 'header.php'; ?>

<h2>Ajouter un médicament</h2>
<form method="post">
    <div class="mb-3">
        <label>Numéro</label>
        <input type="text" name="numMedoc" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Désignation</label>
        <input type="text" name="Design" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Prix unitaire</label>
        <input type="number" name="prix_unitaire" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
</form>

<?php include_once 'footer.php'; ?>