<?php

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../config/Database.php';

class ProductDAO {

    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    private function rowToProduct(array $row): Product {
        return new Product(
            id:            (int)$row['id'],
            nome:          $row['nome'],
            tamanho:       $row['tamanho'],
            peso:          (float)$row['peso'],
            tipo:          $row['tipo'],
            cor:           $row['cor'] ?? null,
            image:         $row['image'] ?? '',
            preco_venda:  (float)$row['preco_venda'],
            preco_custo:  (float)$row['preco_custo'],
            stock:          (int)$row['stock']
        );
    }

    public function findById($productId): Product|false {
        $sql = "
            SELECT * 
            FROM produtos 
            WHERE id = :id
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $productId);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->rowToProduct($row) : false;
    }

    public function getProductsDao(): array {
        $sql = "
            SELECT 
                produtos.id,
                produtos.nome,
                produtos.tamanho,
                produtos.peso,
                produtos.tipo,
                produtos.cor,
                produtos.preco_venda,
                produtos.preco_custo,
                produtos.stock
            FROM produtos
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $products = [];

        foreach($rows as $row) {
            $products[] = $this->rowToProduct($row);
        }

        return $products;
    }


    public function updateProduct($productId, $nome, $tamanho, $peso, $tipo, $cor, $preco_venda, $preco_custo, $stock): int {
        $sql = "
            UPDATE produtos 
            SET nome = ?, tamanho = ?, peso = ?, tipo = ?, cor = ?, preco_venda = ?, preco_custo = ?, stock = ?, updated_at = NOW()
            WHERE id = ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$nome, $tamanho, $peso, $tipo, $cor, $preco_venda, $preco_custo, $stock, $productId]);
        return $stmt->rowCount();
    }
 
    public function arrayProductsDAO() {
    $sql = "
        SELECT
            produtos.id,
            produtos.nome,
            produtos.tamanho,
            produtos.peso,
            produtos.tipo,
            produtos.cor,
            produtos.image,
            produtos.preco_venda,
            produtos.preco_custo,
            produtos.stock
        FROM produtos;
    ";
 
    $stmt = $this->conn->prepare($sql);
 
    $stmt->execute();
 
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
    return $rows;
  }

  public function countProducts(){
    $sql = "
        SELECT COUNT(*) as num_products 
        FROM produtos;
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return (int)$stmt->fetchColumn();
  }

  public function productsRevenue($productId): int {
    $sql = "
        SELECT SUM(p.preco_venda * cp.quantidade) AS receita
        FROM carrinho_produtos AS cp
        INNER JOIN produtos AS p ON cp.id_produto = p.id
        INNER JOIN pedidos AS pe ON cp.id_carrinho = pe.id_carrinho
        WHERE p.id = ?
        GROUP BY p.id
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$productId]);
    return (int)$stmt->fetchColumn();
  }

  public function productsSales($productId): int {
    $sql = "
        SELECT SUM(cp.quantidade) AS total_vendido
        FROM carrinho_produtos AS cp
        INNER JOIN produtos AS p ON cp.id_produto = p.id
        INNER JOIN pedidos AS pe ON cp.id_carrinho = pe.id_carrinho
        WHERE p.id = ?
        GROUP BY p.id
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$productId]);
    return (int)$stmt->fetchColumn();
  }

  public function getAllProductsByName(): array {
    $sql = "
    SELECT nome, preco_venda, image
    FROM produtos
    GROUP BY nome, preco_venda, image;
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
  }
}