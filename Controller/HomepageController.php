<?php

declare(strict_types=1);

class HomepageController
{
    private Connection $db;

    public function __construct()
    {
        $this->db = new Connection();
    }

    public function render(): void
    {
        $calculator = new Calculator();
        $customerLoader = new CustomerLoader();
        $productLoader = new ProductLoader();


        $products = $productLoader->getProducts($this->db);
        $customers = $customerLoader->allCustomers($this->db);

        if (isset($_POST['submit'], $_POST['productId'], $_POST['customerId'])) {
            $customer = $customerLoader->singleCustomer($this->db, (int)$_POST['customerId']);
            $customername = $customer->getName();

            $product = $productLoader->getProduct($this->db, (int)$_POST['productId']);
            $productPrice = $product->getPrice() / Calculator::MAGIC_DIVIDER;
            $productName = $product->getName();

            $calculatedPrice = $calculator->checkCustomerDiscount($this->db, $product, $customer);
        } elseif (isset($_POST['submit']) && (!isset($_POST['productId'], $_POST['customerId']))) {
            $error = 'Please select a customer and product from the dropdown.';
        }

        if (isset($_POST['logout'])) {
            unset($_SESSION['logged_in']);
            header('Location: ?logged_in=false');
            exit;
        }

        require 'View/homepage.php';
    }
}
