<?php //checkout.php
    session_start();
    require_once('output_f.php');
    require_once('db_connect.php');
    $conn = db_connect();

    html_header('Check Out');
    menu();
    if(isset($_SESSION['user'])) header('login.php');
    $un = $_SESSION['user'];
    show_login_user($un);
    if(isset($_POST['name']) && isset($_POST['address']) && isset($_POST['phone']) && isset($_POST['cardNo']) && isset($_POST['csc'])) {
        $rsId = $conn->query("SELECT id FROM user WHERE name = '$un'");
        $id = $rsId->fetch_array(MYSQLI_NUM);
        // get cart
        $rsCart = $conn->query("SELECT idInventory, quantity FROM cart WHERE idCustomer = $id[0]");
        for ($i = 0; $i < $rsCart->num_rows; $i++) {
            $cart = $rsCart->fetch_array(MYSQLI_NUM);

            // minus qty for inventory
            $rsUpdateInvQty = $conn->query("UPDATE inventory SET quantityLeft = quantityLeft - $cart[1] WHERE id = $cart[0]");
            if($rsUpdateInvQty){
                $code = $_SESSION['code'];
                $rsUpdatePromoQty = $conn->query("UPDATE promo SET quantityLeft = quantityLeft - 1 WHERE code = '$code'");
                if(!$rsUpdatePromoQty) die("Error during delete record for promo");
                $rsDeleteCart = $conn->query("DELETE FROM cart WHERE idCustomer = $id[0]");
                if(!$rsDeleteCart) die("Error during delete record for cart");
                else {
                    echo'<script>alert("Successfully checked out"); window.location = "member.php"</script>';
                    unset($_SESSION['code']);
                }
            } else die("Error during checkout");
        }
    }
    if(isset($_POST['checkout']) && isset($_POST['code'])) $_SESSION['code'] = $_POST['code'];

?>
    <form id="form" action="checkout.php" method="POST">
        <hr>
        <div class="input-group">
            <label style="text-align: center">-- Customer Info --</label>
        </div>
        <hr>
        <div class="input-group">
            <input type="text" name="name" value="<?= $un ?>" required/>
        </div>
        <div class="input-group">
            <input type="text" name="address" placeholder="Address" required/>
        </div>
        <div class="input-group">
            <input type="text" name="phone" placeholder="Phone Number" required/>
        </div>
        <hr>
        <div class="input-group">
            <label style="text-align: center">-- Credit Card Info --</label>
        </div>
        <hr>
        <div class="input-group">
            <select name="cardType" id="type">
                <option value="" hidden>-- Credit Card Type --</option>
                <option value="amex">Amex</option>
                <option value="visa">Visa</option>
                <option value="master">Master</option>
            </select>
        </div>
        <div class="input-group">
            <input name="cardNo" type='number' placeholder="Credit Card No" required/>
        </div>
        <div class="input-group">
            <select name="expireMonth" id="type" required>
                <option value="" hidden>-- Expire Date Month --</option>
                <option value="1">January (01)</option>
                <option value="2">February (02)</option>
                <option value="3">March (03)</option>
                <option value="4">April (04)</option>
                <option value="5">May (05)</option>
                <option value="6">June (06)</option>
                <option value="7">July (07)</option>
                <option value="8">August (08)</option>
                <option value="9">September (09)</option>
                <option value="10">October (10)</option>
                <option value="11">November (11)</option>
                <option value="12">December (12)</option>
            </select>
        </div>
        <div class="input-group">
            <select name="expireYear" id="type" required>
                <option value="" hidden>-- Expire Date Year --</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2024">2026</option>
                <option value="2024">2027</option>
                <option value="2024">2028</option>
            </select>
        </div>
        <div class="input-group">
            <input name="csc" minlength="3" maxlength="3" placeholder="CSC" pattern="^\d{3}$" /></br>
        </div>
        <hr>
        <div class="input-group">
            <input style="width:100%" class="btn" type="submit" value="CONFIRM CHECKOUT">
        </div>
        <hr>
    </form>
<?php
    html_footer();
?>