
<?php
// Assuming you have established a database connection
session_start();

$user_id = $_SESSION['user_id']; // Example user ID, replace with actual user ID
$goals = $_POST['fitness-goals'];
$type = $_POST['exercise-type'];
$frequency = $_POST['workout-frequency'];
$level = $_POST['current-fitness-level'];// Example height in centimeters

$dbName = 'wpt';  // Change this to your database name
$username = 'postgres';
$conn = pg_connect(" dbname=$dbName user=$username");

$checkQuery = "SELECT * FROM user_preference WHERE user_id = $user_id";
$checkResult = pg_query($conn, $checkQuery);

if (!$checkResult) {
    die("Query failed: " . pg_last_error());
}

if (pg_num_rows($checkResult) > 0) {
    // User preferences already exist, update them
    $updateQuery = "UPDATE user_preference SET 
                    fitness_goals = '$goals',
                    exercise_type = '$type',
                    workout_frequency = '$frequency',
                    current_fitness_level = '$level'
                    WHERE user_id = $user_id";

    $updateResult = pg_query($conn, $updateQuery);

    if (!$updateResult) {
        die("Update query failed: " . pg_last_error());
    } else {
        echo "User preferences updated successfully!";
    }
} else {
    // User preferences do not exist, insert them
    $insertQuery = "INSERT INTO user_preference (user_id, fitness_goals, exercise_type, workout_frequency, current_fitness_level) 
                    VALUES ($user_id, '$goals', '$type', '$frequency', '$level')";

    $insertResult = pg_query($conn, $insertQuery);

    if (!$insertResult) {
        die("Insert query failed: " . pg_last_error());
    } else {
        echo "User preferences inserted successfully!";
    }
}

$equipment = ($type === 'Weight Training') ? 'true' : 'false';
$no_of_weeks = $frequency;

$insertOrUpdateQuery = "INSERT INTO user_routine (user_id, routine_id) 
                        VALUES ($user_id, (SELECT routine_id FROM routines WHERE no_of_week = $no_of_weeks AND equipment = $equipment))
                        ON CONFLICT (user_id) DO UPDATE SET routine_id = EXCLUDED.routine_id";

$insertOrUpdateResult = pg_query($conn, $insertOrUpdateQuery);

if (!$insertOrUpdateResult) {
    die("Insert/update user_routine query failed: " . pg_last_error());
} else {
    echo "User routine inserted/updated successfully!";
}
// Close the connection
pg_close($conn);
header("Location: ../index.php");  // Redirect to dashboard or any other page
exit();
?>

