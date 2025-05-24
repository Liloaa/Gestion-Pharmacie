<?php
class Database {                 //clase database pour se connecter a mysql de maniere securise
    private $host = "localhost";
    private $db_name = "pharmacie";
    private $username = "root"; // Par défaut dans XAMPP
    private $password = "";     // Par défaut vide dans XAMPP
    public $conn;               //var qui stock la connexion

    public function getConnection() {   //gerer la connexion mysql
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password); //etablit la connexion avec mysql en utilisant pdo
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
        }
        return $this->conn;
    }
}
?>