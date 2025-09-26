<?php
// Assuming you have established a database connection
session_start();
if (isset($_SESSION['user_id'])) {
    // Fetch user ID from session
    $user_id = $_SESSION['user_id'];

    $dbName = 'wpt';  // Change this to your database name
    $username = 'postgres';
    $conn = pg_connect("dbname=$dbName user=$username");

$query = "SELECT * FROM user_preference WHERE user_id = $user_id";
    $result = pg_query($conn, $query);

    if (!$result) {
        die("Error in SQL query: " . pg_last_error());
    }

    // Fetch user preferences
    $preferences = pg_fetch_assoc($result);
    
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

    // Query to get user's routine workouts
    $query = "SELECT W.workout_id, W.workoutname AS \"Workout Name\", RW.day_of_week
              FROM routineworkouts RW
              JOIN workouts W ON RW.workout_id = W.workout_id
              WHERE RW.routine_id = (
                  SELECT routine_id
                  FROM user_routine
                  WHERE user_id = $user_id
              )
              ORDER BY RW.sequence";

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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        .container {
            padding: 30px;
            max-width: 1000px;
            margin: 0 auto;
            margin-top:8%;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 40px;
        }
h3 {
color:black;
            text-align: center;
            margin-bottom: 20px;
            font-size: 40px;
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
            color: black;
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
    background-color: rgba(0,0,0,0.4);
}

.modal-content {
color: black;
    background-color: #fefefe;
    margin: 5% auto; /* Center the modal vertically and horizontally */
    padding: 10px;
    border: 1px solid #888;
    width: 50%;
    max-height: 80%; /* Limit the maximum height */
    overflow-y: auto; /* Enable vertical scrolling */
}
hr {
margin-top:10px;
}
/* Add these styles to your CSS */
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

/* Additional style for exercise modal */
#exerciseModal .modal-content {
    width: 60%;
}

        #exerciseList {
            list-style-type: none;
            padding: 0;
        }

        
              #exerciseList li {
            color: black;
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
        }
        #exerciseDetails h4 {
            margin-bottom: 10px;
            font-size:30px;
        }

        #exerciseDetails img {
            max-width: 100%;
            height: auto;
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
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class='Full-container'>
    <?php
    echo "<div class='column your-div'>";
    echo "<h2><strong>User Preferences</strong></h2><hr>";
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
echo "</div>";?>
    <div class='column'>
    
        <h2><?php if ($routine_name) {
        echo "<strong>Workout Plan :</strong> {$routine_name}";
    } else {
        echo "User Routine Workouts";
    }?></h2>
        <table>
            <tr>
                <th>Day of Week</th>
                <th>Workout Name</th>
            </tr>
            <?php
            while ($row = pg_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['day_of_week'] . "</td>";
                echo "<td><span class='workout-link' data-workout-id='{$row['workout_id']}'>" . $row['Workout Name'] . "</span></td>";
                echo "</tr>";
            }
            ?>
        </table>
    
    </div>
<!-- Main Modal for Workout List -->
<div id="myModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3 id="workoutTitle"></h3>
        <hr> 
        <div id="exerciseListContainer">
            <ol id="exerciseList">
                <!-- Exercises will be added here dynamically -->
            </ol>
        </div>
    </div>
</div>

<!-- Modal for Exercise Details -->
<div id="exerciseModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="exerciseDetails">
            <!-- Exercise details will be added here dynamically -->
        </div>
    </div>
</div>



<script src="../js/excrices.js"></script>
</body>
</html>

<?php
    // Close the connection
    pg_close($conn);
} else {
    header("Location: ../index.php");
    exit();
}
?>

