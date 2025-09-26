<?php
session_start();
if (isset($_SESSION['user_id'])) {
// Check if the form is submitted
$user_id = $_SESSION['user_id'];
$dbName = 'wpt';  // Change this to your database name
$username = 'postgres';
$conn = pg_connect(" dbname=$dbName user=$username");
$query = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = pg_query($conn, $query);
if (!$result) {
        die("Query failed: " . pg_last_error());
    }

$user = pg_fetch_assoc($result);
if($user){
if(!isset($user['first_name'])){
$fname = $user['first_name'];
}
if(!isset($user['last_name'])){
$lname = $user['last_name'];
}
$email = $user['email'];
$age = $user['age'];
}// Check if user information is available in the session
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
            <h2>User Information</h2>
            <form id="userInfoForm" class="active-form" action="Info_entry.php" method="post">
                <h3>
                    <label for="name">Name:</label>
<input type="text" id="name" name="name" required><br>

                    <label for="lastname">Lastname:</label>
                    <input type="text" id="lastname" name="lastname" required><br>
                    <label for="Email">Email:</label>
                    <input type="text" id="signupEmail" name="email" value="<?php echo $email; ?>"readonly>
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age" value="<?php echo $age; ?>"readonly>
                    <label for="gender">Gender:</label>
                    <div class="custom-dropdown">
    <select id="gender" name="gender" required>
        <option value="male">Male</option>
        <option value="female">Female</option>
        
    </select>
</div>

                    <label for="weight">Weight:</label>
                    <input type="number" id="weight" name="weight" required><br>
                    <label for="height">Height:</label>
                    <input type="number" id="height" name="height" required><br>
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

