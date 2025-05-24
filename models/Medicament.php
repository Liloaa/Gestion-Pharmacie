<?php
class Medicament {    
    private $conn;  //stocke la conn a la base recue via le constructeur
    private $table = "MEDICAMENT"; 

    public $numMedoc;
    public $Design;
    public $prix_unitaire;
    public $stock;

    public function __construct($db) {    //initiale la classe avec la conn passée en param
        $this->conn = $db;
    }

    // Créer un médicament
    public function create() {
        $query = "INSERT INTO " . $this->table . " SET numMedoc=:numMedoc, Design=:Design, prix_unitaire=:prix_unitaire, stock=:stock";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":numMedoc", $this->numMedoc);
        $stmt->bindParam(":Design", $this->Design);
        $stmt->bindParam(":prix_unitaire", $this->prix_unitaire);
        $stmt->bindParam(":stock", $this->stock);

        return $stmt->execute();
    }

    // Lister tous les médicaments
    public function read() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt; 
    }

    public function search($keyword) {   //chercher like % keywird %
        $query = "SELECT * FROM " . $this->table . " WHERE Design LIKE :keyword";
        $stmt = $this->conn->prepare($query);
        $keyword = "%" . $keyword . "%";
        $stmt->bindParam(":keyword", $keyword);
        $stmt->execute();
        return $stmt;
    }

    public function getLowStock() {  //getLowStock() : Sélectionne tous les médicaments ayant un stock inférieur à 5.
        $query = "SELECT * FROM " . $this->table . " WHERE stock < 5";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->table . " SET Design=:Design, prix_unitaire=:prix_unitaire, stock=:stock WHERE numMedoc=:numMedoc";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":Design", $this->Design);
        $stmt->bindParam(":prix_unitaire", $this->prix_unitaire);
        $stmt->bindParam(":stock", $this->stock);
        $stmt->bindParam(":numMedoc", $this->numMedoc);
        return $stmt->execute();
    }

    public function delete() {
        // Vérifier si le médicament est utilisé dans ACHAT_DETAILS
        $query = "SELECT COUNT(*) FROM ACHAT_DETAILS WHERE numMedoc = :numMedoc";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":numMedoc", $this->numMedoc);
        $stmt->execute();
        $countAchatDetails = $stmt->fetchColumn();
         
        // Étape 2 : Supprimer les enregistrements dans ENTREE qui font référence à ce numMedoc
        $query = "DELETE FROM ENTREE WHERE numMedoc = :numMedoc";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":numMedoc", $this->numMedoc);
        $stmt->execute();
        $countEntree = $stmt->fetchColumn();

        // Si le médicament est utilisé dans ACHAT_DETAILS ou ENTREE, on ne peut pas le supprimer
        if ($countAchatDetails > 0 || $countEntree > 0) {
            return false; // Le médicament est utilisé, on ne le supprime pas
        }
    
        $query = "DELETE FROM " . $this->table . " WHERE numMedoc=:numMedoc";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":numMedoc", $this->numMedoc);
        return $stmt->execute();
    }
}
?>