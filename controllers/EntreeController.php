<?php
class EntreeController {
    private $model;
    private $medicamentModel;

    public function __construct($db) {
        $this->model = new Entree($db);
        $this->medicamentModel = new Medicament($db); // Pour la liste des médicaments
    }

    public function index() {
        $stmt = $this->model->read();
        include_once 'views/entree/list.php';
    }

    public function create() {
        if ($_POST) {
            $this->model->numEntree = $_POST['numEntree'];
            $this->model->numMedoc = $_POST['numMedoc'];
            $this->model->stockEntree = $_POST['stockEntree'];
            $this->model->dateEntree = $_POST['dateEntree'];
            if ($this->model->create()) {
                header("Location: index.php?controller=entree");
            }
        }
        $medicaments = $this->medicamentModel->read();
        include_once 'views/entree/create.php';
    }

    public function edit($numEntree) {
        if ($_POST) {
            $this->model->numEntree = $numEntree;
            $this->model->numMedoc = $_POST['numMedoc'];
            $this->model->stockEntree = $_POST['stockEntree'];
            $this->model->dateEntree = $_POST['dateEntree'];
            $oldStockEntree = $_POST['oldStockEntree'];
            if ($this->model->update($oldStockEntree)) {
                header("Location: index.php?controller=entree");
            }
        }
        $this->model->numEntree = $numEntree;
        $stmt = $this->model->read();
        $medicaments = $this->medicamentModel->read();
        include_once 'views/entree/edit.php';
    }

    public function delete($numEntree) {
        $this->model->numEntree = $numEntree;
        if ($this->model->delete()) {
            header("Location: index.php?controller=entree");
        }
    }
}
?>