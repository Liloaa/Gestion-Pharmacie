<?php
class Entree {
    private $conn;
    private $table = "ENTREE";

    public $numEntree;
    public $numMedoc;
    public $stockEntree;
    public $dateEntree;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET numEntree=:numEntree, numMedoc=:numMedoc, stockEntree=:stockEntree, dateEntree=:dateEntree";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":numEntree", $this->numEntree);
        $stmt->bindParam(":numMedoc", $this->numMedoc);
        $stmt->bindParam(":stockEntree", $this->stockEntree);
        $stmt->bindParam(":dateEntree", $this->dateEntree);

        if ($stmt->execute()) {
            // Mise à jour du stock dans MEDICAMENT
            $query = "UPDATE MEDICAMENT SET stock = stock + :stockEntree WHERE numMedoc = :numMedoc";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":stockEntree", $this->stockEntree);
            $stmt->bindParam(":numMedoc", $this->numMedoc);
            return $stmt->execute();
        }
        return false;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function update($oldStockEntree) {
        // Mettre à jour l'entrée
        $query = "UPDATE " . $this->table . " SET numMedoc=:numMedoc, stockEntree=:stockEntree, dateEntree=:dateEntree WHERE numEntree=:numEntree";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":numEntree", $this->numEntree);
        $stmt->bindParam(":numMedoc", $this->numMedoc);
        $stmt->bindParam(":stockEntree", $this->stockEntree);
        $stmt->bindParam(":dateEntree", $this->dateEntree);

        if ($stmt->execute()) {
            // Ajuster le stock : retirer l'ancien stockEntree et ajouter le nouveau
            $query = "UPDATE MEDICAMENT SET stock = stock - :oldStockEntree + :newStockEntree WHERE numMedoc = :numMedoc";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":oldStockEntree", $oldStockEntree);
            $stmt->bindParam(":newStockEntree", $this->stockEntree);
            $stmt->bindParam(":numMedoc", $this->numMedoc);
            return $stmt->execute();
        }
        return false;
    }

    public function delete() {
        // Récupérer le stockEntree avant suppression pour ajuster le stock
        $query = "SELECT stockEntree, numMedoc FROM " . $this->table . " WHERE numEntree = :numEntree";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":numEntree", $this->numEntree);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stockEntree = $row['stockEntree'];
        $numMedoc = $row['numMedoc'];

        // Supprimer l'entrée
        $query = "DELETE FROM " . $this->table . " WHERE numEntree = :numEntree";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":numEntree", $this->numEntree);

        if ($stmt->execute()) {
            // Ajuster le stock : retirer le stockEntree
            $query = "UPDATE MEDICAMENT SET stock = stock - :stockEntree WHERE numMedoc = :numMedoc";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":stockEntree", $stockEntree);
            $stmt->bindParam(":numMedoc", $numMedoc);
            return $stmt->execute();
        }
        return false;
    }
}
?>