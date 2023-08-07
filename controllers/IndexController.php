<?php
namespace FwTest\Controller;
use FwTest\Classes as Classes;
use FwTest\Classes\Product as Product;
use FwTest\Core\Database;


class IndexController
{
    /**
     * @Route('/index.php')
     */
    public function index()
    {
        echo 'Index.';
    }

}