<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quizgameslogin";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the form submission for creating quizzes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_quiz'])) {
    $quizId = $_POST['quiz_id'];
    $quizDescription = $_POST['quiz_description'];
    $score = $_POST['score'];
    $duration = $_POST['duration'];
    $markingScheme = $_POST['marking_scheme'];
    $numberOfQuestions = $_POST['number_of_questions'];

    // Insert the data into the 'quiz' table
    $sql = "INSERT INTO quiz (Id, Description, Score, Duration, MarkingScheme, NumberOfQuestions)
            VALUES ('$quizId', '$quizDescription', '$score', '$duration', '$markingScheme', '$numberOfQuestions')";

    if ($conn->query($sql) === TRUE) {
        echo "Quiz created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle the form submission for adding quiz questions
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_question'])) {
    $quizId = $_POST['quiz_id'];  // Added line to capture quiz ID
    $level = $_POST['level'];
    $marks = $_POST['marks'];
    $time = $_POST['time'];
    $questionText = $_POST['question_text'];

    // Insert the question data into the 'questiontable' table
    $sql = "INSERT INTO questiontable (Level, Marks, Time, Question)
            VALUES ('$level', '$marks', '$time', '$questionText')";

    if ($conn->query($sql) === TRUE) {
        $questionId = $conn->insert_id;  // Get the ID of the inserted question

        // Insert the relationship data into the 'quizquestiontable' table
        $sql = "INSERT INTO quizquestiontable (QuizId, QuestionId)
                VALUES ('$quizId', '$questionId')";

        if ($conn->query($sql) !== TRUE) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Handle options and correct option
        for ($i = 1; $i <= 4; $i++) {
            $optionText = $_POST["option_text_$i"];
            $isCorrect = ($i == $_POST['correct_option']) ? 1 : 0;

            // Insert the option data into the 'question_option' table
            $sql = "INSERT INTO questionoptiontable (QuestionId, OptionId, Correct)
                    VALUES ('$questionId', '$optionText', '$isCorrect')";

            if ($conn->query($sql) !== TRUE) {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            // If the option is correct, store it in the 'quizanswertable' table
            if ($isCorrect) {
                $sql = "INSERT INTO quizanswertable (QuizId, QuestionId, Id)
                        VALUES ('$quizId', '$questionId', '$conn->insert_id')";

                if ($conn->query($sql) !== TRUE) {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }

        echo "Question added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz and Add Questions</title>
    <!-- Add your styles or link to external stylesheets here -->
    <style>
        .hidden-form {
            display: none;
        }
    </style>
</head>
<body>
    <h2>Create Quiz</h2>
    <form id="createQuizForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="quiz_id">Quiz ID:</label>
        <input type="text" id="quiz_id" name="quiz_id" required><br>

        <label for="quiz_description">Quiz Description:</label>
        <textarea id="quiz_description" name="quiz_description" rows="4" required></textarea><br>

        <label for="score">Score:</label>
        <input type="text" id="score" name="score" required><br>

        <label for="duration">Duration:</label>
        <input type="text" id="duration" name="duration" required><br>

        <label for="marking_scheme">Marking Scheme:</label>
        <input type="text" id="marking_scheme" name="marking_scheme" required><br>

        <label for="number_of_questions">Number of Questions:</label>
        <input type="text" id="number_of_questions" name="number_of_questions" required><br>

        <input type="submit" name="create_quiz" value="Create Quiz">
    </form>

    <h2>Add Quiz Questions</h2>
    <form id="addQuestionForm" class="hidden-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="quiz_id">Quiz ID:</label>
        <input type="text" id="quiz_id" name="quiz_id" required><br>

        <label for="level">Level:</label>
        <input type="text" id="level" name="level" required><br>

        <label for="marks">Marks:</label>
        <input type="text" id="marks" name="marks" required><br>

        <label for="time">Time:</label>
        <input type="text" id="time" name="time" required><br>

        <label for="question_text">Question Text:</label>
        <textarea id="question_text" name="question_text" rows="4" required></textarea><br>

        <!-- Options and correct options -->
        <label for="option_text_1">Option 1:</label>
        <input type="text" id="option_text_1" name="option_text_1" required>
        <input type="radio" name="correct_option" value="1" required> Correct<br>

        <label for="option_text_2">Option 2:</label>
        <input type="text" id="option_text_2" name="option_text_2" required>
        <input type="radio" name="correct_option" value="2"> Correct<br>

        <label for="option_text_3">Option 3:</label>
        <input type="text" id="option_text_3" name="option_text_3" required>
        <input type="radio" name="correct_option" value="3"> Correct<br>

        <label for="option_text_4">Option 4:</label>
        <input type="text" id="option_text_4" name="option_text_4" required>
        <input type="radio" name="correct_option" value="4"> Correct<br>

        <input type="submit" name="add_question" value="Add Question">
    </form>

    <button onclick="toggleForms()">Toggle Forms</button>

    <script>
        function toggleForms() {
            var createQuizForm = document.getElementById("createQuizForm");
            var addQuestionForm = document.getElementById("addQuestionForm");

            if (createQuizForm.style.display === "none") {
                createQuizForm.style.display = "block";
                addQuestionForm.style.display = "none";
            } else {
                createQuizForm.style.display = "none";
                addQuestionForm.style.display = "block";
            }
        }
    </script>
</body>
</html>
