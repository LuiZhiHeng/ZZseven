<?php //forgot_password.php
    require_once('output_f.php');
    html_header('Forgot Password');
    menu0();
?>
    <form id="form" method="post" action="reset_password.php">
        <div class="input-group">
            <input type="text" name="email" placeholder="Email" required/>
        </div>
        <div class="input-group">
            <button type="submit" class="btn">GET NEW PASSWORD</button>
        </div>
<?php
    html_footer();
?>