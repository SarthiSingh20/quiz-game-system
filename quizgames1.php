<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Games</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-size: cover;
            background-position: center;
            transition: background-image 0.5s ease-in-out;
        }

        header {
            background-color: #2c3e50;
            color: #ecf0f1;
            text-align: center;
            padding: 1em;
        }

        .top-left-box {
            position: absolute;
            top: 10px;
            left: 10px;
        }

        .top-right-box {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 10px;
        }

        nav a {
            text-decoration: none;
            color: #ecf0f1;
            font-weight: bold;
            transition: color 0.3s;
        }

        nav a:hover {
            color: #e74c3c;
        }

        main {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ecf0f1;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin: 10px 0;
            background-color: #fff;
            border-radius: 5px;
            overflow: hidden;
            transition: box-shadow 0.3s;
        }

        li:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        a {
            display: block;
            padding: 15px;
            text-decoration: none;
            color: #2c3e50;
            font-weight: bold;
            transition: color 0.3s;
        }

        a:hover {
            color: #e74c3c;
        }

        /* Styles for modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 999;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            position: relative;
            text-align: center;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }

        /* Styles for Sign In Form */
        .signin-form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .signin-form label {
            text-align: left;
        }

        .create-quiz-btn {
            text-decoration: none;
            color: #ecf0f1;
            font-weight: bold;
            transition: color 0.3s;
        }
    </style>
</head>
<body>
    <header>
        <h1>Quiz Games</h1>
    </header>

    <?php
    session_start();

    // Check if the user is logged in
    $logged_in = isset($_SESSION['user_id']);

    // Check if the user is logged in as an examiner
    $examiner_logged_in = isset($_SESSION['examiner_logged_in']) && $_SESSION['examiner_logged_in'];

    ?>

    <!-- Top-left box for "Create Quiz" option -->
    <div class="top-left-box">
        <?php
        if ($logged_in && $examiner_logged_in) {
            echo '<a href="create_quiz.php" class="create-quiz-btn">Create Quiz</a>';
        }
        ?>
    </div>

    <!-- Top-right box for user information or login options -->
    <div class="top-right-box">
        <?php
        if (!$logged_in) {
            echo '<nav><a href="#" onclick="openModal(\'signInModal\')">Sign In</a></nav>';
        } else {
            echo '<p>Welcome, ' . $_SESSION['user_id'] . '! <a href="logout.php">Logout</a></p>';
        }
        ?>
    </div>

    <!-- Sign In Modal -->
    <div id="signInModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('signInModal')">&times;</span>
            <h2>Sign In</h2>
            <!-- Sign In Form -->
            <form action="auth.php" method="post" class="signin-form">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <input type="submit" value="Sign In">
            </form>
        </div>
    </div>



    <!-- Main content with quiz options -->
    <main>
        <ul>
            <li>
                <a href="quiz_questions.php?quiz_id=1">General Knowledge Quiz</a>
            </li>
            <li>
                <a href="#">Science Quiz</a>
            </li>
            <li>
                <a href="#">History Quiz</a>
            </li>
            <li>
                <a href="#">Geography Quiz</a>
            </li>
            <!-- Add more quiz games as needed -->
        </ul>
    </main>

    <!-- JavaScript for modal functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {

        const quizBackgrounds = [
                'url("https://img.freepik.com/free-vector/gradient-question-mark-pattern-design_23-2149423894.jpg?w=740&t=st=1705999010~exp=1705999610~hmac=cf219d63824048fe8d6de4c20c159e4628397932d3ba2f0e03bd8e624e1eea71")',
                // Add more image paths as needed
            ];
            const randomIndex = Math.floor(Math.random() * quizBackgrounds.length);
            const randomBackground = quizBackgrounds[randomIndex];

            // Apply the background image to the body
            document.body.style.backgroundImage = randomBackground;

            // Add event listeners for the "Create Quiz" buttons
            document.querySelector('.create-quiz-btn').addEventListener('click', openCreateQuizModal);
        });

        function openCreateQuizModal() {
            openModal('createQuizModal');
        }

        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
    </script>
</body>
</html>
