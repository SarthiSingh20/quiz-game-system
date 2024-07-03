<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include your head content here -->
    <meta http-equiv="refresh" content="10;url=quizgames1.php">
</head>
<body>
    <?php
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: quizgames1.php#signInModal');
        exit;
    }

    // Check if the quiz ID is set in the URL
    if (isset($_GET['quiz_id'])) {
        $quiz_id = $_GET['quiz_id'];

        // Database connection details
        $db_host = 'localhost';
        $db_username = 'root';
        $db_password = '';
        $db_name = 'quizgameslogin';

        $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch quiz details from the quiz table
        $quiz_query = "SELECT * FROM quiz WHERE Id='$quiz_id'";
        $quiz_result = $conn->query($quiz_query);

        if ($quiz_result->num_rows > 0) {
            $quiz_row = $quiz_result->fetch_assoc();
            $quiz_description = $quiz_row['Description'];
            $quiz_marking_scheme = $quiz_row['MarkingScheme'];
            $quiz_number_of_questions = $quiz_row['NumberOfQuestions'];

            
            // Calculate and display total score
            $total_score = 0;

            foreach ($_POST as $key => $value) {
                if (strpos($key, 'question_') === 0) {
                    $question_id = substr($key, strlen('question_'));
                    $answer_query = "SELECT * FROM questionoptiontable WHERE OptionId='$value' AND QuestionId='$question_id' AND Correct='1'";
                    $answer_result = $conn->query($answer_query);

                    if ($answer_result->num_rows > 0) {
                        $total_score += $quiz_marking_scheme; // Add marks for correct answer
                    }
                }
            }

            echo "<h2>Quiz Results</h2>";
            echo "<p>Total Score: $total_score</p>";

            // Provide additional details as needed
        } else {
            // Handle if no quiz details are found
            echo "No quiz details found.";
        }

        $conn->close();
    } else {
        // Handle if the quiz ID is not set in the URL
        echo "Quiz ID not specified.";
    }
    ?>

    <!-- Add a button to go back to the main menu -->
    <button onclick="window.location.href='quizgames1.php'">Back to Main Menu</button>

    <!-- Add the existing modal and other content here -->

    <script>
        // Your existing script content here

        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
    </script>
</body>
</html>
