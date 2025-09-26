<?php
// Assuming you have established a database connection

if (isset($_GET['workout_id'])) {
    $workout_id = $_GET['workout_id'];

    $dbName = 'wpt';  // Change this to your database name
    $username = 'postgres';
    $conn = pg_connect("dbname=$dbName user=$username");

    // Query to get the workout name
    $workoutQuery = "SELECT workoutname FROM workouts WHERE workout_id = $workout_id";
    $workoutResult = pg_query($conn, $workoutQuery);
    $workout = pg_fetch_assoc($workoutResult)['workoutname'];

    // Query to get exercises for the specified workout ID
    $query = "SELECT E.name AS exercise, E.description AS description, E.image_url AS image_url, E.target_muscle AS target_muscle
              FROM workoutexercises WE
              JOIN exercises E ON WE.exercise_id = E.exercise_id
              WHERE workout_id = $workout_id
              ORDER BY WE.sequence";

    $result = pg_query($conn, $query);

    if (!$result) {
        die("Query failed: " . pg_last_error());
    }

    $exercises = array();
    while ($row = pg_fetch_assoc($result)) {
        $exercise = array(
            "exercise_name" => $row['exercise'],
            "description" => $row['description'],
            "image_url" => $row['image_url'],
            "target_muscle" => $row['target_muscle']
        );
        $exercises[] = $exercise;
    }

    // Close the connection
    pg_close($conn);

    // Combine workout name and exercises into a single array
    $response = array("workout" => $workout, "exercises" => $exercises);

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Handle case when workout_id is not provided
    echo json_encode(array("error" => "Workout ID not provided"));
}
?>

