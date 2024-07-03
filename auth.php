<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quizgameslogin";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputUsername = $_POST["username"];
    $inputPassword = $_POST["password"];

    // Perform authentication with prepared statement to prevent SQL injection
    $stmt = $conn->prepare("
        SELECT user_id FROM useridpassword WHERE user_id = ? AND password = ?
        UNION
        SELECT examiner_id FROM examineridpassword WHERE examiner_id = ? AND password = ?
    ");
    $stmt->bind_param("ssss", $inputUsername, $inputPassword, $inputUsername, $inputPassword);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['user_id'] = $inputUsername;
        
        // Check if the user is logged in with examineridpassword credentials
        $examineridpassword_query = "SELECT * FROM examineridpassword WHERE examiner_id = '$inputUsername'";
        $examineridpassword_result = $conn->query($examineridpassword_query);
        if ($examineridpassword_result->num_rows > 0) {
            $_SESSION['examiner_logged_in'] = true;
        }

        header('Location: quizgames1.php?login=success'); // Redirect to the main quiz page
        exit();
    } else {
        echo "<script>alert('Username or password incorrect');</script>";
    }

    $stmt->close();
}

// Close connection
$conn->close();
?>
