<?php
require_once 'database/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation-id']) && isset($_POST['name']) && isset($_POST['customer-id']) && isset($_POST['check-in-date']) && isset($_POST['check-out-date'])) {
    $reservationId = $_POST['reservation-id'];
    $checkInDate = $_POST['check-in-date'];
    $checkOutDate = $_POST['check-out-date'];
    $name = $_POST['name'];
    $customerId = $_POST['customer-id'];

    // Check if the updated dates conflict with existing reservations for the same room
    $conflictCheckQuery = "SELECT * FROM reservation WHERE ReservationID != $reservationId AND RoomNumber = (SELECT RoomNumber FROM reservation WHERE ReservationID = $reservationId)";
    $conflictCheckResult = mysqli_query($connection, $conflictCheckQuery);

    if ($conflictCheckResult && mysqli_num_rows($conflictCheckResult) > 0) {
        while ($row = mysqli_fetch_assoc($conflictCheckResult)) {
            // Check if the given dates overlap with other reservations for the same room
            $existingCheckIn = $row['CheckInDate'];
            $existingCheckOut = $row['CheckOutDate'];

            if (($checkInDate <= $existingCheckOut) && ($checkOutDate >= $existingCheckIn)) {
                // Conflicting dates found; do not allow the update
                echo "The updated dates conflict with existing reservations for the same room. Please choose different dates.";
                exit;
            }
        }
    }

    // No conflicting dates found; proceed with updating the reservation
    $updateQuery = "UPDATE reservation SET CheckInDate = '$checkInDate', CheckOutDate = '$checkOutDate' WHERE ReservationID = $reservationId";
    $updateResult = mysqli_query($connection, $updateQuery);

    if ($updateResult) {
        // The check-in and check-out dates were successfully updated
        header("location: reservation.php?name=" . urlencode($name) . "&id=" . $customerId);
        exit;
    } else {
        // An error occurred while updating the dates
        echo "Error: " . mysqli_error($connection);
    }
} else {
    header("location: login.php");
    exit;
}
?>
