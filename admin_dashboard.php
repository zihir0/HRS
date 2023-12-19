<!doctype html>
<html lang="en">
<head>
    <title>Admin Dashboard - Hotel Reservation System</title>
    <link rel="stylesheet" href="styles_d.css?v=1">
    <style>
        /* Additional CSS for styling enhancements */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .banner {
            background-color: #f5f5f5;
            padding: 20px;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .logo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .user-name {
            font-weight: bold;
        }

        ul {
            list-style: none;
            display: flex;
        }

        ul li {
            margin-right: 20px;
        }

        ul li a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }

        .content {
            padding: 20px;
        }

        h1 {
            margin-bottom: 20px;
        }

        .buttons-container {
            display: flex;
            justify-content: center;
        }

        .button {
            padding: 10px 20px;
            margin: 0 10px;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="banner">
        <div class="navbar">
            <div class="user-info">
                <img src="images/fl8W3Vd9_400x400.jpg" class="logo">
                <?php
                session_start();

                if (isset($_SESSION['admin_id'])) {
                    $name = $_GET['name'] ?? 'Admin'; // If name is not set, default to 'Admin'
                    $id = $_GET['id'] ?? '';

                    // Display user name if needed
                    echo '<p class="user-name">' . $name . '</p>';
                } else {
                    header("location: login.php");
                    exit;
                }
                ?>

            </div>
            <ul>
                <li><a href="admin_dashboard.php?name=<?php echo urlencode($name); ?>&id=<?php echo urlencode($id); ?>">Home</a></li>
                <li><a href="modify_rooms.php?name=<?php echo urlencode($name); ?>&id=<?php echo $id; ?>">Modify Rooms</a></li>
                <li><a href="modify_customers.php?name=<?php echo urlencode($name); ?>&id=<?php echo $id; ?>">Customers</a></li>
                <li><a href="Logout.php?name=<?php echo urlencode($name); ?>&id=<?php echo $id; ?>">Logout</a></li>
            </ul>
        </div>

        <div class="content">
            <h1>Welcome, <?php echo $name; ?>!</h1>
            <div class="buttons-container">
                <!-- Add admin-specific functionalities here -->
                <a href="modify_rooms.php?name=<?php echo urlencode($name); ?>&id=<?php echo $id; ?>" class="button">Modify Rooms</a>
                <a href="modify_customers.php?name=<?php echo urlencode($name); ?>&id=<?php echo $id; ?>" class="button">Manage Customers</a>
            </div>
        </div>
    </div>
</body>
</html>
