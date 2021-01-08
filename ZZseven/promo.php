<?php //promo.php
    session_start();
    require_once('output_f.php');
    html_header('Promo Code');
    require_once('db_connect.php');
    $conn = db_connect();

    if(isset($_SESSION['user'])) { //menu
        $un = $_SESSION['user'];
        $rsUser = $conn->query("SELECT id, type FROM user WHERE name = '$un'");
        $user = $rsUser->fetch_assoc();
        $uid = $user['id'];
        ($user['type'] == 2)? menu3(): (($user['type'] == 1)? menu2(): menu());
        show_login_user($un);
    } else menu0();

    if(isset($_POST['code'])){
        $code = addslashes($_POST['code']);
        $dateStart = $_POST['dateStart'];
        $timeStart = $_POST['timeStart'];
        $dateEnd = $_POST['dateEnd'];
        $timeEnd = $_POST['timeEnd'];
        $conditions = $_POST['conditions'];
        $discount = $_POST['discount'];
        $qty = $_POST['quantityTotal'];
        $idSeller = $user['id'];

        if($timeEnd > $timeStart){echo "<script>alert('dateEnd needed to greater than dateStart'); window.location = 'addPromo.php';</script>"; }
        $rsAddPromo = $conn->query("INSERT INTO promo VALUES(NULL, '$idSeller', '$code', '$dateStart', '$dateEnd', '$timeStart', '$timeEnd', '$conditions', '$discount', '$qty', '$qty')");
        if($rsAddPromo) echo'<script>alert("Promo Code (' . $code . ') Added")</script>';
    }

    if (isset($_POST['delete']) && isset($_POST['idPromo'])){
        $idPromo = $_POST['idPromo'];
        $result2 = $conn->query("DELETE FROM promo WHERE id = '$idPromo'");
        if (!$result2) die("Delete failed<br><br>");
    }
?>
    <table id="vp" border="1" cellspacing="0" style="text-align: center">
        <tr>
            <th>No.</th>
            <th>Code</th>
            <th>Start</th>
            <th>End</th>
            <th>Min to spent (RM)</th>
            <th>Discount (RM)</th>
            <th>Qty</th>
            <?php if(isset($_SESSION['user']) && $user['type'] == 1) echo'<th>Delete</th>' ?>
        </tr>
    <?php
        if(isset($_SESSION['user']) && $user['type'] == 1) $query = "SELECT * FROM promo WHERE idSeller = $uid ORDER BY dateStart";
        else $query = "SELECT * FROM promo ORDER BY dateStart";
        $result = $conn->query($query);
        for($i=0;$i<$result->num_rows;$i++){
            $row = $result->fetch_assoc();
    ?>
        <tr>
            <td><?= ($i+1) ?></td>
            <td><?= $row['code'] ?></td>
            <td><?= $row['dateStart'] . ' ' . $row['timeStart'] ?></td>
            <td><?= $row['dateEnd'] . ' ' . $row['timeEnd'] ?></td>
            <td><?= $row['conditions'] ?></td>
            <td><?= $row['discount']; ?></td>
            <td><?= $row['quantityLeft'] . '/' . $row['quantityTotal'];  ?></td>
    <?php
            if(isset($_SESSION['user']) && $user['type'] == 1){
            $rid = $row['id'];
            echo "<td><form id='nof' action='promo.php' method='post'>
            <input type='hidden' name='delete' value='yes'>
            <input type='hidden' name='idPromo' value='" . $rid . "'>
            <input type='submit' name='delete' value='DELETE'>
            </form></td>";
            }
            echo '</tr>';
        }
        
        if(isset($_SESSION['user']) && $user['type'] == 1){
    ?>
        <tr>
            <td colspan="8">
                <input style="display: block;margin: auto;padding: 5px 10px; width: 50%;" type="button" onclick="location.href='addPromo.php'" value="ADD PROMO CODE">
            </td>
        </tr>
    <?php
        }
    ?>
    </table>
<?php
    html_footer();
?>