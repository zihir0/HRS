<!doctype html>
<html lang="en">
<head>
    <title>Hotel Reservation System</title>
    <link rel="stylesheet" href="styles_d.css?v=1">
</head>
<body>
    <div class="banner">
        <div class="navbar">
            <div class="user-info">
                <img src="images/fl8W3Vd9_400x400.jpg" class="logo"><?php
                if (isset($_GET['name'])) {
                    $name = $_GET['name'];
                    $id = $_GET['id'];
                    echo '<p class="user-name"> '. $name . '</p>';
                } else {
                    header("location: login.php");
                    exit;
                } ?>
          </div>
          <ul>
            <li><a href="dashboard.php?name=<?php echo urlencode($name); ?>&id=<?php echo $id; ?>">Home</a></li>
            <li><a href="room.php?name=<?php echo urlencode($name); ?>&id=<?php echo $id; ?>">Rooms</a></li>
            <li><a href="reservation.php?name=<?php echo urlencode($name); ?>&id=<?php echo $id; ?>">Reservation</a></li>
            <li><a href="billing.php?name=<?php echo urlencode($name); ?>&id=<?php echo $id; ?>">Billing</a></li>
            <li><a href="Logout.php">Logout</a></li>
        </ul>
        </div>

        <div class="content">
            <h1>RESERVE A ROOM NOW</h1>
            <p>Explore all the rooms and Book the room of your choice</p>
            <div>
            <button type="button" class="button"><span></span><a href="room.php?name=<?php echo urlencode($name); ?>&id=<?php echo $id; ?>">CHECK ROOMS</a></button>
            <button type="button" class="button"><span></span><a href="reservation.php?name=<?php echo urlencode($name); ?>&id=<?php echo $id; ?>">YOUR RESERVED ROOMS</a></button>
            </div>
        </div>
    </div>
</body>
</html>
