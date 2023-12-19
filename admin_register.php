<?php
include 'database/connection.php'; // Include your database connection file here

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        isset($_POST['username']) &&
        isset($_POST['password']) &&
        isset($_POST['email']) &&
        isset($_POST['firstName']) &&
        isset($_POST['lastName']) &&
        isset($_POST['phoneNumber'])
    ) {
        // Sanitize and retrieve form data
        $username = mysqli_real_escape_string($connection, $_POST['username']);
        $password = mysqli_real_escape_string($connection, $_POST['password']);
        $email = mysqli_real_escape_string($connection, $_POST['email']);
        $firstName = mysqli_real_escape_string($connection, $_POST['firstName']);
        $lastName = mysqli_real_escape_string($connection, $_POST['lastName']);
        $phoneNumber = mysqli_real_escape_string($connection, $_POST['phoneNumber']);

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the admin data into the database
        $insertQuery = "INSERT INTO admin (Username, Password, Email, FirstName, LastName, PhoneNumber) 
                        VALUES ('$username', '$hashedPassword', '$email', '$firstName', '$lastName', '$phoneNumber')";
        $result = mysqli_query($connection, $insertQuery);

        if ($result) {
            echo "Admin registered successfully!";
            // Redirect to a success page or login page
            // header("Location: login.php");
            // exit;
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    } else {
        echo "All fields are required!";
    }
}
?>
<!-- Your HTML form for admin registration -->
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Registration</title>
</head>
<body>
    <form method="post" action="">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="text" name="firstName" placeholder="First Name" required><br>
        <input type="text" name="lastName" placeholder="Last Name" required><br>
        <input type="text" name="phoneNumber" placeholder="Phone Number" required><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>
