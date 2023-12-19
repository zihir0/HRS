<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteRoomId'])) {
    $roomId = $_POST['deleteRoomId'];

    require_once 'database/connection.php';

    // Check if there are any reservations associated with the room
    $checkReservationsQuery = "SELECT * FROM reservation WHERE RoomNumber = ?";
    $checkReservationsStmt = mysqli_prepare($connection, $checkReservationsQuery);
    mysqli_stmt_bind_param($checkReservationsStmt, "s", $roomId);
    mysqli_stmt_execute($checkReservationsStmt);
    $checkReservationsResult = mysqli_stmt_get_result($checkReservationsStmt);

    if ($checkReservationsResult && mysqli_num_rows($checkReservationsResult) > 0) {
        // Delete the associated reservations first
        $deleteReservationsQuery = "DELETE FROM reservation WHERE RoomNumber = ?";
        $deleteReservationsStmt = mysqli_prepare($connection, $deleteReservationsQuery);
        mysqli_stmt_bind_param($deleteReservationsStmt, "s", $roomId);
        mysqli_stmt_execute($deleteReservationsStmt);

        if (!$deleteReservationsStmt) {
            // Error occurred while deleting the reservations
            echo "Error deleting associated reservations.";
            mysqli_stmt_close($checkReservationsStmt);
            mysqli_stmt_close($deleteReservationsStmt);
            mysqli_close($connection);
            exit;
        }

        mysqli_stmt_close($deleteReservationsStmt);
    }

    // Delete the room from the database
    $deleteQuery = "DELETE FROM room WHERE RoomNumber = ?";
    $deleteStmt = mysqli_prepare($connection, $deleteQuery);
    mysqli_stmt_bind_param($deleteStmt, "s", $roomId);
    $deleteResult = mysqli_stmt_execute($deleteStmt);

    if ($deleteResult) {
        // Room deleted successfully
        echo "Room deleted successfully.";
        mysqli_stmt_close($deleteStmt);
        
        // Redirect back to modify_rooms.php
        header("Location: modify_rooms.php");
        exit;
    } else {
        // Error occurred during deletion
        echo "Error deleting the room.";
    }

    mysqli_stmt_close($checkReservationsStmt);
    mysqli_close($connection);
} else {
    echo "Invalid request.";
}
?>
