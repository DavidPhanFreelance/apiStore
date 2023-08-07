<?php

$stores = array();

// Gérer les requêtes GET, POST et PUT
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Récupérer la liste des magasins
        echo json_encode($stores);
        break;
    case 'POST':
        // Ajouter un nouveau magasin
        $data = json_decode(file_get_contents("php://input"), true);
        $stores[] = $data;
        echo json_encode(array('message' => 'Magasin ajouté avec succès.'));
        break;
    case 'PUT':
        // Mettre à jour un magasin existant
        $data = json_decode(file_get_contents("php://input"), true);
        $storeId = $data['id'];
        if (isset($stores[$storeId])) {
            $stores[$storeId] = $data;
            echo json_encode(array('message' => 'Magasin mis à jour avec succès.'));
        } else {
            echo json_encode(array('message' => 'Le magasin n\'existe pas.'));
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(array('message' => 'Méthode non autorisée.'));
        break;
}

?>