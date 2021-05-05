<?php

declare(strict_types=1);

session_start();

//include all your model files here
require 'Model/Customer.php';
require 'Model/CustomerLoader.php';
require 'Model/CustomerGroup.php';
require 'Model/CustomerGroupLoader.php';
require 'Model/Product.php';
require 'Model/Calculator.php';
require 'Model/Connection.php';
require 'Model/ProductLoader.php';
require 'config.php';
//include all your controllers here
require 'Controller/HomepageController.php';
require 'Controller/loginController.php';


if ((isset($_SESSION['logged_in'])) && $_SESSION['logged_in'] === true) {
    $controller = new HomepageController();
} else {
    $controller = new loginController();
}
$controller->render($_GET, $_POST);
