<?php 
include('_header.php');

if($liste_depot->delete) {
    echo"Dépôt ".$liste_depot->nom_delete." bien supprimé.";
    echo'<br><br>';
            
    ?> <a href="liste_depot.php">Retour</a>
    <?php  
} else if (!$liste_depot->depot_precis) {
    if($liste_depot->nb_depots != 0) {   
		echo("<h2>");
        echo("Liste de vos dépôts activés");
        echo("</h2>");
	
        echo("<table border=\"1\">");        
        echo("<tr>");
        echo("<th>Nom du rapport</th>");
        echo("<th>Section</th>");
        echo("<th>Promotion</th>");
        echo("<th>Date d'expiration</th>");
        echo("<th>Binôme</th>");
        echo("</tr>");
                    
        for($i=0; $i<$liste_depot->nb_depots; $i++){
        $id = $liste_depot->result[$i]['id'];
        $nom = $liste_depot->result[$i]['nom'];
        $departement = $liste_depot->result[$i]['departement'];
        $annee = $liste_depot->result[$i]['annee'];
        $date = $liste_depot->result[$i]['date'];
        $binome = $liste_depot->result[$i]['binome'];
        
        echo("<tr>");
        echo'<td><b><a href="liste_depot.php?depot='.$id.'">'.$nom.'</a></b></td>';
        echo ("<td>".$departement."</td><td>".$annee."</td><td>".$date."</td>");
        if($binome) {
            echo ("<td>X</td>");
        } else {
            echo ("<td></td>");
        }
        echo'<td><b><a href="liste_depot.php?delete='.$id.'">Supprimer</a></b></td>';
        echo("</tr>");
        }
                    
        echo("</table><br>");
    } else 
        echo("Vous n'avez activé aucun dépôt.<br><br>");
    ?>
    <a href="index.php">Retour</a>
    
    <?php 
    } else {
        echo("<h2>");
        echo("Visualisation du dépôt ");
        echo($liste_depot->nom_depot);
        echo("</h2>");
    
        echo("<table border=\"1\">");
        
        echo("<tr>");
        echo("<th>Nom</th>");
        echo("<th>Prénom</th>");
        echo("<th>Rendu</th>");
        echo("</tr>");        
        
        for($i=0; $i<$liste_depot->nb_personnes; $i++){
            $nom = $liste_depot->tableau_final_eleves[$i]['nom'];
            $prenom = $liste_depot->tableau_final_eleves[$i]['prenom'];
            //$id = $liste_depot->tableau_final_eleves[$i]['id_depot'];
			$trouve = 0;
			for($j=0; $j<$liste_depot->nb_rendus; $j++) {
				if($liste_depot->tableau_final_rendu[$j]['nom'] == $nom 
				&& $liste_depot->tableau_final_rendu[$j]['prenom'] == $prenom) 
				{
					$trouve = 1;
				}
			}
			echo("<tr>");
			echo ("<td>".$nom."</td><td>".$prenom."</td>");
			if($trouve == 1)//si il y a correspondance
				echo("<td>X</td>");//on met une croix
			else
				echo("<td></td>");//sinon on laisse la case vide
			echo("</tr>");
        }
        echo("</table><br>");
    
		echo'<a href="'.$liste_depot->dest_filename.'">Télécharger</a>';
    
    
?>
    <a href="liste_depot.php">Retour</a>
<?php 
}?>

    <a href="index.php?logout">Déconnexion</a>

<?php include('_footer.php'); ?>
