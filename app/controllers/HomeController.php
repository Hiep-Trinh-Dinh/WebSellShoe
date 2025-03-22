<?php
class HomeController extends BaseController {
    private $productModel;

    public function __construct() {
        $this->productModel = $this->loadModel('Product');
    }

    public function index() {
        $featuredProducts = $this->productModel->getFeaturedProducts();
        
        $this->view('layouts/main', [
            'content' => 'home/index.php',
            'title' => 'Trang chủ - Shop Giày',
            'featuredProducts' => $featuredProducts
        ]);
    }
}
?> 