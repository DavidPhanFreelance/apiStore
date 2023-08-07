<?php


class StoreAPI
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getStores()
    {
        $sql = "SELECT * FROM stores"; // Remplacez "stores" par le nom de votre table de magasins
        $result = $this->db->query($sql);

        if ($result) {
            $stores = $result->fetchAll(PDO::FETCH_ASSOC);
            header('Content-Type: application/json');
            echo json_encode($stores);
        } else {
            http_response_code(500);
            echo json_encode(array('message' => 'Erreur lors de la récupération des magasins.'));
        }
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            $this->getStores();
        } else {
            http_response_code(405);
            echo json_encode(array('message' => 'Méthode non autorisée.'));
        }
    }
}

// Connexion à la base de données
try {
    $db = new PDO('mysql:host=localhost;dbname=nom_de_votre_base_de_donnees', 'votre_utilisateur', 'votre_mot_de_passe');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
    exit;
}

$api = new StoreAPI($db);
$api->handleRequest();