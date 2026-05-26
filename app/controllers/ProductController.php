<?php
require_once __DIR__ . '/../dao/ProductDAO.php';
 
class ProductController
{
    private function view($name, $data = []){
        extract($data, EXTR_SKIP);

        require __DIR__ . '/../../public/views/' . $name . '.php';
    }

    public function update($productId) {
        $product = (new ProductDAO())->findById($productId);

        if(!$product) {
            die("Produto não encontrado.");
        }

        if(!AuthMiddlewareWeb::canEditProduct($productId)) {
            die("Acesso negado.");
        }

        $nome = $_POST['nome'] ?? '';
        $tamanho = $_POST['tamanho'] ?? '';
        $peso = $_POST['peso'] ?? '';
        $tipo = $_POST['tipo'] ?? '';
        $cor = $_POST['cor'] ?? '';
        $preco_venda = $_POST['preco_venda'] ?? '';
        $preco_custo = $_POST['preco_custo'] ?? '';
        $stock = $_POST['stock'] ?? '';

        if(empty($nome) || empty($tamanho)) {
            throw new Exception("Nome e tamanho são obrigatórios.");
        }

        $result = (new ProductDAO())->updateProduct($productId, $nome, $tamanho, $peso, $tipo, $cor, $preco_venda, $preco_custo, $stock);

        if(! $result) {
            throw new Exception("Erro ao atualizar dados.");
        }

        if (AuthMiddlewareWeb::canEditProduct($productId)) {
            $nome = $_POST['nome'] ?? '';
            $tamanho = $_POST['tamanho'] ?? '';
            $peso = $_POST['peso'] ?? '';
            $tipo = $_POST['tipo'] ?? '';
            $cor = $_POST['cor'] ?? '';
            $preco_venda = $_POST['preco_venda'] ?? '';
            $preco_custo = $_POST['preco_custo'] ?? '';
            $stock = $_POST['stock'] ?? '';
        }
    }

    public function getProducts() {
        $products = (new ProductDAO())->getProductsDao();
    }

    public function getAllProductsByName() {
        try {
            $products = (new ProductDAO())->getAllProductsByName();

            $dataResponse = [
                'success' => true,
                'message' => "Operação realizada com sucesso",
                'data'    => [
                    'products' => $products
                ]
            ];

            Utils::jsonResponse($dataResponse, 200);

       }catch(Exception $e) {
        $dataResponse = [
            'success' => false,
            'message' => $e->getMessage(),
            'data'    => []
        ];

        Utils::jsonResponse($dataResponse, 401);
       }
    }
}