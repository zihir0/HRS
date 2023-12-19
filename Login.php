<?php
include 'database/connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = mysqli_real_escape_string($connection, $_POST['email']);
        $password = mysqli_real_escape_string($connection, $_POST['password']);
        
        // Query to fetch customer data based on the provided email
        $customerQuery = "SELECT * FROM customer WHERE Email = '$email'";
        $customerResult = mysqli_query($connection, $customerQuery);

        // Query to fetch admin data based on the provided email
        $adminQuery = "SELECT * FROM admin WHERE Email = '$email'";
        $adminResult = mysqli_query($connection, $adminQuery);

        if ($customerResult && mysqli_num_rows($customerResult) > 0) {
            $customer = mysqli_fetch_assoc($customerResult);
            $hashedPassword = $customer['Password']; // Use password_verify() for validation

            if (password_verify($password, $hashedPassword)) {
                $_SESSION['customer_id'] = $customer['CustomerID'];
                header("Location: dashboard.php?name={$customer['FirstName']}&id={$customer['CustomerID']}");
                exit;
            } else {
                echo "Incorrect password!";
                exit;
            }
        } elseif ($adminResult && mysqli_num_rows($adminResult) > 0) {
            $admin = mysqli_fetch_assoc($adminResult);
            
            if (password_verify($password, $admin['Password'])) {
                $_SESSION['admin_id'] = $admin['adminID'] ?? ''; // Ensure 'adminID' is set or initialize as empty
                header("Location: admin_dashboard.php?name={$admin['FirstName']}&id={$_SESSION['admin_id']}");
                exit;
            } else {
                echo "Incorrect password!";
                var_dump($adminResult); // Debugging statement
                var_dump($admin); // Debugging statement
                exit;
            }
        } else {
            echo "Admin email not found!";
            var_dump($adminResult); // Debugging statement
            exit;
        }
    }
}
?>







<!doctype html>
<html lang="en">
<head>
  <title>Webleb</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.9/css/unicons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="styles2.css">
</head>

<body>
<div class="section">
    <div class="container">
        <div class="row full-height justify-content-center">
            <div class="col-12 text-center align-self-center py-5">
                <div class="section pb-5 pt-5 pt-sm-2 text-center">
                    <h6 class="mb-0 pb-3"><span>Log In </span><span>Sign Up</span></h6>
                    <input class="checkbox" type="checkbox" id="reg-log" name="reg-log"/>
                    <label for="reg-log"></label>
                    <div class="card-3d-wrap mx-auto">
                        <div class="card-3d-wrapper">
                            <div class="card-front">
                                <div class="center-wrap">
                                    <div class="section text-center">
                                        <h4 class="mb-4 pb-3">Log In</h4>
                                        <form action="login.php" method="POST">
                                            <div class="form-group">
                                                <input type="email" class="form-style" placeholder="Email" name="email" required>
                                                <i class="input-icon uil uil-at"></i>
                                            </div>
                                            <div class="form-group mt-2">
                                                <input type="password" class="form-style" placeholder="Password" name="password" required>
                                                <i class="input-icon uil uil-lock-alt"></i>
                                            </div>
                                            <button type="submit" class="btn mt-4">Login</button>
                                        </form>
                                        <p class="mb-0 mt-4 text-center"><a href="" class="link">Forgot your password?</a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-back">
                                <div class="center-wrap">
                                    <div class="section text-center">
                                        <h4 class="mb-3 pb-3">Sign Up</h4>
                                        <form action="register.php" method="POST">
                                            <div class="form-group">
                                                <input type="text" class="form-style" placeholder="Full Name" name="full_name" required>
                                                <i class="input-icon uil uil-user"></i>
                                            </div>
                                            <div class="form-group mt-2">
                                                <input type="tel" class="form-style" placeholder="Phone Number" name="phone_number" required>
                                                <i class="input-icon uil uil-phone"></i>
                                            </div>
                                            <div class="form-group mt-2">
                                                <input type="number" class="form-style" placeholder="Age" name="age" required>
                                                <i class="input-icon uil uil-phone"></i>
                                            </div>
                                            <div class="form-group mt-2">
                                                <input type="email" class="form-style" placeholder="Email" name="email_2" required>
                                                <i class="input-icon uil uil-at"></i>
                                            </div>
                                            <div class="form-group mt-2">
                                                <input type="password" class="form-style" placeholder="Password" name="password_2" required>
                                                <i class="input-icon uil uil-lock-alt"></i>
                                            </div>
                                            <button type="submit" class="btn mt-4">Register</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
