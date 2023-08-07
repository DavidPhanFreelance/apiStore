<?php

class StoreAPI
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // GET @route: /store
    public function getStores()
    {
        $sql = "SELECT * FROM magasin";
        $result = $this->db->query($sql);

        if ($result) {
            $stores = $result->fetchAll(PDO::FETCH_ASSOC);
            header('Content-Type: application/json');
            echo json_encode($stores);
        }
        else {
            http_response_code(500);
            echo json_encode(array('message' => 'Erreur lors de la récupération des magasins.'));
        }
    }

    // GET @route: /store/{id}
    public function getStoreById($id)
    {
        $sql = "SELECT * FROM magasin WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $store = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($store) {
            header('Content-Type: application/json');
            echo json_encode($store);
        }
        else {
            http_response_code(404);
            echo json_encode(array('message' => 'Magasin non trouvé.'));
        }
    }

    // POST @route: /store
    // param : [nom]
    public function addStore()
    {
        if (isset($_POST['nom'])) {
            $nomMagasin = $_POST['nom'];
            $sql = "INSERT INTO magasin (nom) VALUES (:nom)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':nom', $nomMagasin, PDO::PARAM_STR);
            $success = $stmt->execute();

            if ($success) {
                http_response_code(201);
                echo json_encode(array('message' => 'Magasin ajouté avec succès.'));
            }
            else {
                http_response_code(500);
                echo json_encode(array('message' => "Une erreur est survenue lors de l'ajout du magasin."));
            }
        }
        else {
            $this->methodNotAllowed();
        }
    }

    // PUT @route: /store/{id}
    // param : [nom]
    public function changeNameStore($id)
    {

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            // Récupérer les données du corps de la requête (raw data)
            $rawData = file_get_contents("php://input");
            parse_str($rawData, $requestData); // Convertir les données en tableau associatif

            // Vérifier si le champ 'nom' est présent dans les données
            if (isset($requestData['nom'])) {
                $newName = $requestData['nom'];
                var_dump($newName);
                // ... (suite du code comme précédemment)
            } else {
                http_response_code(400);
                echo json_encode(array('message' => "Données incorrectes."));
            }
        } else {
            http_response_code(405);
            echo json_encode(array('message' => 'Méthode non autorisée.'));
        }


        if (isset($_POST['nom'])) {
            $nomMagasin = $_POST['nom'];
            var_dump($nomMagasin);
            die;
        }

        var_dump("na pas marché ");
        die;

        // Récupérer les données brutes de la requête PUT
        $data = file_get_contents("php://input");
        $putData = json_decode($data, true);
        var_dump($putData);

        if (isset($putData['nom'])) {
            $paramValue = $putData['nom'];
            // Faire quelque chose avec la valeur du paramètre
        }

        var_dump($paramValue);

        die;

        // Vérifier si le champ 'nom' est présent dans les données
        if (isset($requestData['nom'])) {
            $newName = $requestData['nom'];

            // Préparation de la requête SQL
            $sql = "UPDATE magasin SET nom = :newName WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':newName', $newName, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Exécution de la requête
            $success = $stmt->execute();

            if ($success) {
                http_response_code(200);
                echo json_encode(array('message' => 'Nom du magasin modifié avec succès.'));
            } else {
                http_response_code(500);
                echo json_encode(array('message' => "Une erreur est survenue lors de la modification du nom du magasin."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array('message' => "Données incorrectes."));
        }
    }

    public function handleRequest()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUrl = $_SERVER['REQUEST_URI'];
        $requestUrlParts = explode('/', trim($requestUrl, '/'));

        switch ($requestMethod) {
            case 'GET':
                if ($requestUrlParts[0] === 'store') {
                    if (count($requestUrlParts) === 1) {
                        $this->getStores();
                    } elseif (count($requestUrlParts) === 2) {
                        $id = intval($requestUrlParts[1]);
                        $this->getStoreById($id);
                    } else {
                        $this->notFound();
                    }
                } else {
                    $this->notFound();
                }
                break;
            case 'POST':
                if ($requestUrlParts[0] === 'store' && count($requestUrlParts) === 1) {
                    $this->addStore();
                } else {
                    $this->notFound();
                }
                break;
            case 'PUT':
                if (count($requestUrlParts) === 2) {
                    $id = intval($requestUrlParts[1]);
                    $this->changeNameStore($id);
                } else {
                    $this->notFound();
                }
                break;

            default:
                $this->methodNotAllowed();
        }
    }

    private function notFound()
    {
        http_response_code(404);
        echo json_encode(array('message' => 'Erreur 404.'));
    }

    private function methodNotAllowed()
    {
        http_response_code(405);
        echo json_encode(array('message' => 'Méthode non autorisée.'));
    }

}

// Connexion à la base de données
try {
    $db = new PDO('mysql:dbname=_store_api;host=127.0.0.1;port=3306', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
    exit;
}

$api = new StoreAPI($db);
$api->handleRequest();