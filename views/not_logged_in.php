<?php include('_header.php'); ?>

<h2>Connexion</h2>
<form method="post" action="index.php" name="loginform">
    <label for="user_name"><?php echo WORDING_USERNAME; ?></label>
    <input id="user_name" type="text" name="user_name" required /><br>
    <label for="user_password"><?php echo WORDING_PASSWORD; ?></label>
    <input id="user_password" type="password" name="user_password" autocomplete="off" required /><br><br>
    <input type="submit" name="login" value="<?php echo WORDING_LOGIN; ?>" /><br><br>
</form>

<a href="pre_register.php"><?php echo WORDING_REGISTER_NEW_ACCOUNT; ?></a>
<a href="password_reset.php"><?php echo WORDING_FORGOT_MY_PASSWORD; ?></a>

<?php include('_footer.php'); ?>
