<?php //addPromo.php
    session_start();
    if(!isset($_SESSION['user'])) header('Location:login.php');
    require_once('output_f.php');
    html_header('Add Promo Code');
    menu2();
?>
    <form id="form" method="post" action="promo.php">
        <div class="input-group">
            <label>Promo code: </label>
            <input type="text" name="code" maxlength="8" required/>
        </div>
        <div class="input-group">
            <label>Start date: </label>
            <input type="date" name="dateStart" required/>
        </div>
        <div class="input-group">
            <label>Start time: </label>
            <input type="time" name="timeStart" required/>
        </div>
        <div class="input-group">
            <label>End date: </label>
            <input type="date" name="dateEnd" required/>
        </div>
        <div class="input-group">
            <label>End time: </label>
            <input type="time" name="timeEnd" required/>
        </div>
        <div class="input-group">
            <label>Min amount needed to spent: (RM)</label>
            <input type="number" min="1" name="conditions" required/>
        </div>
        <div class="input-group">
            <label>Discount: (RM)</label>
            <input type="number" name="discount" min="1" max="100" required/>
        </div>
        <div class="input-group">
            <label>Quantity: </label>
            <input type="number" name="quantityTotal" min="1" required/>
        </div>
        <div class="input-group">
            <button type="submit" class="btn">Add Promo Code</button>
        </div>
    </form>
<?php
    html_footer();
?>

