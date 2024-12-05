<?php


class ProductsPdo {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getProductsByUserId($userId): array {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE user_id = :userId");
        $stmt->execute(['userId' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createProduct($name, $price, $userId): bool {
        $stmt = $this->pdo->prepare("INSERT INTO products (name, price, user_id) VALUES (:name, :price, :userId)");
        return $stmt->execute(['name' => $name, 'price' => $price, 'userId' => $userId]);
    }

    public function updateProductByUserId($idOfProduct, $name, $price, $userId): bool {
        $stmt = $this->pdo->prepare(
            "UPDATE products SET name = :name, price = :price WHERE id = :idOfProduct AND user_id = :userId"
        );
        return $stmt->execute([
            'idOfProduct' => $idOfProduct,
            'name' => $name,
            'price' => $price,
            'userId' => $userId
        ]);
    }

    public function deleteProductsByUserId($userId): bool {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE user_id = :userId");
        return $stmt->execute(['userId' => $userId]);
    }
}










?>