<?php //reset_password.php
require_once('db_connect.php');
require_once('output_f.php');
require_once('user_auth_functions.php');
if(isset($_POST['email'])){
    $email = $_POST['email'];
    html_header('Reset Password');
    try {
        $password = reset_password($email);
        notify_password($email, $password);
        echo '<p style="text-align: center">Your new password has been emailed to you.</p>';
    } catch (Exception $e) {
        echo 'Password reset failed. Please try again!';
    }
    echo '<br><p style="text-align: center"><a href="login.php">Go to Login page</a></p><br>';
} else header("Location:login.php");
html_footer();
?>