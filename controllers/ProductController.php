<?php
namespace FwTest\Controller;
use FwTest\Classes as Classes;

class StoreController
{
    /**
     * @Route('/product_list.php')
     */
    public function index()
    {
    	$db = $this->getDatabaseConnection();

        $list_product = Classes\Product::getAll($db, 0, $this->array_constant['store']['nb_products']);
        //$list_product = Classes\Product::getAll($db);

        echo $this->render('store/list.tpl', ['list_product' => $list_product]);

    }

    /**
     * @Route('/product_delete.php')
     */
    public function delete()
    {
        $db = $this->getDatabaseConnection();

        $data = json_decode(file_get_contents('php://input'), true);
        $productId = $data['id'];

        $product = new Classes\Product($db, $productId);
        $entity_product = $product->getProductById($db, $productId);
        $result = $product->delete($productId);

        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['success' => $result]);
        }
        echo json_encode(['error' => $result]);
    }

    /**
     * @Route('/product_detail.php')
     */
    public function detail()
    {
        $db = $this->getDatabaseConnection();

    	$id = (isset($_GET['id']) && !empty($_GET['id']))? $_GET['id']:0;
    	if (!empty($id)) {

    		$product = new Classes\Product($db, $id);
            $entity_product = $product->getProductById($db, $id);

    		if (!empty($product)) {
    			echo $this->render('store/detail_list.tpl', ['store' => $entity_product]);
    		} else {
    			$this->_redirect('product_list.php');
    		}
    		
    	} else {
    		$this->_redirect('product_list.php');
    	}

    }
}