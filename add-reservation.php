<?php
include "database/connection.php";

if (isset($_POST['submit']) && isset($_POST['customerId']) && isset($_POST['checkin']) && isset($_POST['checkout']) && isset($_POST['selected-room'])) {
    $id = $_POST['customerId'];
    $selectedRoom = $_POST['selected-room'][0]; // Select the first room if multiple rooms are allowed
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];

    // Start a database transaction
    mysqli_autocommit($connection, false);

    $error = false;

    // Check if the room is available for the given date range
    $checkAvailabilityQuery = "SELECT * FROM reservation WHERE RoomNumber = '$selectedRoom' AND (('$checkin' BETWEEN CheckInDate AND CheckOutDate) OR ('$checkout' BETWEEN CheckInDate AND CheckOutDate))";
    $checkAvailabilityResult = mysqli_query($connection, $checkAvailabilityQuery);

    if (mysqli_num_rows($checkAvailabilityResult) > 0) {
        $error = true;
        echo "Sorry, the room is already booked for the selected date range.";
    } else {
        // Room is available, so insert the reservation
        $insertQuery = "INSERT INTO reservation (CustomerID, RoomNumber, CheckInDate, CheckOutDate) VALUES ('$id', '$selectedRoom', '$checkin', '$checkout')";
        $insertResult = mysqli_query($connection, $insertQuery);

        if (!$insertResult) {
            $error = true;
            echo "Error adding reservation: " . mysqli_error($connection);
        } else {
            // Retrieve the generated ReservationID after the successful insert
            $reservationID = mysqli_insert_id($connection);
            
            -
            // Update room availability upon successful reservation
            $updateRoomAvailabilityQuery = "UPDATE room SET Availability = 0 WHERE RoomNumber = '$selectedRoom'";
            $updateRoomAvailabilityResult = mysqli_query($connection, $updateRoomAvailabilityQuery);

            if (!$updateRoomAvailabilityResult) {
                $error = true;
                echo "Error updating room availability: " . mysqli_error($connection);
            }
        }
    }

    // Commit the transaction if no errors occurred, otherwise, roll back
    if ($error) {
        mysqli_rollback($connection);
    } else {
        mysqli_commit($connection);
        echo "Reservation successfully added, room availability updated.";
        
        // Redirect to a success page or do further processing
        header("Location: billing.php?name=" . urlencode($name) . "&id=" . $id);
        exit;
    }

    // Restore autocommit mode
    mysqli_autocommit($connection, true);
} else {
    header("Location: reservation.php?name=" . urlencode($name) . "&id=" . $id);
    exit;
}
?>
