<?php //login.php
    require_once('output_f.php');
    html_header('Log in');
    menu0();
?>
    <form id="form" method="post" action="member.php">
        <div class="input-group">
            <input type="text" name="name" placeholder="Username" required/>
        </div>
        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required/>
            <p><small><a href="forgot_password.php" >Forgot Password?</a></small></p>
        </div>
        
        <div class="input-group">
            <button type="submit" class="btn">LOG IN</button>
        </div>
        <p style="text-align: center">
            No account yet? | <a href="register.php" >Register here!</a>
        </p>
        
    </form>
<?php
    html_footer();
?>