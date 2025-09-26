<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/navbar.css"> 
    <style>
        body {
            font-family: Arial, sans-serif;
           
        }
        
        .Full-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            margin-top: 30px;
        }
        .column {
        
            width: 45%;
            
            
           
            margin: 40px auto;
            text-align: left;
            padding: 25px;
            background-color: rgba(0, 0, 0, 0.7); /* Background color with opacity */
            border-radius: 10px;
        
        }
        .column h2 {
        font-size:40px;
        text-align:center;
            margin: 10px;
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: rgba(252, 44, 3, 1); 
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }
        .button:hover {
            background-color: rgba(252, 44, 3, 1); 
        }
        .your-div {
    width: 45%;
    /* Set height as needed */
    background-image: url('../src/goku.png');
    background-size: cover;
    background-position: center;
    opacity: 0.9; /* Set the opacity level (0.0 to 1.0) */
    color: white; /
     /* White with 50% opacity */
} 
.your-div1 {
    width: 45%;
    /* Set height as needed */
    background-image: url('../src/zoro.jpeg');
    background-size: cover;
    background-position: center;
    opacity: 0.9; /* Set the opacity level (0.0 to 1.0) */
    color: white; /
     /* White with 50% opacity */
} 
    </style>
</head>
<body>
<?php
session_start();
if (isset($_SESSION['user_id'])) {

 include '../php/navbar.php'; 
 
$dbname = 'wpt'; // Change this to your database name
$username = 'postgres'; // Change this to your database username

$conn = pg_connect(" dbname=$dbname user=$username");

if (!$conn) {
    die("Connection failed");
}

// Assuming you have the user_id stored in the session
if (isset($_SESSION['user_id'])) {
    // Fetch user ID from session
    $user_id = $_SESSION['user_id'];

    // Query to fetch user information from users table
    $query = "SELECT * FROM users WHERE user_id = $user_id";
    $result = pg_query($conn, $query);

    if (!$result) {
        die("Error in SQL query: " . pg_last_error());
    }

    // Fetch user information
    $user = pg_fetch_assoc($result);

    // Query to fetch user preferences from user_preference table
    $query = "SELECT * FROM user_preference WHERE user_id = $user_id";
    $result = pg_query($conn, $query);

    if (!$result) {
        die("Error in SQL query: " . pg_last_error());
    }

    // Fetch user preferences
    $preferences = pg_fetch_assoc($result);

    // Query to fetch the latest user measurement from user_measurements table
    $query = "SELECT * FROM user_measurements WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 1";
    $result = pg_query($conn, $query);

    if (!$result) {
        die("Error in SQL query: " . pg_last_error());
    }

    // Fetch the latest user measurement
    $measurement = pg_fetch_assoc($result);

    // Query to fetch user routine from user_routine table
    $query = "SELECT * FROM user_routine WHERE user_id = $user_id";
    $result = pg_query($conn, $query);

    if (!$result) {
        die("Error in SQL query: " . pg_last_error());
    }

    // Fetch user routine
    $routine = pg_fetch_assoc($result);

    // If routine is found, fetch the routine name from the routines table
    $routine_name = '';
    if ($routine) {
        $routine_id = $routine['routine_id'];
        $routine_query = "SELECT name FROM routines WHERE routine_id = $routine_id";
        $routine_result = pg_query($conn, $routine_query);
        if ($routine_row = pg_fetch_assoc($routine_result)) {
            $routine_name = $routine_row['name'];
        }
    }

    // Display user information, preferences, latest measurement, and routine name in two columns
    echo "<div class='Full-container'>";
    
    echo "<div class='column your-div1'>";
    echo "<h2>User Information</h2><hr>";
    echo "<div class='User-info'>";
    echo "<p><strong>Name:</strong> {$user['first_name']} {$user['last_name']}</p>";
    echo "<p><strong>Email:</strong> {$user['email']}</p>";
    echo "<p><strong>Age:</strong> {$user['age']}".str_repeat("&nbsp;", 12);
    
    if ($measurement) {
    		echo "<strong class = 'space'>Gender:</strong> {$measurement['gender']}</p>";
    		
        echo "<p><strong>Height:</strong> {$measurement['height']}".str_repeat("&nbsp;", 10);
        echo "<strong class = 'space'>Weight:</strong> {$measurement['weight']}</p>";
        
        echo "<p><strong>BMI:</strong> {$measurement['bmi']}</p>";
        echo "<p><strong>Daily Calorie intake:</strong> {$measurement['bmr']}</p>";
    } else {
        echo "No measurement found.";
    }
    echo "</div>";
    echo "<div class='button-container'>";
echo "<a href='User-Info-Form.php' class='button'>Update Measurements</a>";
echo "</div>";

    echo "</div>";
    
    echo "<div class='column your-div'>";
    echo "<h2>User Preferences</h2><hr>";
    echo "<div class='measurement-info'>";
    echo "<p><strong>Fitness Goals:</strong> {$preferences['fitness_goals']}</p>";
    echo "<p><strong>Exercise Type:</strong> {$preferences['exercise_type']}</p>";
    echo "<p><strong>Workout Frequency:</strong> {$preferences['workout_frequency']} Days</p>";
    echo "<p><strong>Current Fitness Level:</strong> {$preferences['current_fitness_level']}</p>";
    
    if ($routine_name) {
        echo "<p><strong>Workout Plan :</strong> {$routine_name}</p>";
    } else {
        echo "No routine found.";
    }
    echo "</div>";
    echo "<div class='button-container'>";
echo "<a href='Preferences.php' class='button'>Update Preferences</a>";
echo "</div>";

} else {
    echo "User ID not set in session.";
}
		
    echo "</div>";

    echo "</div>";

// Close the database connection
pg_close($conn);
?>
</body>
</html>
<?php } 
else{
header("Location: ../index.php");  // Redirect to dashboard or any other page
exit();
}
?>
