<?php
// Assuming you have established a database connection
session_start();
if (isset($_SESSION['user_id'])) {
    // Fetch user ID from session
    $user_id = $_SESSION['user_id'];

    $dbName = 'wpt';  // Change this to your database name
    $username = 'postgres';
    $conn = pg_connect("dbname=$dbName user=$username");
    
    // Query to get the workout ID for the user on the current day (for example, Monday)
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
    
    $routineQuery = "SELECT workoutname from workouts WHERE workout_id = $workout_id ";

    $routineResult = pg_query($conn, $routineQuery);

    if (!$routineResult) {
        die("Query failed: " . pg_last_error());
    }

    $row = pg_fetch_assoc($routineResult);
    $workout_name = $row['workoutname'];

    $query = "SELECT E.name AS exercise_name
              FROM workoutexercises WE
              JOIN exercises E ON WE.exercise_id = E.exercise_id
              WHERE WE.workout_id = $workout_id
              ORDER BY WE.sequence";

    $result = pg_query($conn, $query);

    if (!$result) {
        die("Query failed: " . pg_last_error());
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Routine Workouts</title>
    <link rel="stylesheet" href="../css/home.css">
    
    <link rel="stylesheet" href="../css/navbar.css">
    <!-- Include jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
     
        <style>
.container {
    padding-top: 20px;
    max-width: 800px;
    margin: 0 auto;
    margin-top: 8%;
}

h2{
    text-align: center;
    margin-bottom: 20px;
    font-size: 40px;
    color: white; /* Set text color */
}

 h3 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 40px;
    color: black; /* Set text color */
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
    font-size: 24px;
}

th {
color :black;
    background-color: #f2f2f2;
}

/* Main Modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 10px;
    border: 1px solid #888;
    width: 50%;
    max-height: 80%;
    overflow-y: auto;
    color: black; /* Set text color */
}

hr {
    margin-top: 10px;
}

.left-column {
    float: left;
    width: 50%;
    padding-right: 20px;
}

.right-column {
    float: left;
    width: 50%;
    padding-left: 20px;
}

.exercise-image {
    width: 100%;
    height: auto;
}

.details-container:after {
    content: "";
    display: table;
    clear: both;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    margin-right: 15px;
    margin-top: -10px;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

#exerciseModal .modal-content {
    width: 60%;
}

#exerciseList {
    list-style-type: none;
    padding: 0;
}

#exerciseList li {
    padding-left: 30px;
    font-size: 30px;
    margin-bottom: 5px;
    cursor: pointer;
}

#exerciseInfo {
    display: flex;
    flex-direction: row;
}

#exerciseImageContainer {
    flex: 1;
    padding: 20px;
}

#exerciseDetails {
    flex: 2;
    padding: 20px;
}

#exerciseDetails p {
    margin-bottom: 10px;
    font-size: 20px;
}

#exerciseDetails h4 {
    margin-bottom: 10px;
    font-size: 30px;
}

#exerciseDetails img {
    max-width: 100%;
    height: auto;
}

    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <?php include 'main-db.php'; ?>
    <div class="container">
        <?php echo "<h2>Today's Workout - <span class='workout-link' data-workout-id='$workout_id'>" . $workout_name . "</span></h2>"; ?>

        <table>
            <tr>
                <th>No.</th>
                <th>Exercise Name</th>
            </tr>
            <?php
$i = 1;
while ($row = pg_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $i . "</td>";
    echo "<td><span class='exercise-link' data-exercise-name='" . $row['exercise_name'] . "'>" . $row['exercise_name'] . "</span></td>";

    echo "</tr>";
    $i++;
}
?>

        </table>
    </div>

 <div id="exerciseModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="exerciseDetails">
                <!-- Exercise details will be displayed here -->
            
        </div>
    </div>
</div>





<script src="../js/info.js"></script>
</body>
</html>

<?php
    // Close the connection
    
} else {
    header("Location: ../index.php");
    exit();
}
?>

