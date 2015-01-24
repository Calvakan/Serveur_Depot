<?php include('_header.php'); ?>

<h2>Page de pré-inscription</h2>
<form method="post" action="pre_register.php" name="pre_registerform">
    Numéro d'étudiant : <input id="num_etu" type="text" name="num_etu" required /><br><br>

    <input type="submit" name="pre_register" value="<?php echo WORDING_REGISTER; ?>" />
</form>

    <a href="index.php"><?php echo WORDING_BACK_TO_LOGIN; ?></a>

<?php include('_footer.php'); ?>
