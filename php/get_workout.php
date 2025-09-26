<?php
// Assuming you have established a database connection
session_start();
if (isset($_SESSION['user_id'])) {
    // Fetch user ID from session
    $user_id = $_SESSION['user_id'];

    $dbName = 'wpt';  // Change this to your database name
    $username = 'postgres';
    $conn = pg_connect("dbname=$dbName user=$username");

    // Get the current day of the week
    $currentDay = date('l');

    // Query to get the workout ID for the user on the current day
    $routineQuery = "SELECT RW.workout_id
                     FROM user_routine UR
                     JOIN routineworkouts RW ON UR.routine_id = RW.routine_id
                     WHERE UR.user_id = $user_id
                     AND RW.day_of_week = 'Monday'";

    $routineResult = pg_query($conn, $routineQuery);

    if (!$routineResult) {
        die("Query failed: " . pg_last_error());
    }

    $row = pg_fetch_assoc($routineResult);
    $workout_id = $row['workout_id'];

    // Query to get exercise names for the workout ID
    $query = "SELECT E.name AS exercise_name
              FROM workoutexercises WE
              JOIN exercises E ON WE.exercise_id = E.exercise_id
              WHERE WE.workout_id = $workout_id
              ORDER BY WE.sequence";

    $result = pg_query($conn, $query);

    if (!$result) {
        die("Query failed: " . pg_last_error());
    }

    $exerciseNames = array();
    while ($row = pg_fetch_assoc($result)) {
        $exerciseNames[] = $row['exercise_name'];
    }

    // Close the connection
    pg_close($conn);

    // JSON response with exercise names
    $response = array("workout_id" => $workout_id, "exercise_names" => $exerciseNames);

    // Send proper JSON content type header
    header('Content-Type: application/json');
    
    // Ensure no BOM characters in output
    echo json_encode($response);
} else {
    header("Location: ../index.php");
    exit();
}
?>

