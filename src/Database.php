<?php
namespace App;

use PDO;

class Database {
    private $host = 'localhost';
    private $dbname = 'ecoride';
    private $username = 'administrateur';
    private $password = '@dminEc0rid3!';
    private $pdo;

    public function connect() {
        if ($this->pdo == null) {
            try {
                $this->pdo = new PDO(
                    "mysql:host=$this->host;dbname=$this->dbname",
                    $this->username,
                    $this->password
                );
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Erreur de connexion : " . $e->getMessage();
            }
        }
        return $this->pdo;
    }
}
?>