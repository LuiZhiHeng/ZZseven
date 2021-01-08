<?php //chat.php
    session_start();
    if(!isset($_SESSION['user'])) header('Location:login.php');
    require_once('db_connect.php');
    $conn = db_connect();
    $userName = $_SESSION['user']; //get user name
    if(isset($_POST['msg']) && isset($_POST['user']) && isset($_POST['other']) && isset($_POST['send'])) {
        $uId = $_POST['user'];
        $oId = $_POST['other'];
        $msg = addslashes($_POST['msg']);
        $rsSend = $conn->query("INSERT INTO chat (idSender, idReceiver, message) VALUES('$uId', '$oId', '$msg')");
        if(!$rsSend) die("Failed to send message");
    }
    if(isset($_POST['msg1']) && isset($_POST['otherName']) && !empty($_POST['msg1'])){
        $msg1 = addslashes($_POST['msg1']);
        $nameOt = $_POST['otherName'];
        $rsGetOtherId = $conn->query("SELECT id FROM user WHERE name = '$nameOt'");
        if($rsGetOtherId->num_rows > 0){
            $ouid = $rsGetOtherId->fetch_array(MYSQLI_NUM);
            $un = $_SESSION['user'];
            $rsGetId = $conn->query("SELECT id FROM user WHERE name = '$un'");
            $unid = $rsGetId->fetch_array(MYSQLI_NUM);
            $rsNewChat = $conn->query("INSERT INTO chat (idSender, idReceiver, message) VALUES($unid[0], $ouid[0], '$msg1')");
        } else echo'<script>alert("Invalid user name"); window.location = "chat.php";</script>';
    }

    //get user id
    $rsUserId = $conn->query("SELECT id, type FROM user WHERE name = '$userName'");
    $rowUser = $rsUserId->fetch_assoc();
    $userId = $rowUser['id'];
    require_once('output_f.php');
    html_header('Chat');
    ($rowUser['type'] == 2)? menu3(): (($rowUser['type'] == 1)? menu2(): menu());
    show_login_user($_SESSION['user']);
    echo'<div style="width: 90%; margin: 0 auto;">';
    //get other user id (if have)
    $rsOtherId = $conn->query("SELECT idSender FROM chat WHERE idReceiver = '$userId' UNION SELECT idReceiver FROM chat WHERE idSender = '$userId'");
    if($rsOtherId->num_rows == 0 && !isset($_GET['name'])) die('<p style="text-align: center">No message yet..</p>');
    elseif ($rsOtherId->num_rows != 0) echo'<table style="width: 30%" id="chatt">';
    for ($i=0; $i < $rsOtherId->num_rows; $i++) { 
        $id = $rsOtherId->fetch_array(MYSQLI_NUM);
        $arr[$rsOtherId->num_rows - $i -1] = $id[0]; //reverse the result & insert into arr
    }
    if($rsOtherId->num_rows > 0){
        for ($i=0; $i < count($arr); $i++) { 
            //get msg (single & newest) L
            $rsMsg1 = $conn->query("SELECT user.name, chat.idSender, chat.idReceiver, chat.message, chat.datetime FROM chat JOIN user ON user.id = $arr[$i] WHERE (chat.idReceiver = '$userId' AND chat.idSender = $arr[$i]) OR (chat.idSender = '$userId' AND chat.idReceiver = $arr[$i]) ORDER BY chat.id DESC LIMIT 1");
            $rowMsgDT1 = $rsMsg1->fetch_assoc();
            ?>
            <tr>
                <td>
                    <b><?= $rowMsgDT1['name']; ?></b>
                    <br><small><?php
                    if($rowMsgDT1['idSender'] == $userId) echo '<small><b><i>You: </i></b></small>';
                    echo substr($rowMsgDT1['message'] ,0 , 20);
                    if(strlen($rowMsgDT1['message']) > 20) echo '<small><b>...</b></small>'; 
                    ?></small><br>
                    <small><small><?= $rowMsgDT1['datetime'] ?></small></small>
                </td>
                <td>
                    <form method="post" action="chat.php">
                        <input type="hidden" name="userChoose" value="<?= $arr[$i]; ?>">
                        <input style="padding: 10px;background-color: white; " type="submit" value="➤">
                    </form>
                </td>
            </tr>
            <?php
        }
    }
    if (isset($_GET['name'])) {
        $otherName = addslashes($_GET['name']);
        $rsGetName = $conn->query("SELECT id FROM user WHERE name = '$otherName'");
        if($rsGetName->num_rows == 0) die('<p style="text-align: center">Invalid User</p>');
        $getName = $rsGetName->fetch_assoc();
        $getId = $getName['id'];
        $selectedId = $getName['id'];
        $rstestId = $conn->query("SELECT id FROM chat WHERE (idSender = '$getId' AND idReceiver = '$getId') OR (idReceiver = '$getId' AND idSender = '$getId')");
        if($rsOtherId->num_rows > 0 && $rstestId->num_rows > 0){
            for ($i = 0; $i < count($arr); $i++) {
                if (($arr[$i] != $getId) && (count($arr) - 1) == $i){
                    ?>
                    <tr>
                        <td>
                            <b><?= $otherName; ?></b><br><br>
                        </td>
                        <td>
                            <form method="post" action="chat.php">
                                <input type="hidden" name="userChoose" value="<?= $arr[$i]; ?>">
                                <input style="padding: 10px;background-color: white; " type="submit" value="➤">
                            </form>
                        </td>
                    </tr>
                    <?php
                }
            }
        } elseif($rsOtherId->num_rows == 0 && $rstestId->num_rows == 0) {
            ?>
                <table style="width: 30%" id="chatt">
                    <tr>
                        <td>
                            <b><?= $otherName; ?></b><br><br>
                        </td>
                        <td>
                            <form method="post" action="chat.php">
                                <input type="hidden" name="userChoose" value="<?= $selectedId; ?>">
                                <input style="padding: 10px;background-color: white; " type="submit" value="➤">
                            </form>
                        </td>
                    </tr>
            <?php
        }
    }
    //selected id to display msg, if not set, auto select the newest one
    if(isset($_POST['userChoose'])) $selectedId = $_POST['userChoose'];
    if(!isset($selectedId)) $selectedId = $arr[0];
    $rsOtherName = $conn->query("SELECT name FROM user WHERE id = '$selectedId'");
    $otherName = $rsOtherName->fetch_assoc();
    ?>
    </table>
    <table style="width: 70%" id="chatt" cellspacing="3">
        <tr><td style="text-align: center;border-bottom: 1px solid gray;"><b><?= $otherName['name']; ?></b></td></tr>
    <?php
        //get msg (selected) R
        $rsMsg = $conn->query("SELECT idSender, idReceiver, message, datetime FROM chat WHERE (idReceiver = '$userId' AND idSender = '$selectedId') OR (idSender = '$userId' AND idReceiver = '$selectedId')");
        for ($varMsg=0; $varMsg < $rsMsg->num_rows; $varMsg++) { 
            $rowMsgDT = $rsMsg->fetch_assoc();
            ?>
        <tr>
            <td>
            <?php
                if ($rowMsgDT['idSender'] == $userId) echo '<div style="text-align:right;">';
                else echo '<div>';
            ?><?= $rowMsgDT['message']; ?><br>
                <small><small><?= $rowMsgDT['datetime'] ?></small></small>
                </div>
            </td>
        </tr>
        <?php
        }
        ?>
        <!-- input and send -->
        <tr id="chatInput">
            <td><hr>
                <div style="width: 100%; margin: 0 auto;">
                    <form action="chat.php" method="POST">
                        <input type="number" name="user" value="<?php echo $userId; ?>" hidden>
                        <input type="number" name="other" value="<?php echo $selectedId; ?>" hidden>
                        <div style="width: 100%; margin: 0 auto;">
                            <input style="padding: 5px 10px; float: left; width: 76%;" type="text" name="msg" placeholder="Enter message here" required>
                            <input style="padding: 5px 10px; " type="submit" name="send" value="SEND">
                        </div>
                    </form>
                </div>
            </td>
        </tr>
    </table>
    </div>
    <?php
    html_footer();
?>
