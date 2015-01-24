<?php include('_header.php');?>

<!-- show registration form, but only if we didn't submit already -->
<?php if (!$creation->creation_successful) { ?>
<h2>Formulaire de création de dépôt</h2>
<form method="post" action="creation_depot.php" name="creationform">
    Nom rapport : <input type=texte name="nom" required /><br>
    Année : 
    <SELECT name="annee" size="1" required>
        <OPTION DISABLED SELECTED value="">Choisissez une promotion.</OPTION>
        <OPTION>1A</OPTION>
        <OPTION>2A</OPTION>
        <OPTION>3A</OPTION>
        <OPTION>4A</OPTION>
        <OPTION>5A</OPTION>
    </SELECT><br>
    Département : 
    <SELECT name="departement" size="1" required>
        <OPTION DISABLED SELECTED value="">Choisissez un département.</OPTION>
        <OPTION>InfoTronique
        <OPTION>Matériaux
    </SELECT><br>
    Date d'expiration : <input type=date name="date" required /><br>
    A rendre en binôme : <input type="checkbox" name="binome" value="1"><br><br>
    <input type="submit" name="create" value="Créer le dépôt" />
</form>
<?php } ?>
<a href="index.php">Retour</a>
<a href="index.php?logout">Déconnexion</a>
<?php include('_footer.php');?>
