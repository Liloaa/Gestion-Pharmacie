<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 80%; margin: auto; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .total { margin-top: 20px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Facture</h1>
            <p>Pharmacie - Tanambao</p>
        </div>
        <p><strong>Numéro d'achat :</strong> <?php echo $achats[0]['numAchat']; ?></p>
        <p><strong>Client :</strong> <?php echo $achats[0]['nomClient']; ?></p>
        <p><strong>Date :</strong> <?php echo $achats[0]['dateAchat']; ?></p>
        <table class="table">
            <thead>
                <tr>
                    <th>Médicament</th>
                    <th>Nombre</th>
                    <th>Prix unitaire</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0;
                foreach ($achats as $achat) { 
                    $subtotal = $achat['nbr'] * $achat['prix_unitaire'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?php echo $achat['Design']; ?></td>
                        <td><?php echo $achat['nbr']; ?></td>
                        <td><?php echo $achat['prix_unitaire']; ?> Ar</td>
                        <td><?php echo $subtotal; ?> Ar</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="total">
            <p>Total : <?php echo $total; ?> Ar</p>
        </div>
    </div>
</body>
</html>