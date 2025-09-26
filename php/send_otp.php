
<?php

// Important Files
require_once "phpmailer/src/Exception.php";
require_once "phpmailer/src/PHPMailer.php";
require_once "phpmailer/src/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the email address from the form
    $email = $_POST['email'];

    $dbName = 'wpt';  // Change this to your database name
    $username = 'postgres';
    $conn = pg_connect("dbname=$dbName user=$username");

    // Check if connection is successful
    if (!$conn) {
        // If connection fails, send error response
        $response = array(
            'status' => 'error',
            'message' => 'Failed to connect to the database.',
        );
        echo json_encode($response);
        exit; // Stop further execution
    }

    // Query to check if the email exists in the database
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = pg_query($conn, $query);

    // Check if any rows are returned
    if (pg_num_rows($result) > 0) {
        // If email exists, generate and send OTP
        $otp = mt_rand(100000, 999999); // Generate a random 6-digit OTP

        function sendMail($email, $otp) {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "tls";
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 587;
            $mail->Username = "sujitrakshe45@gmail.com"; // Corrected Gmail address
            $mail->Password = "irfghbvuntimgksp"; // Use the app password you generated

            // Sender information
            $mail->setFrom("donotraply.workoutplanner@gmail.com", "Workout Planner");
            $mail->addAddress($email);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = "Action Required: Password Update";
            $mail->Body = "
                <html>
                <head>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-image: url('https://drive.google.com/uc?id=1gdYcIWqa0Zoe4Gg29ZqfFkD3gBtxzr6y');
                            background-size: cover;
                            background-repeat: no-repeat;
                            padding: 20px;
                        }
                        .content {
                            background-color: rgba(255, 255, 255, 0.8);
                            padding: 20px;
                            border-radius: 10px;
                        }
                    </style>
                </head>
                <body>
                    <div class='content'>
                        <p><strong>Dear valued user,</strong></p>
                        <p>We hope this message finds you well.</p>
                        <p>Our records indicate that you recently requested to update your password. To proceed with this change, please use the following One-Time Password (OTP): <strong>{$otp}</strong>.</p>
                        <p>If you did not initiate this request or need further assistance, please do not hesitate to contact our support team immediately.</p>
                        <br>
                        <p><em>Thank you for choosing us as your service provider.</em></p>
                        <p>Sincerely,</p>
                        <p>Workout Planner</p>
                    </div>
                </body>
                </html>
            ";
            $mail->AltBody = "Dear valued user, \n\nOur records indicate that you recently requested to update your password. To proceed with this change, please use the following One-Time Password (OTP): {$otp}. \n\nIf you did not initiate this request or need further assistance, please do not hesitate to contact our support team immediately. \n\nThank you for choosing us as your service provider. \n\nSincerely, \nYour Company Name";

            $mail->send();
        }

        sendMail($email, $otp);

        // Placeholder response for demonstration
        $response = array(
            'status' => 'success',
            'message' => 'OTP sent to your email address.',
            'otp' => $otp // Send OTP in the response (for demonstration only)
        );
        echo json_encode($response);
    } else {
        // If email does not exist, send error response
        $response = array(
            'status' => 'error',
            'message' => 'Email address not found.',
        );
        echo json_encode($response);
    }

    // Close the database connection
    pg_close($conn);
} else {
    // If request method is not POST
    // Send error response
    $response = array(
        'status' => 'error',
        'message' => 'Invalid request method.',
    );
    echo json_encode($response);
}

?>


