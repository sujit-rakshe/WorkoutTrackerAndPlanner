<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <title>Forgot Password</title>
    <style>
        .container {
            padding: 30px;
            max-width: 1000px;
            margin: 20px auto;
        }

        form h1 {
            text-align: center;
            font-weight: bold;
            font-size: 2rem;
        }

        form {
            text-align: center;
        }

        form label {
            display: block;
            margin-bottom: 10px;
        }

        form input {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        form .next-button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        form .next-button:hover {
            background-color: #2980b9;
        }

        .error-message {
            color: red;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <?php include '../php/navbar.php'; ?>
    <div class="container">
        <h1 style="text-align: Center;">Forgot Password</h1>
        <form id="forgotpassword" action="#" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Email ID"><br>
            <input type="button" class="next-button" id="sendOtpBtn" value="Send OTP">
            
                <label for="otp">Enter OTP:</label>
                <input type="text" id="otp" name="otp" placeholder="Enter OTP"><br>
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" placeholder="New Password"><br>
                <input type="button" class="next-button" id="updatePasswordBtn" value="Update Password">
            </div>
        </form>
        <div id="errorMessage" class="error-message" style="display: none;"></div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
       $(document).ready(function() {
    var sentOTP = "";

    function validatePassword(password) {
    // Regular expressions for each validation rule
    var lowercaseRegex = /[a-z]/;
    var uppercaseRegex = /[A-Z]/;
    var digitRegex = /\d+/;
    var specialCharRegex = /[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]/;
    
    // Check if password meets all criteria
    if (password.length <= 8 || password.length >= 20) {
        return "Password length must be atleast 8 characters.Passowrd Contains atleast one uppercase character, atlease one lowercase character, atleast one digit and atleast one special symbol.";
    }
    if (!lowercaseRegex.test(password)) {
        return "Password length must contain atleast 8 characters.Passowrd Contains atleast one uppercase character, atlease one lowercase character, atleast one digit and atleast one special symbol.";
    }
    if (!uppercaseRegex.test(password)) {
        return "Password length must contain atleast 8 characters.Passowrd Contains atleast one uppercase character, atlease one lowercase character, atleast one digit and atleast one special symbol.";
    }
    if (!digitRegex.test(password)) { 
        return "Password length must contain atleast 8 characters.Passowrd Contains atleast one uppercase character, atlease one lowercase character, atleast one digit and atleast one special symbol.";
    }
    if (!specialCharRegex.test(password)) {
        return "Password length must contain atleast 8 characters.Passowrd Contains atleast one uppercase character, atlease one lowercase character, atleast one digit and atleast one special symbol.";
    }
    
    // Password meets all criteria
    return "Password is valid.";
}


    // Send OTP button click event
    $("#sendOtpBtn").click(function() {
        // Get form data
        var formData = $("#forgotpassword").serialize();
        // Send AJAX request to send_otp.php
        $.ajax({
            url: "send_otp.php",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                // Display success message
                alert(response.message);
                // Store sent OTP
                sentOTP = response.otp;
                $("#forgotpassword").data("sentOTP", response.otp);
            },
            error: function(xhr, status, error) {
                // Display error message
                alert("An error occurred while sending OTP: " + xhr.responseText);
            }
        });
    });

    // Verify OTP button click event
    $("#updatePasswordBtn").click(function() {
        var enteredOTP = $("#otp").val().trim(); // Trim whitespace
        var sentOTP = $("#forgotpassword").data("sentOTP");
        console.log("Entered OTP: " + enteredOTP); // Debugging
        console.log("Sent OTP: " + sentOTP); // Debugging

        // Compare entered OTP with sent OTP
        if (enteredOTP === sentOTP) {
            var password = $("#password").val().trim(); // Retrieve password from the input field
            var passwordValidationResult = validatePassword(password); // Pass the password to the validation function

            if (passwordValidationResult === "Password is valid.") {
                // Get form data
                var formData = $("#forgotpassword").serialize();
                // Send AJAX request to update_password.php
                $.ajax({
                    url: "update_password.php",
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        // Display success message
                        alert(response.message);
                    },
                    error: function(xhr, status, error) {
                        // Display error message
                        alert("An error occurred while updating password: " + xhr.responseText);
                    }
                });
            } else {
                alert(passwordValidationResult);
            }
        } else {
            alert("OTP does not match. Please enter the correct OTP.");
        }
    });

});

    </script>
</body>
</html>

