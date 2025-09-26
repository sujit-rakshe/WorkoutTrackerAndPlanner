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
    $dob = $_POST['dob'];
    $password = $_POST['password'];  // Hash the password

    // Connect to PostgreSQL database
    $conn = pg_connect(" dbname=$dbName user=$username");

    // Check connection
    if (!$conn) {
        die("Connection failed: " . pg_last_error());
    }

    // Check if email already exists
    $checkQuery = "SELECT * FROM users WHERE email = '$email'";
    $checkResult = pg_query($conn, $checkQuery);

    if (!$checkResult) {
        die("Query failed: " . pg_last_error());
    }

    $rows = pg_num_rows($checkResult);
    if ($rows > 0) {
        $_SESSION['signup_error'] = "Email already exists. Please choose a different email.";
        header("Location: form.php");
        exit();
    }

    // Insert new user
    $insertQuery = "INSERT INTO users (email, date_of_birth, password) VALUES ('$email', '$dob', '$password')";
    $insertResult = pg_query($conn, $insertQuery);

    if (!$insertResult) {
        die("Insert query failed: " . pg_last_error());
    }

		// Execute the UPDATE query
$updateQuery = "UPDATE users SET age = calculate_age(date_of_birth)";
$updateResult = pg_query($conn, $updateQuery);

if (!$updateResult) {
    die("Update query failed: " . pg_last_error());
} else {
    echo "Age updated successfully.";
}


    $_SESSION['signup_success'] = "Signup successful. You can now login.";
    header("Location: form.php");
    exit();

    // Close connection
    pg_close($conn);
} else {
    // If not a POST request, redirect to form.php
    header("Location: form.php");
    exit();
}
?>

