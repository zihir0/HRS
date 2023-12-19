<!doctype html>
<html lang="en">
<head>
    <title>Hotel Guest Dashboard</title>
    <link rel="stylesheet" type="text/css" href="styles_r.css?v=1">
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #fff;
        }

        header, footer {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }

        .container {
            max-width: 960px;
            margin: 0 auto;
            padding: 20px;
        }

        .content {
            background-color: #fff0;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .room-item {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }

        .room-item img {
            max-width: 630px; /* Adjust the maximum width as needed */
            height: auto;
            border-radius: 5px;
            margin-bottom: 10px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .room-item p {
            margin: 5px 0;
        }

        .room-item a {
            display: inline-block;
            padding: 8px 15px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 3px;
            transition: background-color 0.3s ease;
        }

        .room-item a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="banner">
        <div class="navbar">
            <ul>
                <li><a href="guest_dashboard.php">Home</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </div>

        <div class="content">
            <h1>EXPLORE AVAILABLE ROOMS</h1>
            <p>Choose a room and click to log in for reservation.</p>
            
            <!-- Displaying available rooms -->
            <div class="room-list">
                <?php
                include 'database/connection.php';
                $sql = "SELECT RoomNumber, RoomType, MaxOccupancy, Room_img, RatePerNight FROM room WHERE Availability = 1";
                $result = mysqli_query($connection, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Display room information
                        echo '<div class="room-item">';
                        echo '<img src="' . $row['Room_img'] . '" alt="' . $row['RoomType'] . '">';
                        echo '<p>' . $row['RoomType'] . ' - Room ' . $row['RoomNumber'] . '</p>';
                        echo '<p>Occupancy: ' . $row['MaxOccupancy'] . '</p>';
                        echo '<p>Rate/Night: $' . $row['RatePerNight'] . '</p>';
                        echo '<a href="login.php">Login to Reserve</a>';
                        echo '</div>';
                    }
                } else {
                    echo "No rooms available at the moment.";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
