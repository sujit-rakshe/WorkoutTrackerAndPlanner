<?php
session_start();

if (isset($_SESSION['user_id'])) {
    // Assuming you have established a database connection
    $dbName = 'wpt';  // Change this to your database name
    $username = 'postgres';
    $conn = pg_connect("dbname=$dbName user=$username");

    // Get user ID from session
    $user_id = $_SESSION['user_id'];

    // Get workout data from POST request
    $workoutData = $_POST['workout_data']; // Retrieve workout data

    // Parse the JSON data
    $decodedData = json_decode($workoutData, true);

    // Extract workout details
    $routine_id = $decodedData['routine_id'];
    $workout_id = $decodedData['workout_id'];
    $workout_name = $decodedData['workout_name'];
    $exercises = $decodedData['exercises'];

    // Insert into userworkouts table to get user_workout_id
    $workoutDate = date('Y-m-d H:i:s');

    $insertWorkoutQuery = "INSERT INTO userworkouts (user_id, workout_id, workout_date)
                           VALUES ($user_id, $workout_id, '$workoutDate')
                           RETURNING user_workout_id";

    $result = pg_query($conn, $insertWorkoutQuery);

    if (!$result) {
        $error_message = "Insert into userworkouts failed: " . pg_last_error($conn);
        $response = array("success" => false, "message" => $error_message);
        echo json_encode($response);
        exit();
    }

    $row = pg_fetch_assoc($result);
    $user_workout_id = $row['user_workout_id'];

    // Insert workout details into user_workout_logs table
    foreach ($exercises as $exercise) {
        $exercise_id = $exercise['exercise_id'];
        $set1 = $exercise['set1'];
        $set2 = $exercise['set2'];
        $set3 = $exercise['set3'];
        $unit = $exercise['unit']; // Assuming this is the unit for the exercise

        // Escape values to prevent SQL injection
        $routine_id = pg_escape_string($conn, $routine_id);
        $workout_id = pg_escape_string($conn, $workout_id);
        $exercise_id = pg_escape_string($conn, $exercise_id);
        $set1 = pg_escape_string($conn, $set1);
        $set2 = pg_escape_string($conn, $set2);
        $set3 = pg_escape_string($conn, $set3);
        $unit = pg_escape_string($conn, $unit);
        $user_workout_id = pg_escape_string($conn, $user_workout_id);

        // Insert data into user_workout_logs table
        $insertQuery = "INSERT INTO user_workout_logs (user_workout_id, exercise_id, set1, set2, set3, unit)
                        VALUES ($user_workout_id, $exercise_id, $set1, $set2, $set3, '$unit')";

        $insertResult = pg_query($conn, $insertQuery);

        if (!$insertResult) {
            $error_message = "Insert into user_workout_logs failed: " . pg_last_error($conn);
            $response = array("success" => false, "message" => $error_message);
            echo json_encode($response);
            exit();
        }
    }

    // Close the connection
    pg_close($conn);

    // Return success response
    $response = array("success" => true, "message" => "Workout data saved successfully.");
    echo json_encode($response);
} else {
    // Return error if user is not logged in
    $response = array("success" => false, "message" => "User not logged in.");
    echo json_encode($response);
}
?>

