<?php
session_start();
session_destroy();
header("Location: quizgames1.php");
exit;
?>
