<?php //addProduct.php
    session_start();
    if(!isset($_SESSION['user'])) header("Location:login.php");
    require_once('output_f.php');
    html_header("Add Product");
    menu2();
    show_login_user($_SESSION['user']);
?>
    <form id="form" action="viewProduct.php" method="POST">
        <div class="input-group">
            Name: <input type="text" name="name" maxlength="128" required>
        </div>
        <div class="input-group">
            Type: <select id="type" name="type">
                <option value="" hidden></option>
                <option value="1">Acoustic</option>
                <option value="2">Classical</option>
            </select>
        </div>
        <div class="input-group">
            Price: <input type="number" name="price" min="1" required>
        </div>
        <div class="input-group">
            Quantity: <input type="number" name="quantity" min="1" max="32767" required>
        </div>
        <input class="btn" type="submit" name="add" value="Add Product">
    </form>
<?php
    html_footer();
?>