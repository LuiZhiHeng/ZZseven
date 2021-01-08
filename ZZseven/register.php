<?php //register.php
    require_once('output_f.php');
    html_header('Register');
    menu0();
    require_once('user_auth_functions.php');
    require_once('db_connect.php');
    if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['type']) && isset($_POST['password'])){
        $name = addslashes($_POST['name']);
        $email = $_POST['email'];
        $type = $_POST['type'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        try {
            if (!filled_out($_POST)) throw new Exception("Please fill in all required field!");
            if(!valid_email($email)) throw new Exception("Invalid email. Please try again!");
            if($password != $password2) throw new Exception("Password not match. Please try again!");
            if((strlen($password)<6) || (strlen($password)>16)) throw new Exception("Invalid password length. (6-16 characters)");
            if($type != 0 && $type != 2) throw new Exception("Please choose the user type!");
            register($name, $password, $email, $type);      
            echo "<script>alert('Your registration was successful'); window.location = 'login.php';</script>";
        } catch(Exception $e ){
            echo '<script>alert("'.$e->getMessage().'");</script>';
        }
    }
?>
    <form id="form" method="post" action="register.php">
        <div class="input-group">
            <input type="text" name="name" placeholder="Username"/>
        </div>
        <div class="input-group">
            <input type="email" name="email" placeholder="Email"/>
        </div>
        <div class="input-group">
            <select name="type" id="type">
                <option value="" hidden>-- User Type --</option>
                <option value="0">Customer</option>
                <option value="1">Seller</option>
            </select>
        </div>
        <div class="input-group">
            <input type="password" name="password" placeholder="Password"/>
        </div>
        <div class="input-group">
            <input type="password" name="password2" placeholder="Confirm Password"/>
        </div>
        <div class="input-group">
            <button type="submit" class="btn">Register</button>
        </div>
        <p style="text-align: center;">
        Already a member? | <a href="login.php" >Log in here!</a>
        </p>
    </form>
<?php
    html_footer();
?>