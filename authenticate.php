<?php
require_once 'auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $authenticated = authenticateUser($username, $password);
    if (!$authenticated) {
        echo "Login failed. Invalid username or password.";
    }
}
?>
