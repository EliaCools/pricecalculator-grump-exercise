<?php


class ProductLoader
{
    public function getProduct(PDO $pdo, int $id): Product
    {
        $query = $pdo->prepare('SELECT * FROM product WHERE product.id = :id');

        if (!$query) {
            throw new PDOException("customer  could not be fetched");
        }


        $query->bindValue('id', $id);
        $query->execute();
        $raw = $query->fetch();
        return new Product($raw['id'], $raw['name'], $raw["price"]);
    }

    public function getProducts(PDO $pdo): array|false
    {
        $query = $pdo->query('SELECT * FROM product');

        if (!$query) {
            throw new PDOException("customer  could not be fetched");
        }

        return $query->fetchAll();
    }
}
