<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once "database/connection.php";

$reservationID = $checkinDate = $checkoutDate = $totalAmount = $paymentID = $paymentDate = $paymentAmount = $paymentMethod = null;

if (isset($_SESSION['customer_id'])) {
    $loggedInCustomerID = filter_var($_SESSION['customer_id'], FILTER_VALIDATE_INT);

    if ($loggedInCustomerID !== false && $loggedInCustomerID > 0) {
        $loggedInCustomerID = $connection->real_escape_string($loggedInCustomerID);

        $reservationQuery = "SELECT r.ReservationID, r.CheckInDate, r.CheckOutDate, r.TotalAmount, r.RoomNumber, p.PaymentID, p.PaymentDate, p.PaymentAmount, p.PaymentMethod 
                            FROM reservation r
                            LEFT JOIN payment p ON r.ReservationID = p.ReservationID
                            WHERE r.CustomerID = '$loggedInCustomerID'";
        $reservationResult = $connection->query($reservationQuery);

        if ($reservationResult && $reservationResult->num_rows > 0) {
            $reservation = $reservationResult->fetch_assoc();
            $reservationID = $reservation['ReservationID'];
            $checkinDate = isset($reservation['CheckInDate']) ? new DateTime($reservation['CheckInDate']) : null;
            $checkoutDate = isset($reservation['CheckOutDate']) ? new DateTime($reservation['CheckOutDate']) : null;
            $totalAmount = $reservation['TotalAmount'];
            $roomNumber = $reservation['RoomNumber'];
            $paymentID = $reservation['PaymentID'];
            $paymentDate = isset($reservation['PaymentDate']) ? new DateTime($reservation['PaymentDate']) : null;
            $paymentAmount = $reservation['PaymentAmount'];
            $paymentMethod = $reservation['PaymentMethod'];

            // Fetch room rate from the room table based on RoomNumber
            $roomRateQuery = "SELECT RatePerNight FROM room WHERE RoomNumber = '$roomNumber'";
            $roomResult = $connection->query($roomRateQuery);

            if ($roomResult && $roomResult->num_rows > 0) {
                $room = $roomResult->fetch_assoc();
                $roomRate = $room['RatePerNight'];

                // Calculate duration of stay in days
                $duration = 1; // Default duration in case of errors or missing data
                if ($checkinDate && $checkoutDate) {
                    $duration = $checkoutDate->diff($checkinDate)->days;
                }

                // Calculate total amount
                $totalAmount = $roomRate * $duration;
            } else {
                echo "Error fetching room rate: " . $connection->error;
            }
        } else {
            echo "No reservation found for the logged-in customer.";
        }
    } else {
        echo "Invalid customer ID.";
    }
} else {
    echo "User is not logged in.";
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Reservation System</title>
    <link rel="stylesheet" href="styles_d2.css?v=1">
    <style>
        /* Add any additional inline styles or update your CSS file */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .banner {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 20px;
        }

        .navbar {
            background-color: #333;
            color: white;
            padding: 10px 0;
            width: 100%;
            display: flex;
            justify-content: space-around;
            align-items: center;
            margin-bottom: 20px; /* Added margin to separate banner and content */
            margin: inherit;
        }

        .navbar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .navbar ul li {
            display: inline;
        }

        .navbar ul li a {
            text-decoration: none;
            color: white;
            padding: 10px 15px;
        }

        .invoice {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin: auto;
            width: 70%;
            max-width: fit-content;
        }

        .invoice h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
        }

        .invoice p {
            font-size: 18px;
            margin-bottom: 10px;
            line-height: 1.6; /* Increased line height for better readability */
        }

        .payment-details {
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }

        .payment-details h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .payment-details p {
            font-size: 16px;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
<div class="banner">
    <div class="navbar">
        <div class="user-info">
            <img src="images/fl8W3Vd9_400x400.jpg" class="logo">
            <?php
            if (isset($_GET['name'])) {
                $name = $_GET['name'];
                $customerId = $_GET['id']; // Fix variable name here
                echo '<p class="user-name"> ' . $name . '</p>';
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
    <div class="content">
    <div class="invoice">
        <?php if (isset($_SESSION['customer_id']) && $reservationID !== null) { ?>
            <div class="invoice-header">
                <h1>Invoice</h1>
            </div>
            <div class="invoice-details">
                <div class="reservation-info">
                    <p><strong>Reservation ID:</strong> <?php echo $reservationID; ?></p>
                    <p><strong>Check-in Date:</strong> <?php echo ($checkinDate ? $checkinDate->format('Y-m-d') : 'Not available'); ?></p>
                    <p><strong>Check-out Date:</strong> <?php echo ($checkoutDate ? $checkoutDate->format('Y-m-d') : 'Not available'); ?></p>
                    <p><strong>Total Amount:</strong> <?php echo $totalAmount; ?></p>
                </div>
                <?php if ($paymentID !== null) { ?>
                    <div class="payment-info">
                        <h2>Payment Information</h2>
                        <p><strong>Payment ID:</strong> <?php echo $paymentID; ?></p>
                        <p><strong>Payment Date:</strong> <?php echo ($paymentDate ? $paymentDate->format('Y-m-d') : 'Not available'); ?></p>
                        <p><strong>Payment Amount:</strong> <?php echo $paymentAmount; ?></p>
                        <p><strong>Payment Method:</strong> <?php echo $paymentMethod; ?></p>
                    </div>
                <?php } else { ?>
                    <p class="no-payment-info">No payment information available.</p>
                <?php } ?>
            </div>
        <?php } else { ?>
            <p class="user-message">User is not logged in or no reservation found.</p>
        <?php } ?>
    </div>
</div>
</body>
</html>
