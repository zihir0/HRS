<!DOCTYPE html>
<html>
<head>
    <title>Hotel Reservation - Modify Rooms</title>
    <link rel="stylesheet" type="text/css" href="styles_mr.css">
    <style>
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
        
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .navbar {
            position: relative;
            z-index: 2; /* Increase the z-index to make the navbar appear on top */
        }
    </style>
</head>
<body>
    <div class="banner">
        <div class="navbar">
            <div class="user-info">
                <img src="images/fl8W3Vd9_400x400.jpg" class="logo">
                <?php
                // Check if the name and ID parameters are set
                if (isset($_GET['name']) && isset($_GET['id'])) {
                    $name = $_GET['name'];
                    $customerId = $_GET['id'];
                    echo '<p class="user-name">' . $name . '</p>';
                }
                ?>
            </div>
            <ul>
                <li><a href="admin_dashboard.php?name=<?php echo urlencode($name); ?>&id=<?php echo $customerId; ?>">Home</a></li>
                <li><a href="modify_rooms.php?name=<?php echo urlencode($name); ?>&id=<?php echo $customerId; ?>">Modify Rooms</a></li>
                <li><a href="modify_customers.php?name=<?php echo urlencode($name); ?>&id=<?php echo $customerId; ?>">Customers</a></li>
                <li><a href="Logout.php">Logout</a></li>
            </ul>
        </div>
    </div>

        <div class="content">
            <h1>Hotel Reservation - Modify Rooms</h1>
            <div class="room-list-container">
            <h2>Room List</h2>
            <?php
            require_once 'database/connection.php';

            $roomsQuery = "SELECT * FROM room";
            $roomsResult = mysqli_query($connection, $roomsQuery);

            if ($roomsResult && mysqli_num_rows($roomsResult) > 0) {
                echo '<table>';
                echo '<tr><th>Room Number</th><th>Room Type</th><th>Max Occupancy</th><th>Rate Per Night</th><th>Availability</th><th>Room Image</th></tr>';
                while ($roomRow = mysqli_fetch_assoc($roomsResult)) {
                    echo '<tr>';
                    echo '<td>' . $roomRow['RoomNumber'] . '</td>';
                    echo '<td>' . $roomRow['RoomType'] . '</td>';
                    echo '<td>' . $roomRow['MaxOccupancy'] . '</td>';
                    echo '<td>' . $roomRow['RatePerNight'] . '</td>';
                    echo '<td>' . $roomRow['Availability'] . '</td>';
                    echo '<td><img src="' . $roomRow['Room_img'] . '" alt="Room Image" style="width: 500px; height: 200px; border-radius: 15px;"></td>';
                    echo '<td><button class="edit-room-link">Edit</button></td>';
                    echo '<td><button class="delete-room-link">Delete</button></td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo '<p>No rooms found.</p>';
            }

            mysqli_free_result($roomsResult);
            mysqli_close($connection);
            ?>

            <button id="addRoomButton">Add Room</button>
        </div>
    </div>

   <!-- Edit Room Modal -->
    <div id="editRoomModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Room</h2>
            <form method="post" action="edit_room.php">
                <input type="hidden" id="editRoomId" name="editRoomId">

                <label for="editRoomImg">Room Image URL:</label>
                <input type="text" id="editRoomImg" name="editRoomImg" required><br>

                <label for="editRoomType">Room Type:</label>
                <input type="text" id="editRoomType" name="editRoomType" required><br>

                <label for="editMaxOccupancy">Max Occupancy:</label>
                <input type="number" id="editMaxOccupancy" name="editMaxOccupancy" required><br>

                <label for="editRatePerNight">Rate Per Night:</label>
                <input type="number" id="editRatePerNight" name="editRatePerNight" required><br>

                <label for="editAvailability">Availability:</label>
                <input type="text" id="editAvailability" name="editAvailability" required><br>

                <input type="submit" value="Save">
            </form>
        </div>
    </div>

    <!-- Delete Room Modal -->
    <div id="deleteRoomModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <!-- Delete Room Form -->
            <h2>Delete Room</h2>
            <form method="post" action="delete_room.php">
                <input type="hidden" id="deleteRoomId" name="deleteRoomId">
                <p>Are you sure you want to delete this room?</p>
                <input type="submit" value="Delete">
            </form>
        </div>
    </div>

    <!-- Add Room Modal -->
    <div id="addRoomModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <!-- Add Room Form -->
            <h2>Add Room</h2>
            <form method="post" action="add_room.php">
                <label for="room_img_url">Room Image URL:</label>
                <input type="text" id="room_img_url" name="room_img_url" required><br>

                <label for="room_type">Room Type:</label>
                <input type="text" id="room_type" name="room_type" required><br>

                <label for="max_occupancy">Max Occupancy:</label>
                <input type="number" id="max_occupancy" name="max_occupancy" required><br>

                <label for="rate_per_night">Rate Per Night:</label>
                <input type="number" id="rate_per_night" name="rate_per_night" required><br>

                <label for="availability">Availability:</label>
                <input type="text" id="availability" name="availability" required><br>

                <input type="submit" value="Add Room">
            </form>
        </div>
    </div>


    <script>
        // Edit Room functionality
        var editRoomLinks = document.getElementsByClassName('edit-room-link');
        var editRoomModal = document.getElementById('editRoomModal');
        var editRoomIdInput = document.getElementById('editRoomId');
        var editRoomTypeInput = document.getElementById('editRoomType'); // Added
        var editMaxOccupancyInput = document.getElementById('editMaxOccupancy'); // Added
        var editRatePerNightInput = document.getElementById('editRatePerNight'); // Added
        var editAvailabilityInput = document.getElementById('editAvailability'); // Added
        var editRoomImgInput = document.getElementById('editRoomImg'); // Added
        var editRoomCloseBtn = document.getElementsByClassName('close')[0];

        // Inside the script that opens the "Edit Room" modal
        for (var i = 0; i < editRoomLinks.length; i++) {
            editRoomLinks[i].addEventListener('click', function () {
                editRoomModal.style.display = 'block';
                var row = this.parentNode.parentNode;
                var roomId = row.cells[0].textContent;
                var roomType = row.cells[1].textContent;
                var maxOccupancy = row.cells[2].textContent;
                var ratePerNight = row.cells[3].textContent;
                var availability = row.cells[4].textContent;
                var roomImg = row.cells[5].querySelector('img').src;

                editRoomIdInput.value = roomId;
                editRoomTypeInput.value = roomType;
                editMaxOccupancyInput.value = maxOccupancy;
                editRatePerNightInput.value = ratePerNight;
                editAvailabilityInput.value = availability;
                editRoomImgInput.value = roomImg;
            });
        }



        // Delete Room functionality
        var deleteRoomLinks = document.getElementsByClassName('delete-room-link');
        var deleteRoomModal = document.getElementById('deleteRoomModal');
        var deleteRoomIdInput = document.getElementById('deleteRoomId');
        var deleteRoomCloseBtn = document.getElementsByClassName('close')[1];

        for (var i = 0; i < deleteRoomLinks.length; i++) {
            deleteRoomLinks[i].addEventListener('click', function () {
                deleteRoomModal.style.display = 'block';
                var row = this.parentNode.parentNode;
                var roomId = row.cells[0].textContent;
                deleteRoomIdInput.value = roomId;
            });
        }

        // Add Room functionality
        var addRoomModal = document.getElementById('addRoomModal');
        var addRoomButton = document.getElementById('addRoomButton');
        var addRoomCloseBtn = document.getElementsByClassName('close')[2];

        addRoomButton.addEventListener('click', function () {
            addRoomModal.style.display = 'block';
        });



        // Close the modal if the user clicks outside of it
        window.addEventListener('click', function (event) {
            if (event.target == editRoomModal) {
                editRoomModal.style.display = 'none';
            }
            if (event.target == deleteRoomModal) {
                deleteRoomModal.style.display = 'none';
            }
            if (event.target == addRoomModal) {
                addRoomModal.style.display = 'none';
            }
        });
    
         // Validate the navbar links after modifying a room
        function validateNavbarLinks() {
            var navbarLinks = document.querySelectorAll('.navbar ul li a');
            navbarLinks.forEach(function (link) {
                link.addEventListener('click', function (event) {
                    window.location.href = this.getAttribute('href');
                    event.preventDefault();
                });
            });
        }

        // Re-validate navbar links after room modification
        function revalidateNavbarLinks() {
            validateNavbarLinks();
            handleRoomModification();
        }

        // Call the function to validate navbar links after each action
        function handleRoomModification() {
            // Close the modals on successful room modification
            editRoomCloseBtn.addEventListener('click', function () {
                editRoomModal.style.display = 'none';
                revalidateNavbarLinks();
            });

            deleteRoomCloseBtn.addEventListener('click', function () {
                deleteRoomModal.style.display = 'none';
                revalidateNavbarLinks();
            });

            addRoomCloseBtn.addEventListener('click', function () {
                addRoomModal.style.display = 'none';
                revalidateNavbarLinks();
            });

            // Ensure navbar links validation after any room modification action
            window.addEventListener('click', function (event) {
                if (event.target == editRoomModal || event.target == deleteRoomModal || event.target == addRoomModal) {
                    revalidateNavbarLinks();
                }
            });
        }

        // Call handleRoomModification function on window load
        window.addEventListener('load', function () {
            handleRoomModification();
            validateNavbarLinks();
        });
    </script>
</body>
</html>
