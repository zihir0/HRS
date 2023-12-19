<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    // Redirect to the login page if the user is not logged in
    header("location: login.php");
    exit;
}

include 'database/connection.php'; // Include your database connection file here

// Retrieve user data from the database using the CustomerID
$customerId = $_SESSION['customer_id'];
$queryCustomer = "SELECT FirstName FROM customer WHERE CustomerID = '$customerId'";
$resultCustomer = mysqli_query($connection, $queryCustomer);

if ($resultCustomer && mysqli_num_rows($resultCustomer) > 0) {
    $customerData = mysqli_fetch_assoc($resultCustomer);
    $customerName = $customerData['FirstName'];
} else {
    // If the first name is not found, provide a default value or handle it accordingly
    $customerName = "Guest";
}

// Fetch room details from the database
$queryRooms = "SELECT RoomNumber, RoomType, MaxOccupancy, Room_img, RatePerNight, Availability FROM room";
$resultRooms = mysqli_query($connection, $queryRooms);

// Close the database connection (don't forget to do this at the end of your script)
mysqli_close($connection);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Hotel Reservation - Room Selection</title>
  <link rel="stylesheet" type="text/css" href="styles_r.css?v=1">
</head>
<body>
    <div class="banner">
        <div class="navbar">
            <div class="user-info">
                <img src="images/fl8W3Vd9_400x400.jpg" class="logo">
                <?php
                // Display user name retrieved from the session
                echo '<p class="user-name"> ' . $customerName . '</p>';
                ?>
            </div>
            <ul>
                <!-- Navigation links -->
                <li><a href="dashboard.php?name=<?php echo urlencode($customerName); ?>&id=<?php echo urlencode($customerId); ?>">Home</a></li>
                <li><a href="room.php?name=<?php echo urlencode($customerName); ?>&id=<?php echo urlencode($customerId); ?>">Rooms</a></li>
                <li><a href="reservation.php?name=<?php echo urlencode($customerName); ?>&id=<?php echo urlencode($customerId); ?>">Reservation</a></li>
                <li><a href="billing.php?name=<?php echo urlencode($customerName); ?>&id=<?php echo urlencode($customerId); ?>">Billing</a></li>
                <li><a href="Logout.php">Logout</a></li>
            </ul>
        </div>

        <!-- Room Selection Section -->
        <div class="room-selection">
            <?php
            if ($resultRooms && mysqli_num_rows($resultRooms) > 0) {
                echo "<form method='POST' action='add-reservation.php'>"; // Adjust action based on your form handling

                echo "<h2>Select Rooms:</h2>";
                echo "<div class='date-fields'>";
                echo "<input type='text' id='customerId' name='customerId' value='" . $customerId . "' hidden>";
                echo "<label for='checkin'>Check-in Date:</label>";
                echo "<input type='date' id='checkin' name='checkin' required>";
                echo "<label for='checkout'>Check-out Date:</label>";
                echo "<input type='date' id='checkout' name='checkout' required>";
                echo "</div>";

                echo "<h2>Available Rooms:</h2>";

                while ($row = mysqli_fetch_assoc($resultRooms)) {
                    $roomNumber = $row['RoomNumber'];
                    $roomType = $row['RoomType'];
                    $maxOccupancy = $row['MaxOccupancy'];
                    $roomImage = $row['Room_img'];
                    $ratePerNight = $row['RatePerNight'];
                    $availability = $row['Availability'];

                    echo "<div class='room'>";
                    echo "<input type='checkbox' name='selected-room[]' value='$roomNumber'>";
                    echo "<img src='$roomImage' alt='Room Image' class='room-image'>";
                    echo "<span class='room-name'>$roomType</span>";
                    echo "<span class='room-info'>Max Occupancy: $maxOccupancy | Rate/Night: $ratePerNight | Availability: $availability</span>";
                    echo "</div>";
                }

                echo "<div class='button-container'>";
                echo "<input type='submit' name='submit' class='book-now-button' value='Book Now' disabled>";
                echo "</div>";
                echo "</form>";
            } else {
                echo "<p class='error-message'>No rooms available at the moment.</p>";
            }
            ?>
        </div>
    </div>

    <!-- JavaScript for form validation and interaction -->
    <script>
        function enableBookNowButton() {
            var selectedRooms = document.querySelectorAll('input[name="selected-room[]"]:checked');
            var checkinDate = document.getElementById('checkin').value;
            var checkoutDate = document.getElementById('checkout').value;
            var bookNowButton = document.querySelector('.book-now-button');
            bookNowButton.disabled = selectedRooms.length === 0 || checkinDate === '' || checkoutDate === '';
        }

        // Add an event listener to enable/disable the book now button
        var checkboxes = document.querySelectorAll('input[name="selected-room[]"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', enableBookNowButton);
        });

        // Add event listeners for date fields
        var dateFields = document.querySelectorAll('.date-fields input');
        dateFields.forEach(function(dateField) {
            dateField.addEventListener('input', enableBookNowButton);
        });
    </script>
</body>
</html>
