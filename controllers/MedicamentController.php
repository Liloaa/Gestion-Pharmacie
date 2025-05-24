<?php
class MedicamentController {
    private $model; //stocke une instance de la classe medicament

    public function __construct($db) {     //crée une instance de Medicament avec la conn
        $this->model = new Medicament($db);  
    }

    public function index() {
        if (isset($_GET['search'])) {   //on vérifie si un paramètre search est présent dans l’URL ($_GET['search'])
            $stmt = $this->model->search($_GET['search']);
        } else {
            $stmt = $this->model->read();
        }
        include_once 'views/medicament/list.php';
    }

    public function lowStock() {  //Appelle getLowStock() et affiche une nouvelle vue pour les médicaments en rupture de stock.
        $stmt = $this->model->getLowStock();
        include_once 'views/medicament/low_stock.php';
    }

    public function create() {
        if ($_POST) {    //check si un formulaire est sourni
            $this->model->numMedoc = $_POST['numMedoc'];
            $this->model->Design = $_POST['Design'];
            $this->model->prix_unitaire = $_POST['prix_unitaire'];
            $this->model->stock = 0; // Stock initial = 0
            if ($this->model->create()) {
                header("Location: index.php");
            }
        }
        include_once 'views/medicament/create.php';
    }

    public function edit($numMedoc) {
        if ($_POST) {
            $this->model->numMedoc = $numMedoc;
            $this->model->Design = $_POST['Design'];
            $this->model->prix_unitaire = $_POST['prix_unitaire'];
            $this->model->stock = $_POST['stock'];
            if ($this->model->update()) {
                header("Location: index.php");
            }
        }
        $this->model->numMedoc = $numMedoc;
        $stmt = $this->model->read(); // On réutilise read pour récupérer les données actuelles
        include_once 'views/medicament/edit.php';
    }

    public function delete($numMedoc) {
        $this->model->numMedoc = $numMedoc;
        if ($this->model->delete()) {
            header("Location: index.php?controller=medicament");
        } else {
            echo "<script>alert('Erreur : Le médicament est utilisé dans des achats ou des entrées et ne peut pas être supprimé !');</script>";
            header("Location: index.php?controller=medicament");
        }
    }
}
?>