<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

function authenticateUser($username, $password) {
    // Validate user credentials against a database (you may hash the passwords)
    require_once 'database/connection.php'; // Adjust the path as needed

    $query = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = mysqli_prepare($connection, $query);
    $hashedPassword = hash("sha256", $password); // Hash the password
    mysqli_stmt_bind_param($stmt, "ss", $username, $hashedPassword);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        // Handle admin vs. regular user here based on roles in the database
        // Redirect users based on their roles
        if ($user['role'] === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: dashboard.php");
        }
        exit;
    } else {
        return false; // Authentication failed
    }
}
?>
