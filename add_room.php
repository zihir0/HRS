<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'database/connection.php';

    $roomImgUrl = $_POST['room_img_url'] ?? '';
    $roomType = $_POST['room_type'] ?? '';
    $maxOccupancy = intval($_POST['max_occupancy'] ?? 0);
    $ratePerNight = floatval($_POST['rate_per_night'] ?? 0);
    
    // Defaulting availability to 1 for available room
    $availability = 1;
    
    $insertQuery = "INSERT INTO room (RoomType, MaxOccupancy, Room_img, RatePerNight, Availability) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connection, $insertQuery);
    
    // ... rest of your code remains the same
    

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sisds", $roomType, $maxOccupancy, $roomImgUrl, $ratePerNight, $availability);

        $insertResult = mysqli_stmt_execute($stmt);

        if ($insertResult) {
            header("Location: modify_rooms.php");
            exit();
        } else {
            echo '<script>alert("Failed to add room.");</script>';
        }

        mysqli_stmt_close($stmt);
    } else {
        echo '<script>alert("Failed to prepare statement.");</script>';
    }

    mysqli_close($connection);
}
?>
