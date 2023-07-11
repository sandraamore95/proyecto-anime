<?php include("parte_superior.php"); ?>
<!--Section: Block Content-->
<div class="mt-5 text-center">
    <p>Forgotten Password?</p>
    <div class="md-form md-outline">
        <form method="post">
            <p>Enter Email Address To Send Password Link</p>
            <input type="text" name="email">
            <input type="submit" name="submit_email">
        </form>
        <?php
        if (count($errors) > 0) {
            echo '<div class="alert alert-danger">
        <strong>'.$errors['email'].'</strong></div>';
        }
        ?>
    </div>
</div>
<?php include("parte_inferior.php"); ?>