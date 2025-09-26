<?php
// Assuming you have established a database connection
session_start();
$user_id = $_SESSION['user_id']; // Example user ID, replace with actual user ID
$firstName = $_POST['name'];
$lastName = $_POST['lastname'];
$age = $_POST['age'];
$height = $_POST['height'];// Example height in centimeters
$weight = $_POST['weight'];// Example weight in kilograms
$gender = $_POST['gender']; // Example gender
$bmi = calculateBMI($height, $weight); // Function to calculate BMI
$bmr = calculateBMR($height, $weight, $age, $gender); // Function to calculate BMR

// Prepare and execute the SQL statement to insert into user_measurements table
$dbName = 'wpt';  // Change this to your database name
$username = 'postgres';
$conn = pg_connect(" dbname=$dbName user=$username");

$query = "UPDATE users SET first_name='$firstName', last_name='$lastName' WHERE user_id='$user_id'";

    // Execute the query
    $result = pg_query($conn, $query);
    if (!$result) {
        die("Error updating profile: " . pg_last_error($conn));
    }

// Escape values to prevent SQL injection
    $height = pg_escape_string($conn, $height);
    $weight = pg_escape_string($conn, $weight);
    $gender = pg_escape_string($conn, $gender);
    $bmi = pg_escape_string($conn, $bmi);
    $bmr = pg_escape_string($conn, $bmr);

    // Insert query
    $query = "INSERT INTO user_measurements (user_id, height, weight, gender, bmi, bmr) VALUES ('$user_id', '$height', '$weight', '$gender', '$bmi', '$bmr')";

    // Execute the query
    $result = pg_query($conn, $query);
    if (!$result) {
        die("Error inserting user measurements: " . pg_last_error($conn));
    }
    
// Functions to calculate BMI, BMR, and body fat percentage
function calculateBMI($height, $weight) {
    // BMI calculation formula: BMI = weight (kg) / (height (m))^2
    $heightInMeters = $height / 100; // Convert height from centimeters to meters
    return $weight / ($heightInMeters * $heightInMeters);
}

function calculateBMR($height, $weight, $age, $gender) {
    // BMR calculation formula (Harris-Benedict equation)
    // For males: BMR = 88.362 + (13.397 * weight in kg) + (4.799 * height in cm) - (5.677 * age in years)
    // For females: BMR = 447.593 + (9.247 * weight in kg) + (3.098 * height in cm) - (4.330 * age in years)
    if ($gender === 'male') {
        return 88.362 + (13.397 * $weight) + (4.799 * $height) - (5.677 * $age);
    } elseif ($gender === 'female') {
        return 447.593 + (9.247 * $weight) + (3.098 * $height) - (4.330 * $age);
    } else {
        // Default to male BMR calculation
        return 88.362 + (13.397 * $weight) + (4.799 * $height) - (5.677 * $age);
    }
}


header("Location: ../index.php");  // Redirect to dashboard or any other page
exit();

