

<!DOCTYPE html>
<html>
<head>
    <title>Hotel Reservation - Reservation Details</title>
    <link rel="stylesheet" type="text/css" href="styles_rs.css?v=p<?php echo time(); ?>">
    <style>
        .unbook-button {
            background-color: #ff0000;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
        }

        .unbook-button:hover {
            background-color: #e60000;
        }

        .edit-dates-button {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
        }

        .edit-dates-button:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        function confirmUnbook(name, customerId, reservationId) {
            if (confirm("Are you sure you want to unbook this room?")) {
                // Redirect to the unbooking page with the selected room
                const url = "unbook.php?name=" + encodeURIComponent(name) + "&id=" + customerId + "&reservationId=" + reservationId;
                window.location.href = url;
            }
        }

        function openEditDatesForm(reservationId, checkIn, checkOut, name, customerId) {
            document.getElementById("edit-date-modal").style.display = "block";
            document.getElementById("reservation-id").value = reservationId;
            document.getElementById("check-in-date").value = checkIn;
            document.getElementById("check-out-date").value = checkOut;
            document.getElementById("name").value = name;
            document.getElementById("customer-id").value = customerId;
        }

        function closeEditDatesForm() {
            document.getElementById("edit-date-modal").style.display = "none";
        }
    </script>
</head>
<body>
<div class="banner">
    <div class="navbar">
        <div class="user-info">
            <img src="images/fl8W3Vd9_400x400.jpg" class="logo">
            <?php
            if (isset($_GET['name']) && isset($_GET['id'])) {
                $name = $_GET['name'];
                $customerId = $_GET['id'];
                echo '<p class="user-name">' . $name . '</p>';
            } else {
                header("location: login.php");
                exit;
            }
            ?>
        </div>
        <ul>
            <li><a href="dashboard.php?name=<?php echo urlencode($name); ?>&id=<?php echo $customerId; ?>">Home</a></li>
            <li><a href="room.php?name=<?php echo urlencode($name); ?>&id=<?php echo $customerId; ?>">Rooms</a></li>
            <li><a href="reservation.php?name=<?php echo urlencode($name); ?>&id=<?php echo $customerId; ?>">Reservation</a></li>
            <li><a href="billing.php?name=<?php echo urlencode($name); ?>&id=<?php echo urlencode($customerId); ?>">Billing</a></li>
            <li><a href="Logout.php">Logout</a></li>
        </ul>
    </div>
    <h1>Hotel Reservation - Reservation Details</h1>

    <div class="reservation-container">
        <?php
        require_once 'database/connection.php';

        // Retrieve the user's reservations from the database
        $reservationQuery = "SELECT r.*, room.RoomNumber, room.Room_img AS image_url FROM reservation AS r
        INNER JOIN room ON r.RoomNumber = room.RoomNumber
        WHERE r.CustomerID = '$customerId'";
        $reservationResult = $connection->query($reservationQuery);

        if ($reservationResult && $reservationResult->num_rows > 0) {
            echo "<h2>Your Reservations:</h2>";
            while ($reservationRow = $reservationResult->fetch_assoc()) {
                // Define or fetch reservationId for each iteration
                $reservationId = $reservationRow['ReservationID'] ?? null; // Adjust the key according to your database structure
                $roomNumber = $reservationRow['RoomNumber'];
                $imageURL = $reservationRow['image_url'];

                echo "<p>Reserved Room: Room Number " . $roomNumber . "</p>";
                echo "<p>Check-in: " . $reservationRow['CheckInDate'] . "</p>";
                echo "<p>Check-out: " . $reservationRow['CheckOutDate'] . "</p>";

                echo "<div style='text-align: center;'>";
                echo "<img src='" . $imageURL . "' alt='Room Image' style='width: 100%;height: 200px;object-fit: cover;border-radius: 8px;'>";
                echo "</div>";
                echo "<div class='button-container'>";
                echo "<button class='unbook-button' onclick='confirmUnbook(\"$name\", \"$customerId\", \"$reservationId\")'>Unbook</button>";
                echo "<a href='#' onclick='openEditDatesForm(\"$reservationId\", \"" . $reservationRow['CheckInDate'] . "\", \"" . $reservationRow['CheckOutDate'] . "\", \"$name\" , \"$customerId\")'><button class='edit-dates-button'>Edit Check-in and Check-out Dates</button></a>";
                echo "</div>";
            }
        } else {
            echo "<p class='info-message'>You have no reservations.</p>";
        }
        ?>
    </div> <!-- Closing div for reservation-container -->

    <!--MODAL-->
    <div id="edit-date-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditDatesForm()">&times;</span>
            <h3>Edit Dates</h3>
            <form action="edit_dates.php" method="POST" id="edit-date-form">
                <input type="hidden" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
                <input type="hidden" id="customer-id" name="customer-id" value="<?php echo htmlspecialchars($customerId); ?>">
                <input type="hidden" id="reservation-id" name="reservation-id">
                <label for="check-in-date">Check-in Date:</label>
                <input type="date" id="check-in-date" name="check-in-date" required>
                <label for="check-out-date">Check-out Date:</label>
                <input type="date" id="check-out-date" name="check-out-date" required>
                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>
</div> <!-- Closing div for banner -->
</body>
</html>
