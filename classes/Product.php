<?php

use Exception;
use stdClass;

class Product
{
    /**
     * The table name
     *
     * @access  protected
     * @var     string
     */
	protected static $table_name = 'produit';

    /**
     * The primary key name
     *
     * @access  protected
     * @var     string
     */
    protected static $pk_name = 'produit_id';

    /**
     * The object datas
     *
     * @access  private
     * @var     array
     */
	private $_array_datas = array();

    /**
     * The object id
     *
     * @access  private
     * @var     int
     */
	private $id;

    /**
     * The lang id
     *
     * @access  private
     * @var     int
     */
	private $lang_id = 1;

    /**
     * The link to the database
     *
     * @access  public
     * @var     object
     */
	public $db;

    /**
     * Mes accesseurs
     */
    public $produit_nom;
    public $produit_titreobjet;
    public $produit_description;
    public $produit_prixremise;
    public $produit_prixvente;
    public $produit_vente;


    public function getProduitNom() {
        return $this->produit_nom;
    }
    public function setProduitNom($nom) {
        $this->produit_nom = $nom;
    }

    public function getProduitTitreObjet() {
        return $this->produit_titreobjet;
    }
    public function setProduitTitreObjet($nom) {
        $this->produit_titreobjet = $nom;
    }

    public function getProduitDescription() {
        return $this->produit_description;
    }
    public function setProduitDescription($description) {
        $this->produit_description = $description;
    }

    public function getProduitPrixRemise() {
        return $this->produit_prixremise;
    }
    public function setProduitPrixRemise($prix) {
        $this->produit_prixremise = $prix;
    }

    public function getProduitVente() {
        return $this->produit_vente;
    }
    public function setProduitVente($vente) {
        $this->produit_vente = $vente;
    }

    public function getProduitPrixVente() {
        return $this->produit_prixvente;
    }
    public function setProduitPrixVente($prix) {
        $this->produit_prixvente = $prix;
    }


    /**
     * Product constructor.
     *
     * @param      $db
     * @param      $datas
     *
     * @throws Exception
     */
	public function __construct($db, $datas)
    {

        if (($datas != intval($datas)) && (!is_array($datas))) {
            throw new Exception('The given datas are not valid.');
        }

        $this->db = $db;

        if (is_array($datas)) {
            $this->_array_datas = array_merge($this->_array_datas, $datas);
        } else {
            $this->_array_datas[self::$pk_name] = $datas;
        }
    }

    /**
     * Get the list of store.
     *
     * @param      $db
     * @param      $begin
     * @param      $end
     *
     * @return     array of Product
     */
	public static function getAll($db, $begin = 0, $end = 15)
	{

        $sql_get = "SELECT * FROM " . self::$table_name . " LIMIT " . $begin. ", " . $end;

        $result = $db->fetchAll($sql_get);

		$array_product = [];

		if (!empty($result)) {
			foreach ($result as $key => $product) {
				$array_product[$key] = new Product($db, $product);
                $array_product[$key]->setProduitNom($product['produit_nom']);
                $array_product[$key]->setProduitDescription($product['produit_description']);
            }
		}

		return $array_product;
	}

    public static function getProductById($db, $id)
    {
        $sql_get = "SELECT * FROM " . self::$table_name . " WHERE produit_id = $id";

        $result = $db->fetchAll($sql_get);
        if (!empty($result)) {
            $product = new Product($db, $result);
            $product->setProduitTitreObjet($result[0]['produit_titreobjet']);
            $product->setProduitPrixRemise($result[0]['produit_prixremise']);
            $product->setProduitPrixVente($result[0]['produit_prixvente']);
            $product->setProduitDescription($result[0]['produit_description']);
            //produit_vente n'existe pas ?
        }

        return $product;
    }

    /**
     * Delete a store.
     *
     * @return     bool if succeed
     */
	public function delete() 
	{
		$id = $this->getId();
		$sql_delete = "DELETE FROM " . self::$table_name . " WHERE " . self::$pk_name . " = :id";

        $params = [
            'id' => $id,
        ];

		return $this->db->query($sql_delete, $params);
	}

}