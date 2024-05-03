<?php
require_once(__DIR__ . '/../service/service.php');
require_once(__DIR__ . '/../security/security.php');
use Services\ActionHandler;

class Router {
    public static function dbConnect() {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=esiea_web', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la connexion à la base de données : ' . $e->getMessage());
        }
    }
}

?>
