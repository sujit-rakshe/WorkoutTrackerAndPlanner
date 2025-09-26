
<?php
session_start();
if (isset($_SESSION['user_id'])) {
header("Location: ../index.php");  // Redirect to dashboard or any other page
exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Signup Page</title>
    
    <link rel="stylesheet" href="../css/form.css">
    <link rel="stylesheet" href="../css/navbar.css"> 
    <script src="../js/form.js"></script>
    <style>
        .hide-me {
    display: none;
}
/* Add your custom CSS styles here */
    </style>
</head>
<body>
    <?php include '../php/navbar.php'; ?>

    <div class="container">
        <div class="form-container">
            <div class="logo-container">
                <img src="../src/logo.png" alt="Logo">
                <h1>Workout Planner</h1>
            </div>
            
            <div class="horizontal-line"></div>
            <?php
    // Display signup error if it exists
    if (isset($_SESSION['signup_error'])) {
        echo "<p style='color: red;font-weight: bold;'>
        {$_SESSION['signup_error']}</p>";
        unset($_SESSION['signup_error']); 
      
         // Clear the error after displaying it
    }


    // Display signup success message if it exists
    if (isset($_SESSION['signup_success'])) {
        echo "<p style='color: green;font-weight: bold;'>
        {$_SESSION['signup_success']}</p>";
        unset($_SESSION['signup_success']);  // Clear the success message after displaying it
       
    }
    ?>
            <form id="loginForm" class="active-form" action="./login.php" method="post">
                <h2>Login Form</h2>
                <h3>
                    <label for="loginEmail">Email:</label>
                    <input type="text" id="loginEmail" name="email" required>
                    <span id="loginEmailError" class="error"></span><br> <!-- Span for email error message -->

                    <label for="loginPassword">Password:</label>
                    <input type="password" id="loginPassword" name="password" required>
                    <span id="loginPasswordError" class="error"></span><br> <!-- Span for password error message -->
<?php
if (isset($_SESSION['login_error'])) {
        echo "<p style='color: red;text-align: center;'>
        {$_SESSION['login_error']}</p>";
        unset($_SESSION['login_error']); 
       
         // Clear the error after displaying it
    }
?>
                    <input type="submit" value="Login" class="next-button">
                </h3>
            </form>

            <form id="signupForm" action="./signup.php" method="post">
            
                <h2>Signup Form</h2>
                <h3>
               
                
                    <label for="signupEmail">Email:</label>
                    <input type="text" id="signupEmail" name="email" required>
                    <span id="signupEmailError" class="error"></span><br> <!-- Span for email error message -->
                    
                    <label for="signupDOB">Date of Birth:</label>
                    <input type="date" id="signupDOB" name="dob" required>
                    <span id="signupDOBError" class="error"></span><br> <!-- Span for dob error message -->
                    
                    <label for="signupPassword">Password:</label>
                    <input type="password" id="signupPassword" name="password" required>
                    
                    
                    <span id="signupPasswordError" class="error"></span><br> <!-- Span for password error message -->

                    <input type="submit" value="Signup" class="next-button">
                </h3>
            </form>

            <p id="switchMessage">Not a member yet? Join <a href="#" onclick="switchForm('signupForm')">Sign up</a></p>
        </div>
    </div>

    
</body>
</html>

