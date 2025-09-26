<?php
session_start();

if (isset($_SESSION['user_id'])) {
// Check if the form is submitted
$user_id = $_SESSION['user_id'];
$dbName = 'wpt';  // Change this to your database name
$username = 'postgres';
$conn = pg_connect(" dbname=$dbName user=$username");


// Check if user information is available in the session
$userInfo = isset($_SESSION['user_info']) ? $_SESSION['user_info'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Info Page</title>
    <link rel="stylesheet" href="../css/navbar.css"> 
    <link rel="stylesheet" href="../css/form.css"> <!-- Replace with your actual CSS filename -->
</head>
<body>
<?php include '../php/navbar.php'; ?>
    <div class="container">
        <div class="form-container">
            <div class="logo-container">
                <img src="../src/logo.png" alt="Logo">
                <h1>Workout Planner <br>& Tracker</h1>
            </div>
            
            <div class="horizontal-line"></div>
            <h2>Workout Preference</h2>
            <form id="userInfoForm" class="active-form" action="Preferences_entry.php" method="post">
                <h3>
                    <!-- Fitness Goals -->
<div class="form-group">
    <label for="fitness-goals">Fitness Goals:</label>
    <div class="custom-dropdown">
        <select id="fitness-goals" name="fitness-goals" required>
            <option value="weight-loss">Weight Loss</option>
            <option value="muscle-gain">Muscle Gain</option>
            <option value="endurance">Improved Endurance</option>
            <!-- Add more options as needed -->
        </select>
    </div>
</div>

<!-- Workout Preferences -->
<div class="form-group">
    <label for="exercise-type">Workout Preferences:</label>
    <div class="custom-dropdown">
        <select id="exercise-type" name="exercise-type" required>
            <option value="Weight Training">Weight Training </option>
            <option value="Calisthenics">Calisthenics</option>
           
            <!-- Add more options as needed -->
        </select>
    </div>
</div>


<div class="form-group">
    <label for="workout-frequency">Weekly Commitment:</label>
    <div class="custom-dropdown">
        <select id="workout-frequency" name="workout-frequency" required>
            <option value="3">3 day</option>
            <option value="4">4 days</option>
            <option value="5">5 day</option>
            <option value="6">6 days</option>
            <!-- Add more options as needed -->
        </select>
    </div>
</div>

<!-- Current Fitness Level -->
<div class="form-group">
    <label for="current-fitness-level">Current Fitness Level:</label>
    <div class="custom-dropdown">
        <select id="current-fitness-level" name="current-fitness-level" required>
            <option value="beginner">Beginner</option>
            <option value="intermediate">Intermediate</option>
            <option value="advanced">Advanced</option>
        </select>
    </div>
</div>





                    <input type="submit" name="Submit"value="Enter">
                </h3>
            </form>
        </div>
    </div>
    
    <?php
} else {
    // If not a POST request, redirect to login.php
    header("Location: ../index.php");
    exit();
}
?>

</body>
</html>

