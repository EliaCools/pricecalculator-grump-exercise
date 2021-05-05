<?php


class loginController
{
    private Connection $db;

    public function __construct()
    {
        $this->db = new Connection();
    }

    public function render()
    {
        if (isset($_POST['email'], $_POST['password'])) {
            $customerLoader = new CustomerLoader();

            $result = $customerLoader->loginCheck($this->db, (string)$_POST['email']);

            if ($result !== false && password_verify((string)$_POST['password'], $result['password'])) {
                $_SESSION['logged_in'] = true;
                header('Location: ?logged_in=true');
                exit;
            } else {
                $msg = 'Wrong login details';
            }
        }

        require 'View/login.php';
    }
}
