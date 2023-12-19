<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'database/connection.php';

    // Validate and sanitize input
    $roomId = $_POST['editRoomId'] ?? '';
    $roomType = $_POST['editRoomType'] ?? '';
    $maxOccupancy = $_POST['editMaxOccupancy'] ?? '';
    $roomImg = $_POST['editRoomImg'] ?? '';
    $ratePerNight = $_POST['editRatePerNight'] ?? '';
    $availability = $_POST['editAvailability'] ?? '';

    // Validate input fields (adjust conditions based on requirements)
    if (empty($roomId) || empty($roomType) || empty($maxOccupancy) || empty($ratePerNight) || empty($availability)) {
        echo '<script>alert("Please fill in all required fields.");</script>';
    } else {
        // Prepare the update query
        $updateQuery = "UPDATE room SET RoomType = ?, MaxOccupancy = ?, Room_img = ?, RatePerNight = ?, Availability = ? WHERE RoomNumber = ?";
        $stmt = mysqli_prepare($connection, $updateQuery);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sisdsi", $roomType, $maxOccupancy, $roomImg, $ratePerNight, $availability, $roomId);

            // Execute the update query
            $updateResult = mysqli_stmt_execute($stmt);

            if ($updateResult) {
                echo '<script>alert("Room details updated successfully.");</script>';
            } else {
                echo '<script>alert("Failed to update room details.");</script>';
            }

            mysqli_stmt_close($stmt);
        } else {
            echo '<script>alert("Failed to prepare the statement.");</script>';
        }
    }

    mysqli_close($connection);

    // Redirect the user
    $redirectUrl = 'modify_rooms.php?id=' . urlencode($roomId);
    echo '<script>window.location.href = "' . $redirectUrl . '";</script>';
}
?>
