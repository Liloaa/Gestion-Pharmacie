<?php
class Achat {
    private $conn;
    private $table = "ACHAT";

    public $numAchat;
    public $nomClient;
    public $dateAchat;
    public $details = []; // Tableau pour stocker les détails de l'achat (médicaments et nbrs)

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        // Insérer l'achat dans la table achat
        $query = "INSERT INTO " . $this->table . " SET numAchat=:numAchat, nomClient=:nomClient, dateAchat=:dateAchat";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":numAchat", $this->numAchat);
        $stmt->bindParam(":nomClient", $this->nomClient);
        $stmt->bindParam(":dateAchat", $this->dateAchat);

        if ($stmt->execute()) {
            // Vérifier le stock pour chaque médicament
            foreach ($this->details as $detail) {
                $query = "SELECT stock FROM MEDICAMENT WHERE numMedoc = :numMedoc";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":numMedoc", $detail['numMedoc']);  //bindParam lie la var par reference ce qui permet de modifier sa val avant de l'execution
                $stmt->execute();
                $stock = $stmt->fetchColumn();

                if ($stock < $detail['nbr']) {
                    // Annuler l'achat si le stock est insuffisant
                    $this->delete();
                    return -1; // Stock insuffisant
                }
            }

            // Insérer les détails dans ACHAT_DETAILS
            foreach ($this->details as $detail) {
                $query = "INSERT INTO ACHAT_DETAILS SET numAchat=:numAchat, numMedoc=:numMedoc, nbr=:nbr";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":numAchat", $this->numAchat);
                $stmt->bindParam(":numMedoc", $detail['numMedoc']);
                $stmt->bindParam(":nbr", $detail['nbr']);
                $stmt->execute();

                // Mettre à jour le stock
                $query = "UPDATE MEDICAMENT SET stock = stock - :nbr WHERE numMedoc = :numMedoc";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":nbr", $detail['nbr']);
                $stmt->bindParam(":numMedoc", $detail['numMedoc']);
                $stmt->execute();
            }
            return true;
        }
        return false;
    }
    
    public function read() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    //méthode pour récupérer les détails d’un achat (médicaments et nbrs)
    public function readDetails($numAchat) {
        $query = "SELECT ad.numMedoc, m.Design, ad.nbr 
                  FROM ACHAT_DETAILS ad 
                  JOIN MEDICAMENT m ON ad.numMedoc = m.numMedoc 
                  WHERE ad.numAchat = :numAchat";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":numAchat", $numAchat);
        $stmt->execute();
        return $stmt;
    }

    public function update($oldDetails) {
        // Mettre à jour les informations de l'achat
        $query = "UPDATE " . $this->table . " SET nomClient=:nomClient, dateAchat=:dateAchat WHERE numAchat=:numAchat";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":numAchat", $this->numAchat);
        $stmt->bindParam(":nomClient", $this->nomClient);
        $stmt->bindParam(":dateAchat", $this->dateAchat);

        if ($stmt->execute()) {
            // Restaurer le stock pour les anciens détails
            foreach ($oldDetails as $detail) {
                $query = "UPDATE MEDICAMENT SET stock = stock + :nbr WHERE numMedoc = :numMedoc";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":nbr", $detail['nbr']);
                $stmt->bindParam(":numMedoc", $detail['numMedoc']);
                $stmt->execute();
            }

            // Vérifier le stock pour les nouveaux détails
            foreach ($this->details as $detail) {
                $query = "SELECT stock FROM MEDICAMENT WHERE numMedoc = :numMedoc";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":numMedoc", $detail['numMedoc']);
                $stmt->execute();
                $stock = $stmt->fetchColumn();

                if ($stock < $detail['nbr']) {
                    // Annuler les modifications si le stock est insuffisant
                    $this->update($oldDetails); // Restaurer les anciens détails
                    return -1; // Stock insuffisant
                }
            }

            // Supprimer les anciens détails
            $query = "DELETE FROM ACHAT_DETAILS WHERE numAchat = :numAchat";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":numAchat", $this->numAchat);
            $stmt->execute();

            // Insérer les nouveaux détails
            foreach ($this->details as $detail) {
                $query = "INSERT INTO ACHAT_DETAILS SET numAchat=:numAchat, numMedoc=:numMedoc, nbr=:nbr";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":numAchat", $this->numAchat);
                $stmt->bindParam(":numMedoc", $detail['numMedoc']);
                $stmt->bindParam(":nbr", $detail['nbr']);
                $stmt->execute();

                // Mettre à jour le stock
                $query = "UPDATE MEDICAMENT SET stock = stock - :nbr WHERE numMedoc = :numMedoc";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":nbr", $detail['nbr']);
                $stmt->bindParam(":numMedoc", $detail['numMedoc']);
                $stmt->execute();
            }
            return true;
        }
        return false;
    }

    public function delete() {
        // Récupérer les détails pour restaurer le stock
        $detailsStmt = $this->readDetails($this->numAchat);
        $details = [];
        while ($row = $detailsStmt->fetch(PDO::FETCH_ASSOC)) {
            $details[] = $row;
        }

        // Supprimer d'abord les détails dans ACHAT_DETAILS
        $query = "DELETE FROM ACHAT_DETAILS WHERE numAchat = :numAchat";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":numAchat", $this->numAchat);
        $stmt->execute();

        // Supprimer l'achat
        $query = "DELETE FROM " . $this->table . " WHERE numAchat = :numAchat";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":numAchat", $this->numAchat);

        if ($stmt->execute()) {
            // Restaurer le stock
            foreach ($details as $detail) {
                $query = "UPDATE MEDICAMENT SET stock = stock + :nbr WHERE numMedoc = :numMedoc";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":nbr", $detail['nbr']);
                $stmt->bindParam(":numMedoc", $detail['numMedoc']);
                $stmt->execute();
            }
            return true;
        }
        return false;
    }

    // methode pour afficher details de chaque achat 
    public function getAchatDetails($numAchat) {
        $query = "SELECT a.*, ad.numMedoc, ad.nbr, m.Design, m.prix_unitaire 
                  FROM " . $this->table . " a 
                  JOIN ACHAT_DETAILS ad ON a.numAchat = ad.numAchat
                  JOIN MEDICAMENT m ON ad.numMedoc = m.numMedoc 
                  WHERE a.numAchat = :numAchat";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":numAchat", $numAchat);
        $stmt->execute();
        return $stmt;
    }

    // méthode pour calculer la recette totale des ventes
    public function getTotalRevenue() {
        $query = "SELECT SUM(ad.nbr * m.prix_unitaire) as total_revenue 
                  FROM ACHAT_DETAILS ad 
                  JOIN MEDICAMENT m ON ad.numMedoc = m.numMedoc";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_revenue'] ?? 0; // Retourne 0 si aucun achat
    }

    // méthode pour récupérer les 5 médicaments les plus vendus
    public function getTopVenteMedicaments() {
        $query = "SELECT ad.numMedoc, m.Design, SUM(ad.nbr) as total_sold 
                  FROM ACHAT_DETAILS ad
                  JOIN MEDICAMENT m ON ad.numMedoc = m.numMedoc 
                  GROUP BY ad.numMedoc, m.Design 
                  ORDER BY total_sold DESC 
                  LIMIT 5";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // méthode pour récupérer les recettes mensuelles (5 derniers mois)
    public function getMonthlyRevenue() {
        $query = "SELECT 
                    CONCAT(YEAR(a.dateAchat), '-', LPAD(MONTH(a.dateAchat), 2, '0')) as month,
                    SUM(ad.nbr * m.prix_unitaire) as revenue
                  FROM " . $this->table . " a 
                  JOIN ACHAT_DETAILS ad ON a.numAchat = ad.numAchat
                  JOIN MEDICAMENT m ON ad.numMedoc = m.numMedoc
                  WHERE a.dateAchat >= DATE_SUB(CURDATE(), INTERVAL 5 MONTH)
                  GROUP BY YEAR(a.dateAchat), MONTH(a.dateAchat)
                  ORDER BY YEAR(a.dateAchat) DESC, MONTH(a.dateAchat) DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>