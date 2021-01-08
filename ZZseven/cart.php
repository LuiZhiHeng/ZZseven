<?php //cart.php
    session_start();
    if(!isset($_SESSION['user'])) header('Location:login.php');
    require_once('db_connect.php');
    $conn = db_connect();
    require_once('output_f.php');
    html_header('Cart');
    menu();
    $un = $_SESSION['user'];
    show_login_user($un);

    if(isset($_GET['id']) && isset($_GET['quantity'])){
        $cid = $_GET['id'];
        $quantity = $_GET['quantity'];
        if($_GET['quantity'] > 0) $resultA = $conn->query("UPDATE cart SET quantity = '$quantity' WHERE id = '$cid'");
        elseif($_GET['quantity']== 0) $queryA2 = $conn->query("DELETE FROM cart WHERE id = '$cid'");
    }
?>
    <table style="margin-top: 0px" id="vp" border="1" cellspacing="0">
        <tr>
            <th>Product</th>
            <th>Unit Price (RM)</th>
            <th style="text-align:center">Qty</th>
            <th>Total (RM)</th>
        </tr>
<?php
    $userId = $conn->query("SELECT id FROM user WHERE name = '$un'");
    $rowid = $userId->fetch_array(MYSQLI_NUM);
    $sessionId = $rowid[0];
    $totalTemp = 0;
    $total = 0;
    $dis = 0;
    $ct = 0;
    $rsCart = $conn->query("SELECT cart.id, cart.quantity, inventory.id, inventory.name, inventory.price, inventory.quantityLeft FROM cart JOIN inventory ON inventory.id = cart.idInventory WHERE cart.idCustomer = '$sessionId'");
    for ($i=0; $i < $rsCart->num_rows; $i++) { 
        $cart = $rsCart->fetch_array(MYSQLI_NUM);
        $total = $totalTemp += ($cart[1] * $cart[4]);
?>
        <tr>
            <td><?= $cart[3] ?></td>
            <td style="text-align:right"><?= $cart[4] ?></td>
            <td class="ct"><input style="width:35px" type='number' name='quantity' min='0' max='<?= $cart[5]; ?>' value='<?= $cart[1] ?>' onchange='updateQty(<?= $cart[0] ?>, this.value)'></td>
            <td style="text-align:right"><?= sprintf("%.2f", ($cart[1] * $cart[4])) ?></td>
        </tr>
<?php
    }
?>
        <tr>
            <td style="text-align: right; font-weight: bold" colspan="3">Subtotal: </td>
            <td style="text-align: right"><?= sprintf("%.2f", $totalTemp); ?></td>
        </tr>
            <?php
                if(isset($_POST['code'])){
                    $code = $_POST['code'];
                    $rsPromo = $conn->query("SELECT id, code, quantityLeft, discount, conditions FROM promo WHERE code = '$code'");
                    $promo = $rsPromo->fetch_assoc();
                    if ($code == $promo['code']) {
                        if ($promo['quantityLeft'] <= 0) echo '<script>alert("Code (' . $promo['code'] . ') is not left.")</script>';
                        else {
                            if ($totalTemp < $promo['conditions']) echo '<script>alert("You need to spent at least RM' . $promo['conditions'] . ' to use this code (' . $promo['code'] . ')")</script>';
                            else {
                                $ct = 1;
                                $total -= $dis = $promo['discount'];
                                echo '<script>alert("Code (' . $promo['code'] . ') is prepared to use.")</script>';
                            }
                        }
                    } else echo '<script>alert("Code (' . $code . ') is not exist")</script>';
                }
                //echo '✔✘';
            ?>
        <tr>
            <td style="text-align: right; font-weight: bold">Promo Code: </td>
            <td>
            <?php if ($ct == 0) { ?>
                <form action="cart.php" method="POST">
                    <input name="code" type="text" placeholder="Enter Code Here..." minlength="1" maxlength="8">
            <?php } else echo $code; ?>
            </td>
            <td>
            <?php if ($ct == 0) { ?>
                <input style="width:100%" type="submit" value="➤">
            <?php } ?>
            </td>
            <?php if ($ct == 0) { ?>
            </form>
            <?php } ?>
            <td style="text-align: right">- <?= sprintf("%.2f", $dis); ?></td>
        </tr>
        <tr>
            <td style="text-align: right; font-weight: bold" colspan="3">Total: </td>
            <td style="text-align: right"><?= sprintf("%.2f", $total); ?></td>
        </tr>
        <tr>
            <td colspan="4">
                <form action="checkout.php" method="POST">
                    <input type="hidden" name="code" value="<?= $promo['code']; ?>">
                    <input name="checkout" class="btn" style="width:100%" type="submit" value="CHECKOUT">
                </form>
            </td>
        </tr>
    </table>
<script>
function updateQty(id, quantity){
    if (id=='' && quantity==''){
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                if(quantity==0){
                    alert("Item Removed");
                    window.location.reload();
                } else alert("Quantity changed")
            }
        }
        xmlhttp.open('GET', 'cart.php?id=' + id + '&quantity=' + quantity, true);
        xmlhttp.send();
    }
}
</script>
<?php
    html_footer();
?>