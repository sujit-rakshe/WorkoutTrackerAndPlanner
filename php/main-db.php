<?php
 // Start session if not already started

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page or handle unauthorized access
    echo '<script>window.location.href = "./Form.php";</script>';
    exit();
}

// Fetch user ID from session
$user_id = $_SESSION['user_id'];

$dbName = 'wpt';  // Change this to your database name
$username = 'postgres';
$conn = pg_connect("dbname=$dbName user=$username");

// Query to fetch user information from users table
$query = "SELECT * FROM users WHERE user_id = $user_id";
$result = pg_query($conn, $query);

if (!$result) {
    die("Error in SQL query: " . pg_last_error());
}

// Fetch user information
$user = pg_fetch_assoc($result);

// Query to fetch the latest user measurement from user_measurements table
$query = "SELECT * FROM user_measurements WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 1";
$result = pg_query($conn, $query);

if (!$result) {
    die("Error in SQL query: " . pg_last_error());
}

// Fetch the latest user measurement
$measurement = pg_fetch_assoc($result);

if (!isset($measurement['weight'])) {
    echo '<script>window.location.href = "php/User-Info-Form.php";</script>';
    exit();
}


// Query to fetch user preferences
$query = "SELECT * FROM user_preference WHERE user_id = $user_id";
$result = pg_query($conn, $query);

if (!$result) {
    die("Error in SQL query: " . pg_last_error());
}

$preferences = pg_fetch_assoc($result);

// Redirect if preferences are not set
if (!isset($preferences['user_id'])) {
    echo '<script>window.location.href = "php/Preferences.php";</script>';
    exit();
}

// Query to get the workout ID for the user on the current day (for example, Monday)
$currentDay = date('l');
$routineQuery = "SELECT RW.workout_id
                 FROM user_routine UR
                 JOIN routineworkouts RW ON UR.routine_id = RW.routine_id
                 WHERE UR.user_id = $user_id
                 AND RW.day_of_week = '$currentDay'";

$routineResult = pg_query($conn, $routineQuery);

if (!$routineResult) {
    die("Query failed: " . pg_last_error());
}

$row = pg_fetch_assoc($routineResult);
$workout_id = $row['workout_id'];

if (!$workout_id) {
    die("Workout ID not found for user on $currentDay");
}

// Query to fetch workout name based on workout ID
$query = "SELECT workoutname FROM workouts WHERE workout_id = $workout_id";

$result = pg_query($conn, $query);

if (!$result) {
    die("Query failed: " . pg_last_error());
}

$row = pg_fetch_assoc($result);

if (!$row) {
    die("Workout not found for ID: $workout_id");
}

$workout_name = $row['workoutname'];

// Query to fetch exercise names for the workout
$query = "SELECT E.name AS exercise_name
          FROM workoutexercises WE
          JOIN exercises E ON WE.exercise_id = E.exercise_id
          WHERE WE.workout_id = $workout_id
          ORDER BY WE.sequence";

$result = pg_query($conn, $query);

if (!$result) {
    die("Query failed: " . pg_last_error());
}



pg_close($conn);
?>

<!-- Assuming the rest of your HTML goes here to display workout information -->

