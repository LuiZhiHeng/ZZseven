<?php //change_password.php
    session_start();
    require_once('db_connect.php');
    $conn = db_connect();
    if(!isset($_SESSION['user'])) header('Location:login.php');
    if (isset($_POST['old_password']) && isset($_POST['new_password']) && isset($_POST['new_password2'])) {
        $oldpassword = $_POST['old_password'];
        $newpassword = $_POST['new_password'];
        $newpassword2 = $_POST['new_password2'];
        $user = $_SESSION['user'];
    
        $query="SELECT password FROM user WHERE password='$oldpassword' AND name='$user'";
        $result=$conn->query($query);
    
        if($_POST['new_password']==$_POST['new_password2']){
            $password = sha1($newpassword);
            $query="UPDATE user SET password='$password' WHERE name='$user'";
            $result=$conn->query($query);
            if(!$result) throw new Exception("Password failed to reset. Password entered does not match.");  
            else echo "<script>alert('Password changed successfully!'); window.location = 'login.php';</script>";
        }
    }
    require_once('output_f.php');    
    html_header('Change Password');
    $un = $_SESSION['user'];
    $rs = $conn->query("SELECT type FROM user WHERE name = '$un'");
    $rowtem = $rs->fetch_array(MYSQLI_NUM);
    ($rowtem[0] == 2)? menu3(): (($rowtem[0] == 2)? menu2(): menu());
    show_login_user($_SESSION['user']);
?>
    <form id="form" method="post" action="change_password.php">
        <div class="input-group">
            <label>Old Password</label>
            <input type="password" name="old_password" required/>
        </div>
        <div class="input-group">
            <label>New Password</label>
            <input type="password" name="new_password" required/>
        </div>
        <div class="input-group">
            <label>Confirm New Password</label>
            <input type="password" name="new_password2" required/>
        </div>
        <div class="input-group">
            <button type="submit" class="btn">Confirm</button>
        </div>
    </form>
<?php
    html_footer();
?>