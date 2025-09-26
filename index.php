<?php
session_start();


 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Routine Workouts</title>
    <link rel="stylesheet" href="css/home.css">
    
    <link rel="stylesheet" href="css/navbar.css">
    <!-- Include jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
     
        <style>
.container {
    padding: 30px;
    max-width: 1000px;
    
    margin-top: 8%;
    margin: 20 auto;
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

.Full-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            margin-top: 30px;
        }
        .column {
        
            width: 45%;
            
            
           
            margin: 50px auto;
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
   
   
   
    .your-div {
    width: 45%;
    /* Set height as needed */
    background-image: url('src/baki.png');
    background-size: cover;
    background-position: center;
    opacity: 0.9; /* Set the opacity level (0.0 to 1.0) */
    color: white;/* White with 50% opacity */
} /* White with 50% opacity */

.your-div1 {
    
    /* Set height as needed */
    background-image: url('src/rest.jpg');
    background-size: cover;
    background-position: center;
    opacity: 0.9; /* Set the opacity level (0.0 to 1.0) */
    color: white;/* White with 50% opacity */
} /* White with 50% opacity */

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
        
        
        
    </style>
</head>
<body>
<?php 


// Your code in navbar.php
include 'php/navbar.php';
// End output buffering and discard buffer contents

?>
<?php

// Assuming you have established a database connection

if (isset($_SESSION['user_id'])) {
  include 'php/main-db.php';
  echo "<div class='Full-container'>";
    
    echo "<div class='column'>";
?>

        <?php echo "<h2>Today's Workout - <span class='workout-link' data-workout-id='$workout_id'>"  . $workout_name . "</span></h2>"; 
if($workout_id != 11) {?>
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

<?php }
else {
	$msg = "Rest days are vital for fitness. They aid in muscle recovery, lower injury chances, and prevent exhaustion. Consider it a battery recharge for your body, giving muscles time to repair and grow. On rest days, light activities like walking or stretching are beneficial. Stay hydrated, eat nutritiously, and prioritize sleep. These days enhance performance and well-being.";
	echo "<div style='background-color: rgba(0, 0, 0, 0.7); color: white; padding: 20px; border: 1px solid #ccc; border-radius: 10px; font-size:30px;'>";
	echo "". str_repeat("&nbsp;", 8).$msg;
	echo "</div>";
}
echo "<div class='button-container'>";
echo "<a href='./php/User_Workout.php' class='button'>See Full Routine</a>";
echo "</div>";
?>
    </div>

<?php
echo "<div class='column your-div'>";
    echo "<h2>User Information</h2><hr>";
    echo "<div class='User-info '>";
    echo "<p><strong>Name:</strong> {$user['first_name']} {$user['last_name']}</p>";
    echo "<p><strong>Email:</strong> {$user['email']}</p>";
    echo "<p><strong>Age:</strong> {$user['age']}". str_repeat("&nbsp;", 8);
    
    if ($measurement) {
    		echo "<strong class = 'space'>Gender:</strong> {$measurement['gender']}</p>";
    		
        echo "<p><strong>Height:</strong> {$measurement['height']}". str_repeat("&nbsp;", 8);
        echo "<strong class = 'space'>Weight:</strong> {$measurement['weight']}</p>";
        
        echo "<p><strong>BMI:</strong> {$measurement['bmi']}</p>";
        echo "<p><strong>Daily Calorie intake:</strong> {$measurement['bmr']}</p>";
    } else {
        echo "No measurement found.";
    }
    echo "</div>";
    echo "<div class='button-container'>";
echo "<a href='./php/User-Info-Form.php' class='button'>Update Measurements</a>";
echo "</div>";

    echo "</div>";
    ?>


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






<?php } else {
                ?>
                <div class="container">
        <h1 style="text-align: Center;">Welcome to<br> Workout Planner</h1>
        <a href="./php/form.php">Let's Go Super Sayian !!!</a>
    </div>
          <?php  }
          
            ?>
    

<script src="js/info.js"></script>
</body>
</html>



