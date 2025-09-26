<?php
// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the email address from the form
    $email = $_POST['email'];
    // Get the new password from the form
    $newPassword = $_POST['password'];

    // Establish database connection
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

    // Query to update the user's password
    $query = "UPDATE users SET password='$newPassword' WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    if ($result) {
        // If password update is successful, send success response
        $response = array(
            'status' => 'success',
            'message' => 'Password updated successfully.',
        );
        echo json_encode($response);
    } else {
        // If password update fails, send error response
        $response = array(
            'status' => 'error',
            'message' => 'Failed to update password. Please try again later.',
        );
        echo json_encode($response);
    }

    // Close the database connection
    mysqli_close($conn);
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
