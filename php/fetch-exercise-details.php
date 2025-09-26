<?php
// Assuming you have established a database connection
$dbName = 'wpt';  // Change this to your database name
$username = 'postgres';
$conn = pg_connect("dbname=$dbName user=$username");

// Check if exercise_name is provided and not empty
if (isset($_GET['exercise_name']) && !empty($_GET['exercise_name'])) {
    $exerciseName = $_GET['exercise_name'];

    // Query to fetch exercise details based on exercise_name
    $query = "SELECT * FROM exercises WHERE name = '$exerciseName'";
    
    // Prepare the statement
    $stmt = pg_prepare($conn, "fetch_exercise", $query);
    
    if (!$stmt) {
        die("Error in preparing statement: " . pg_last_error());
    }

    // Execute the statement with the provided exercise name
    $result = pg_execute($conn, "fetch_exercise", array($exerciseName));
    
    if (!$result) {
        die("Error in executing statement: " . pg_last_error());
    }

    // Fetch the row
    $exercise = pg_fetch_assoc($result);

    if ($exercise) {
        // Build HTML for exercise details
        $exerciseDetailsHTML = '
            <h3>' . $exercise['name'] . '</h3>
            <div id="exerciseInfo">
                <div id="exerciseImageContainer">
                    <img src="../src/' . $exercise['image_url'] . '" alt="' . $exercise['name'] . '" class="exercise-image">
                </div>
                <div id="exerciseDetails">
                    <h4>Description:</h4>
                    <p>' . $exercise['description'] . '</p>
                    <h4>Target Muscle:</h4>
                    <p>' . $exercise['target_muscle'] . '</p>
                </div>
            </div>
        ';

        // Return the HTML response
        echo $exerciseDetailsHTML;
    } else {
        echo "Exercise not found.";
    }
} else {
    echo "Exercise name not provided.";
}

// Close the database connection
pg_close($conn);
?>

