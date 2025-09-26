<?php
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database credentials
    // Change this to your host if different
    $dbName = 'wpt';  // Change this to your database name
    $username = 'postgres';  // Change this to your database username
    // Change this to your database password

    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];// Hash the password	
	    // You can set a simple username and password for demonstration purposes.
    // Connect to PostgreSQL database
    $conn = pg_connect(" dbname=$dbName user=$username");
    
    
 		$query = "SELECT * FROM users WHERE email = '$email'";
    $result = pg_query($conn, $query);

    if (!$result) {
        die("Query failed: " . pg_last_error());
    }

    $user = pg_fetch_assoc($result);
    		
        // Check if user exists
        if ($user) {
            // Verify password
            if ($password === $user['password']) {
                // Password is correct, log in the user
                $_SESSION['user_id'] = $user['user_id'];  // Store user ID in session or any other necessary data
                header("Location: ../index.php");  // Redirect to dashboard or any other page
                exit();
            } else {
                // Password is incorrect
                $_SESSION['login_error'] = "Invalid email or password.1";
                header("Location: form.php");
                exit();
            }
        } else {
            // User with that email does not exist
            $_SESSION['login_error'] = "Invalid email or password.2";
            header("Location: form.php");
            exit();
        }
    

    // Close connection
    $conn = null;
} else {
    // If not a POST request, redirect to login.php
    header("Location: form.php");
    exit();
}
?>



