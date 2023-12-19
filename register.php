<?php
include 'database/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        isset($_POST['full_name']) && isset($_POST['phone_number']) &&
        isset($_POST['age']) && isset($_POST['email_2']) && isset($_POST['password_2'])
    ) {
        $fullName = mysqli_real_escape_string($connection, $_POST['full_name']);
        $phoneNumber = mysqli_real_escape_string($connection, $_POST['phone_number']);
        $age = mysqli_real_escape_string($connection, $_POST['age']);
        $email = mysqli_real_escape_string($connection, $_POST['email_2']);
        $password = mysqli_real_escape_string($connection, $_POST['password_2']);

        // Separate full name into first and last names
        $names = explode(' ', $fullName);
        $firstName = $names[0]; // First name
        $lastName = isset($names[1]) ? $names[1] : ''; // Last name (if provided)

        // Hash the password consistently
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert data into the customer table
        $insertQuery = "INSERT INTO customer (FirstName, LastName, Email, PhoneNumber, Password, Age)
                        VALUES ('$firstName', '$lastName', '$email', '$phoneNumber', '$hashedPassword', '$age')";

        if (mysqli_query($connection, $insertQuery)) {
            // Registration successful, redirect to login page
            header("Location: Login.php"); // Corrected the header to redirect to login.php
            exit;
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    }
}
?>
