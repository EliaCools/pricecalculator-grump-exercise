<?php


class CustomerLoader
{
    public function singleCustomer(PDO $pdo, int $id): Customer
    {
        $query = $pdo->prepare('SELECT id, concat_ws(" ", c.firstname, c.lastname) AS name , group_id , fixed_discount, variable_discount FROM customer c WHERE c.id =:id');

        if (!$query) {
            throw new PDOException("customer could not be fetched");
        }

        $query->bindValue('id', $id);
        $query->execute();
        $raw = $query->fetch();
        return new Customer((int)$raw["id"], $raw["name"], $raw["group_id"], $raw["fixed_discount"], $raw["variable_discount"]);
    }


    public function allCustomers(PDO $pdo): array|false
    {
        $query = $pdo->prepare('SELECT id, concat_ws(" ", c.firstname, c.lastname) AS name , group_id , fixed_discount, variable_discount FROM customer c');
        if (!$query) {
            throw new PDOException("customer  could not be fetched");
        }

        $query->execute();
        return $query->fetchAll();
    }

    public function loginCheck(PDO $pdo, string $email)
    {
        $query = $pdo->prepare(('SELECT email, password FROM customer c WHERE email = :email'));

        if (!$query) {
            throw new PDOException("user email could not be fetched");
        }


        $query->bindValue('email', $email);
        $query->execute();
        return $query->fetch();
    }
}
