<!DOCTYPE html>
<html>
<head>
    <title>User  Progress</title>
    <!-- Include Chart.js -->
    <link rel="stylesheet" href="../css/navbar.css"> 
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    body {
            margin: 0;
            padding: 0;
            align-items:Center;
            background-image: url('../src/bg.jpg'); /* Replace 'bg.jpg' with your actual background image */
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position:center;
            background-size: cover;
            font-family: 'Arial', sans-serif;
            color: #ffffff; /* Text color */
        }
    .container {
            
            
            text-align: center; /* Center align contents inside the container */
        }
        .chart-container {
            width: 48%; /* Adjusted width to allow for margins */
            float: left;
            margin: 5% 1% ; /* Space between charts */
            background-color: rgba(0, 0, 0, 0.6); /* Dark background color */
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.4); /* Optional: Add a shadow effect */
            box-sizing: border-box; /* Include padding and border in element's total width and height */
        }

        .chart-title {
            color: #fff; /* Text color for chart title */
            margin-bottom: 10px;
        }

        /* Clear the float after the last chart to prevent layout issues */
        .clear {
            clear: both;
        }
    </style>
</head>
<body>

<?php include '../php/navbar.php';?>
<div class="container">
    <div class="chart-container">
        <div class="chart-title">BMI(Body Mass Index) Chart</div>
        <canvas id="bmiChart"></canvas>
    </div>

    <div class="chart-container">
        <div class="chart-title">Weight Chart</div>
        <canvas id="weightChart"></canvas>
    </div>
<div>
    <!-- PHP Code to Get Data -->
    <?php
    session_start();
if (isset($_SESSION['user_id'])) {
    // Fetch user ID from session
    $user_id = $_SESSION['user_id'];
    // Connect to your database and fetch data
    $dbname = 'wpt'; // Change this to your database name
$username = 'postgres'; // Change this to your database username

$conn = pg_connect(" dbname=$dbname user=$username");
 // Your user ID

    // Fetch BMI data
    $queryBMI = "SELECT created_at, bmi FROM user_measurements WHERE user_id = $user_id ORDER BY created_at ASC";
    $resultBMI = pg_query($conn, $queryBMI);

    $datesBMI = array();
    $bmiData = array();
    while ($row = pg_fetch_assoc($resultBMI)) {
        $datesBMI[] = date('d/m/Y', strtotime($row['created_at']));
        //$datesBMI[] = $row['created_at'];
        $bmiData[] = $row['bmi'];
    }

    // Fetch weight data
    $queryWeight = "SELECT created_at, weight FROM user_measurements WHERE user_id = $user_id ORDER BY created_at ASC";
    $resultWeight = pg_query($conn, $queryWeight);

    $datesWeight = array();
    $weightData = array();
    while ($row = pg_fetch_assoc($resultWeight)) {
        $datesWeight[] = date('d/m/Y', strtotime($row['created_at']));
        //$datesWeight[] = $row['created_at'];
        $weightData[] = $row['weight'];
    }
    ?>

    <!-- JavaScript to Render Charts -->
    <script>
        // BMI Chart
        var ctxBMI = document.getElementById('bmiChart').getContext('2d');
        var bmiData = <?php echo json_encode($bmiData); ?>;
        var datesBMI = <?php echo json_encode($datesBMI); ?>;

        var bmiChart = new Chart(ctxBMI, {
            type: 'line',
            data: {
                labels: datesBMI,
                datasets: [{
                    label: 'BMI',
                    data: bmiData,
                    borderColor: 'blue',
                    fill: false
                }]
            },
            options: {
                scales: {
                    xAxes: [{
                        type: 'time',
                        time: {
                            unit: 'day'
                        }
                    }],
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'BMI'
                        }
                    }]
                }
            }
        });

        // Weight Chart
        var ctxWeight = document.getElementById('weightChart').getContext('2d');
        var weightData = <?php echo json_encode($weightData); ?>;
        var datesWeight = <?php echo json_encode($datesWeight); ?>;

        var weightChart = new Chart(ctxWeight, {
            type: 'line',
            data: {
                labels: datesWeight,
                datasets: [{
                    label: 'Weight',
                    data: weightData,
                    borderColor: 'green',
                    fill: false
                }]
            },
            options: {
                scales: {
                    xAxes: [{
                        type: 'time',
                        time: {
                            unit: 'day'
                        }
                    }],
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Weight'
                        }
                    }]
                }
            }
        });
    </script>
    <div class="clear"></div>
    <?php
    // Close the connection
    pg_close($conn);
} else {
    header("Location: ../index.php");
    exit();
}
?>

</body>
</html>

