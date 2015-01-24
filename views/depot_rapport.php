<?php include('_header.php');?>

<!-- show depot_rapport form, but only if we didn't submit already -->
<?php if (!$depot_rapport->depot_rapport_successful && !$depot_rapport->depot_perime) { ?>
<form method="post" action="depot_rapport.php" enctype="multipart/form-data" name="creationdepotform">
    <h2>Formulaire de dépôt de rapport : <?php echo $depot_rapport->nom_depot; ?></h2>
    
    <input type="hidden" name='id_depot' value=<?php echo $id_depot; ?>> 
    
    Rapport : <input type='file' name='file' id='file' required><br><br>
    
    <?php if ($depot_rapport->binome) {;?>
    Binôme : 
    <SELECT name="binome" size="1" required>
        <OPTION value = "" DISABLED SELECTED>Choisissez votre binôme.</OPTION>
       <?php 
        for($i=0; $i<$depot_rapport->nb_eleves;$i++) {
            $nom = $depot_rapport->liste_binome[$i]['nom'];
            $prenom = $depot_rapport->liste_binome[$i]['prenom'];
            $nom_complet = $nom." ".$prenom;
            //echo $nom_complet;
            echo ("<OPTION>$nom_complet</OPTION>");
        }
       ?>
    </SELECT><br><br>
    <?php }?>
    
    <input type="submit" name="rapport_rendu" value="Rendre ce rapport" />
</form>
<?php } 
	else if($depot_rapport->depot_perime) {?>
		<h1>Dépôt <?php echo $depot_rapport->nom_depot; ?></h1>
		<p>Ce dépôt est périmé. Adressez vous au professeur qui l'a créé : </p>
		<a href="mailto:<?php echo $depot_rapport->mail_prof; ?>">
		<?php echo $depot_rapport->mail_prof; ?></a><br>
<?php }?>
<br>
<a href="liste_rapport.php">Retour</a>
<a href="index.php?logout">Déconnexion</a>

<?php include('_footer.php');?>
