<?php
require_once 'vendor/autoload.php'; // Inclure l'autoloader de Composer pour charger DOMPDF
use Dompdf\Dompdf; //importe la classe dompdf

class AchatController {
    private $model;
    private $medicamentModel;

    public function __construct($db) {
        $this->model = new Achat($db);
        $this->medicamentModel = new Medicament($db);
    }

    public function index() {
        $stmt = $this->model->read();
        $totalRevenue = $this->model->getTotalRevenue(); // Récupérer la recette totale
        $topMedicaments = $this->model->getTopVenteMedicaments(); // Récupérer les 5 médicaments les plus vendus
        $monthlyRevenue = $this->model->getMonthlyRevenue(); // Récupérer les recettes mensuelles
        include_once 'views/achat/list.php';
    }

    public function create() {   //Récupère les médicaments et nbrs depuis le formulaire (tableaux numMedoc et nbr), les stocke dans $this->model->details, et appelle create() sur le modèle
        if ($_POST) {
            $this->model->numAchat = $_POST['numAchat'];
            $this->model->nomClient = $_POST['nomClient'];
            $this->model->dateAchat = $_POST['dateAchat'];

            // Récupérer les détails (médicaments et nbrs)
            $this->model->details = [];
            $numMedocs = $_POST['numMedoc'] ?? [];
            $nbrs = $_POST['nbr'] ?? [];
            for ($i = 0; $i < count($numMedocs); $i++) {
                if (!empty($numMedocs[$i]) && !empty($nbrs[$i])) {
                    $this->model->details[] = [
                        'numMedoc' => $numMedocs[$i],
                        'nbr' => $nbrs[$i]
                    ];
                }
            }

            $result = $this->model->create();
            if ($result === -1){
                echo "<script>alert('Stock insuffisant pour un ou plusieurs médicaments !');</script>";
            } elseif ($result) {
                // Générer la facture après l'achat
                $this->genererFacture($this->model->numAchat);
                header("Location: index.php?controller=achat");
            }
        }
        $medicaments = $this->medicamentModel->read();
        include_once 'views/achat/create.php';
    }

    public function edit($numAchat) {       //Récupère les nouveaux détails, récupère les anciens détails pour la mise à jour du stock, et appelle update()
        if ($_POST) {
            $this->model->numAchat = $numAchat;
            $this->model->nomClient = $_POST['nomClient'];
            $this->model->dateAchat = $_POST['dateAchat'];

            // Récupérer les nouveaux détails
            $this->model->details = [];
            $numMedocs = $_POST['numMedoc'] ?? [];
            $nbrs = $_POST['nbr'] ?? [];
            for ($i = 0; $i < count($numMedocs); $i++) {
                if (!empty($numMedocs[$i]) && !empty($nbrs[$i])) {
                    $this->model->details[] = [
                        'numMedoc' => $numMedocs[$i],
                        'nbr' => $nbrs[$i]
                    ];
                }
            }

            // Récupérer les anciens détails pour la mise à jour du stock
            $oldDetailsStmt = $this->model->readDetails($numAchat);
            $oldDetails = [];
            while ($row = $oldDetailsStmt->fetch(PDO::FETCH_ASSOC)) {
                $oldDetails[] = [
                    'numMedoc' => $row['numMedoc'],
                    'nbr' => $row['nbr']
                ];
            }

            $result = $this->model->update($oldDetails);
            if ($result === -1) {
                echo "<script>alert('Stock insuffisant après modification !');</script>";
            } elseif ($result) {
                // Regénérer la facture
                $this->genererFacture($numAchat);
                header("Location: index.php?controller=achat");
            }
        }
        $this->model->numAchat = $numAchat;
        $stmt = $this->model->read();
        $detailsStmt = $this->model->readDetails($numAchat);
        $medicaments = $this->medicamentModel->read();
        include_once 'views/achat/edit.php';
    }

    public function delete($numAchat) {    //Supprime l’achat et ses détails, et supprime la facture associée
        $this->model->numAchat = $numAchat;
        if ($this->model->delete()) {
            // Supprimer la facture associée
            $facturePath = 'factures/facture_' . $numAchat . '.pdf';
            if (file_exists($facturePath)) {
                unlink($facturePath);
            }
            header("Location: index.php?controller=achat");
        }
    }

    private function genererFacture($numAchat) {         //Ajusté pour gérer plusieurs médicaments dans la facture.
        $achatStmt = $this->model->getAchatDetails($numAchat);
        $achats = [];
        while ($row = $achatStmt->fetch(PDO::FETCH_ASSOC)) {
            $achats[] = $row;
        }
        ob_start();
        include 'views/achat/facture.php';
        $html = ob_get_clean();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $output = $dompdf->output();

        file_put_contents('factures/facture_' . $numAchat . '.pdf', $output);
    }
}
?>