<?php include('_header.php'); ?>

<!-- show registration form, but only if we didn't submit already -->
<?php if (!$registration->registration_successful && !$registration->verification_successful) { ?>

<h2>Page d'inscription</h2>
<form method="post" action="register.php" name="registerform">
    Numéro étudiant : <input type="text" name="num_etu" value=<?php echo $num_etu; ?> readonly><br>
    
    Nom : <input type="text" name="nom" required /><br>
    
    Prénom : <input type="text" name="prenom" required /><br>
    
    Département : 
    <SELECT name="departement" size="1" required >
        <OPTION DISABLED SELECTED value="" >Choisissez votre département.</OPTION>
        <OPTION>InfoTronique
        <OPTION>Materiaux
    </SELECT><br>
    
    Année : 
    <SELECT name="annee_formation" size="1" required>
        <OPTION DISABLED SELECTED value="">Choisissez votre promotion.</OPTION>
        <OPTION>1A</OPTION>
        <OPTION>2A</OPTION>
        <OPTION>3A</OPTION>
        <OPTION>4A</OPTION>
        <OPTION>5A</OPTION>
    </SELECT><br>
    
    Mail : <input type="text" name="user_email" required />@etu.u-bourgogne.fr<br>

    Mot de passe : <input type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" /><br>
    
    Retapez votre mot de passe : <input type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" /><br><br>

    <input type="submit" name="register" value="<?php echo WORDING_REGISTER; ?>" />
</form>
<?php } ?>

    <a href="index.php"><?php echo WORDING_BACK_TO_LOGIN; ?></a>

<?php include('_footer.php'); ?>
