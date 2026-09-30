<?php
session_start();
session_unset(); // Delete all session variables 
session_destroy(); // Delete full Session

header("Location: index.php ? msg=logout_success"); // Back to login page
exit();
?>