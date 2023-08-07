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
            $this->notFound("Magasin non trouvé");
        }
    }

    // GET @route: /store?find=[name]
    public function getStoresByName($searchTerm)
    {
        if ($searchTerm) {
            $sql = "SELECT * FROM magasin WHERE nom LIKE :searchTerm";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%');
            $stmt->execute();

            $stores = $stmt->fetchAll(PDO::FETCH_ASSOC);
            header('Content-Type: application/json');
            echo json_encode($stores);
        }
    }

    // POST @route: /store
    // form-data: [nom]:[string]
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

    // PATCH @route: /store/{id}
    // JSON : {"nom": "ExampleXiaopple Shop"}
    public function changeNameStore($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
            $rawData = file_get_contents("php://input");
            $requestData = json_decode($rawData, true);

            if (isset($requestData['nom']) && $id) {
                if ($this->checkIfStoreExist($id)) {
                    $newName = $requestData['nom'];
                    $sql = "UPDATE magasin SET nom = :nom WHERE id = :id";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindValue(':nom', $newName, PDO::PARAM_STR);
                    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                    $success = $stmt->execute();

                    if ($success) {
                        http_response_code(201);
                        echo json_encode(array('message' => "Le nom du magasin a été modifié avec succès."));
                    }
                    else {
                        http_response_code(500);
                        echo json_encode(array('message' => "Une erreur est survenue lors de la modification du nom du magasin."));
                    }
                }
                else {
                    $this->notFound("Magasin non trouvé");
                }
            }
            else {
                $this->paramError();
            }
        }
        else {
            $this->methodNotAllowed();
        }
    }

    // DELETE @route: /store/{id}
    public function deleteStore($id)
    {
        if ($id) {
            if ($this->checkIfStoreExist($id)) {
                $sql = "DELETE FROM magasin WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $success = $stmt->execute();

                if ($success) {
                    http_response_code(200);
                    echo json_encode(array('message' => 'Magasin supprimé avec succès.'));
                }
                else {
                    http_response_code(500);
                    echo json_encode(array('message' => "Une erreur est survenue lors de la suppression du magasin."));
                }
            }
            else {
                $this->notFound("Magasin non trouvé");
            }
        }
        else {
            $this->methodNotAllowed();
        }
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $url_parts = parse_url($_SERVER['REQUEST_URI']);
        $path = $url_parts['path'];
        $path_parts = explode('/', $path);
        array_shift($path_parts);

        if ($path_parts[0] === 'store') {
            $id = isset($path_parts[1]) ? $path_parts[1] : null;
            if ($id !== null && !ctype_digit($id)) {
               $this->paramError();
               return;
            }

            switch ($method) {
                case 'GET':
                    if ($id !== null) {
                        $this->getStoreById($id);
                    }
                    elseif (isset($_GET['find'])) {
                        $this->getStoresByName($_GET['find']);
                    }
                    else {
                        $this->getStores();
                    }
                    break;
                case 'POST':
                    $this->addStore();
                    break;
                case 'PATCH':
                    $this->changeNameStore($id);
                    break;
                case 'DELETE':
                    $this->deleteStore($id);
                    break;
                default:
                    $this->methodNotAllowed();
                    break;
            }
        } else {
            $this->notFound("Erreur 404");
        }
    }

    private function notFound($message)
    {
        http_response_code(404);
        echo json_encode(array('message' => $message));
    }

    private function methodNotAllowed()
    {
        http_response_code(405);
        echo json_encode(array('message' => 'Méthode non autorisée.'));
    }

    private function paramError() {
        http_response_code(400);
        echo json_encode(array('message' => 'Erreur de paramètre'));
    }

    private function checkIfStoreExist($id)
    {
        $sql = "SELECT * FROM magasin WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $store = $stmt->fetch(PDO::FETCH_ASSOC);

        return $store;
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