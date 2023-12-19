<?php
include "database/connection.php";

if (isset($_GET['name']) && isset($_GET['id']) && isset($_GET['reservationId'])) {
    $reservationId = $_GET['reservationId'];
    $name = $_GET['name'];
    $id = $_GET['id'];

    // Retrieve room number associated with the reservation
    $roomNumberQuery = "SELECT RoomNumber FROM reservation WHERE ReservationID = $reservationId";
    $roomNumberResult = mysqli_query($connection, $roomNumberQuery);

    if ($roomNumberResult && mysqli_num_rows($roomNumberResult) > 0) {
        $roomData = mysqli_fetch_assoc($roomNumberResult);
        $roomNumber = $roomData['RoomNumber'];

        // Delete the entry from the reservation table
        $deleteQuery = "DELETE FROM reservation WHERE ReservationID = $reservationId";
        $deleteResult = mysqli_query($connection, $deleteQuery);

        if ($deleteResult) {
            // Update the room's availability upon successful deletion
            $updateAvailabilityQuery = "UPDATE room SET Availability = 1 WHERE RoomNumber = '$roomNumber'";
            $updateAvailabilityResult = mysqli_query($connection, $updateAvailabilityQuery);

            if ($updateAvailabilityResult) {
                echo "Entry successfully deleted. Room availability updated.";
                header("Location: reservation.php?name=" . urlencode($name) . "&id=" . $id);
                exit;
            } else {
                echo "Error updating room availability: " . mysqli_error($connection);
            }
        } else {
            echo "Error deleting entry: " . mysqli_error($connection);
        }
    } else {
        echo "Reservation not found.";
    }
} else {
    header("location: login.php");
    exit;
}
?>
