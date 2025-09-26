<div class="navbar">
    <b><a href="#" >Workout Planner </a></b>
    <span class="hide-me" style="margin-left: auto;">
        <?php
$current_page = basename($_SERVER['PHP_SELF']);
$home_link = ($current_page !== 'index.php') ? '../index.php' : '#';
$logout_link = ($current_page !== 'index.php') ? 'logout.php' : 'php/logout.php';
$login_link = ($current_page !== 'index.php') ? 'login.php' : 'php/login.php';
$info_link = ($current_page !== 'User-Info.php') ? (($current_page !== 'index.php') ? 'User-Info.php' : 'php/User-Info.php') : '#';
$workout_link = ($current_page !== 'User_Workout.php') ? (($current_page !== 'index.php') ? 'User_Workout.php' : 'php/User_Workout.php') : '#';
$progress_link = ($current_page !== 'Progress.php') ? (($current_page !== 'index.php') ? 'Progress.php' : 'php/Progress.php') : '#';
?>

<a href="<?php echo $home_link; ?>">Home</a> |
        <a href="<?php echo $workout_link; ?>" >Workout Routine</a> |
        <a href="<?php echo $progress_link; ?>" >Progress</a> |
        <a href="<?php echo $info_link; ?>" >User info</a> 
        
        <?php
        

        if (isset($_SESSION['user_id'])) {
            // User is logged in
            echo "| <a href= $logout_link >Logout</a>";
        } else {
            // User is not logged in
            echo "| <a href= $login_link >Login</a>";
        }
        ?>
    </span>
</div>
