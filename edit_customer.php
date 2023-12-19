<?php
require_once 'database/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editCustomerId'], $_POST['editCustomerFirstName'], $_POST['editCustomerLastName'], $_POST['editCustomerAge'], $_POST['editCustomerEmail'], $_POST['editCustomerPhone'])) {
    $editCustomerId = $_POST['editCustomerId'];
    $editCustomerFirstName = $_POST['editCustomerFirstName'];
    $editCustomerLastName = $_POST['editCustomerLastName'];
    $editCustomerAge = $_POST['editCustomerAge'];
    $editCustomerEmail = $_POST['editCustomerEmail'];
    $editCustomerPhone = $_POST['editCustomerPhone'];

    // Update query
    $updateQuery = "UPDATE customer 
                    SET FirstName = '$editCustomerFirstName', 
                        LastName = '$editCustomerLastName', 
                        Age = '$editCustomerAge',
                        Email = '$editCustomerEmail', 
                        PhoneNumber = '$editCustomerPhone'
                    WHERE CustomerID = $editCustomerId";

    if (mysqli_query($connection, $updateQuery)) {
        // Redirect back to modify_customers.php
        header("Location: modify_customers.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($connection);
    }
} else {
    echo "Invalid request";
}
mysqli_close($connection);

?>
