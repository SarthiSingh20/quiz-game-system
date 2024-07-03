<!DOCTYPE html>
<html lang="en">
<head>

</head>
<body>
    <?php
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo "<script>
                var confirmed = confirm('You need to log in first. Do you want to log in now?');
                if (confirmed) {
                    window.location.href = 'quizgames1.php#signInModal';
                } else {
                    window.location.href = 'quizgames1.php';
                }
             </script>";
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
            $quiz_score = $quiz_row['Score'];
            $quiz_duration = $quiz_row['Duration'];
            $quiz_marking_scheme = $quiz_row['MarkingScheme'];
            $quiz_number_of_questions = $quiz_row['NumberOfQuestions'];

            // Display quiz details
            echo "<h2>$quiz_description</h2>";
            echo "<p>Duration: $quiz_duration</p>";
            echo "<p>Marking Scheme: $quiz_marking_scheme</p>";
            echo "<p>Score: $quiz_score</p>";
            echo "<p>Number of Questions: $quiz_number_of_questions</p>";

            // Add more details as needed

            // Fetch questions for the quiz from the quizquestiontable
            $question_query = "SELECT qq.QuestionId, q.Question
                FROM quizquestiontable qq
                INNER JOIN questiontable q ON qq.QuestionId = q.Id
                WHERE qq.QuizId='$quiz_id'";
            $question_result = $conn->query($question_query);

            if ($question_result->num_rows > 0) {
                echo '<form name="quizForm" action="" method="post">';

                while ($question_row = $question_result->fetch_assoc()) {
                    $question_id = $question_row['QuestionId'];
                    $question_text = $question_row['Question'];

                    // Fetch options for the question from the questionoptiontable
                    $option_query = "SELECT * FROM questionoptiontable WHERE QuestionId='$question_id'";
                    $option_result = $conn->query($option_query);

                    if ($option_result->num_rows > 0) {
                        echo "<p>$question_text</p>";

                        while ($option_row = $option_result->fetch_assoc()) {
                            $option_text = $option_row['OptionId'];
                            $is_correct = $option_row['Correct'];

                            // Display options as radio buttons without bold formatting
                            echo "<input type='radio' name='question_$question_id' value='$option_text'> $option_text<br>";

                            // If you need to check correctness, store correct option texts in an array
                            if ($is_correct == 1) {
                                $correct_option_texts[$question_id] = $option_text;
                            }
                        }
                    }
                }

                // Provide a button to submit the quiz and check the answers
                echo "<button onclick='submitQuiz($quiz_id)'>Submit Quiz</button>";

                echo '</form>';
            } else {
                // Handle if no questions are found for the quiz
                echo "No questions found for the quiz.";
            }
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

    
    <script>
        // Your existing script content here

        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function submitQuiz(quizId) {
            document.forms['quizForm'].action = `quiz_results.php?quiz_id=${quizId}`;
            document.forms['quizForm'].submit();
        }

        document.addEventListener('DOMContentLoaded', function () {
            const quizTimeLimit = 60* 60 * 1000; // Convert minutes to milliseconds
            setTimeout(function() {
                alert("Time's up! Quiz will now be submitted.");
                submitQuiz(<?php echo $quiz_id; ?>);
            }, quizTimeLimit);
        });

    </script>
</body>
</html>
