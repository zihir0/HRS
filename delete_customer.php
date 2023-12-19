<?php
// delete_customer.php

// Check if the request method is POST and the 'deleteCustomerId' is set in the POST data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteCustomerId'])) {
    require_once 'database/connection.php'; // Ensure the connection file is included

    // Sanitize the input to prevent SQL injection
    $customerId = mysqli_real_escape_string($connection, $_POST['deleteCustomerId']);

    // Delete the customer from the database
    $deleteQuery = "DELETE FROM customer WHERE CustomerID = '$customerId'";

    if (mysqli_query($connection, $deleteQuery)) {
        // Redirect to the customer list page after successful deletion
        header("location: modify_customers.php?name=" . urlencode($_GET['name']) . "&id=" . $_GET['id']);
        exit;
    } else {
        // Handle the deletion error - Redirect with an error message or log the error
        // For instance, redirect back to the customer list page with an error parameter
        header("location: modify_customers.php?name=" . urlencode($_GET['name']) . "&id=" . $_GET['id'] . "&error=1");
        exit;
    }

    // Close the database connection
    mysqli_close($connection);
} else {
    // Handle cases where deleteCustomerId is not set in the POST data
    echo "Invalid request";
}
?>
