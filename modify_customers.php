<!DOCTYPE html>
<html>
<head>
    <title>Hotel Reservation - Modify Customers</title>
    <link rel="stylesheet" type="text/css" href="styles_mc.css?v=<?php echo time(); ?>">
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
        <!-- Navbar content -->
        <?php
        if (isset($_GET['name']) && isset($_GET['id'])) {
            $name = $_GET['name'];
            $customerId = $_GET['id'];
            echo '<p class="user-name">' . $name . '</p>';
        } else {
            header("Location: modify_customers.php?name=" . urlencode($name) . "&id=" . $customerId);
            exit;
        }
        ?>
        <ul>
            <li><a href="admin_dashboard.php?name=<?php echo urlencode($name); ?>&id=<?php echo $customerId; ?>">Home</a></li>
            <li><a href="modify_rooms.php?name=<?php echo urlencode($name); ?>&id=<?php echo $customerId; ?>">Modify Rooms</a></li>
            <li><a href="modify_customers.php?name=<?php echo urlencode($name); ?>&id=<?php echo $customerId; ?>">Customers</a></li>
            <li><a href="Logout.php">Logout</a></li>
        </ul>
    </div>
</div>


<div class="content">
    <h1>Hotel Reservation - Modify Customers</h1>

    <div class="customer-list-container">
        <h2>Customer List</h2>
        <?php
        require_once 'database/connection.php';
        $customersQuery = "SELECT * FROM customer";
        $customersResult = mysqli_query($connection, $customersQuery);

        if ($customersResult && mysqli_num_rows($customersResult) > 0) {
            echo '<table>';
            echo '<tr><th>Customer ID</th><th>Name</th><th>Email</th><th>Phone Number</th><th>Age</th><th>Actions</th></tr>';
            while ($customerRow = mysqli_fetch_assoc($customersResult)) {
                echo '<tr>';
                if (isset($customerRow['CustomerID'])) {
                    echo '<td>' . $customerRow['CustomerID'] . '</td>';
                }
                if (isset($customerRow['FirstName']) && isset($customerRow['LastName'])) {
                    echo '<td>' . $customerRow['FirstName'] . ' ' . $customerRow['LastName'] . '</td>';
                }
                if (isset($customerRow['Email'])) {
                    echo '<td>' . $customerRow['Email'] . '</td>';
                }
                if (isset($customerRow['PhoneNumber'])) {
                    echo '<td>' . $customerRow['PhoneNumber'] . '</td>';
                }
                if (isset($customerRow['Age'])) {
                    echo '<td>' . $customerRow['Age'] . '</td>';
                }
                echo '<td>';
                echo '<a class="edit-customer-link" href="#" data-id="' . $customerRow['CustomerID'] . '">Edit</a> | ';
                echo '<a class="delete-customer-link" href="#" data-id="' . $customerRow['CustomerID'] . '">Delete</a>';
                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p>No customers found.</p>';
        }

        mysqli_free_result($customersResult);
        mysqli_close($connection);
        ?>
    </div>
</div>

<!-- Edit Customer Modal -->
<div id="editCustomerModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <!-- Add your edit customer form here -->
        <h2>Edit Customer</h2>
        <form method="post" action="edit_customer.php">
            <input type="hidden" id="editCustomerId" name="editCustomerId">

            <label for="editCustomerFirstName">First Name:</label>
            <input type="text" id="editCustomerFirstName" name="editCustomerFirstName" required><br>

            <label for="editCustomerLastName">Last Name:</label>
            <input type="text" id="editCustomerLastName" name="editCustomerLastName" required><br>

            <label for="editCustomerAge">Age:</label>
            <input type="number" id="editCustomerAge" name="editCustomerAge" required><br>

            <label for="editCustomerEmail">Email:</label>
            <input type="email" id="editCustomerEmail" name="editCustomerEmail" required><br>

            <label for="editCustomerPhone">Phone Number:</label>
            <input type="text" id="editCustomerPhone" name="editCustomerPhone" required><br>

            <input type="submit" value="Save">
            
        </form>
    </div>
</div>

<script>
    var editCustomerLinks = document.getElementsByClassName('edit-customer-link');
    var editCustomerModal = document.getElementById('editCustomerModal');
    var editCustomerIdInput = document.getElementById('editCustomerId');
    var editCustomerFirstNameInput = document.getElementById('editCustomerFirstName');
    var editCustomerLastNameInput = document.getElementById('editCustomerLastName');
    var editCustomerAgeInput = document.getElementById('editCustomerAge');
    var editCustomerEmailInput = document.getElementById('editCustomerEmail');
    var editCustomerPhoneInput = document.getElementById('editCustomerPhone');
    var editCustomerCloseBtn = document.getElementsByClassName('close')[0];

    for (var i = 0; i < editCustomerLinks.length; i++) {
        editCustomerLinks[i].addEventListener('click', function () {
            editCustomerModal.style.display = 'block';
            var customerId = this.getAttribute('data-id');
            var row = this.parentNode.parentNode;
            var customerName = row.cells[1].textContent.split(' ');
            var customerFirstName = customerName[0];
            var customerLastName = customerName[1];
            var customerAge = row.cells[2].textContent;
            var customerEmail = row.cells[3].textContent;
            var customerPhone = row.cells[4].textContent;

            editCustomerIdInput.value = customerId;
            editCustomerFirstNameInput.value = customerFirstName;
            editCustomerLastNameInput.value = customerLastName;
            editCustomerAgeInput.value = customerAge;
            editCustomerEmailInput.value = customerEmail;
            editCustomerPhoneInput.value = customerPhone;
        });
    }

    editCustomerCloseBtn.addEventListener('click', function () {
        editCustomerModal.style.display = 'none';
    });
    
    var deleteCustomerLinks = document.getElementsByClassName('delete-customer-link');

    for (var i = 0; i < deleteCustomerLinks.length; i++) {
        deleteCustomerLinks[i].addEventListener('click', function (e) {
            e.preventDefault();
            var customerId = this.getAttribute('data-id');
            var customerName = this.getAttribute('data-name');
            var confirmDelete = confirm('Are you sure you want to delete ' + customerName + '?');
            
            if (confirmDelete) {
                // Perform AJAX request to delete_customer.php
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Reload the page after successful deletion
                            window.location.reload();
                        } else {
                            // Handle deletion error
                            console.error('Error deleting customer');
                        }
                    }
                };
                
                xhr.open('POST', 'delete_customer.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.send('deleteCustomerId=' + customerId);
            }
        });
    }
</script>
</body>
</html>
