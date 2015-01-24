<?php include('_header.php'); ?>
<h2>Liste des rapports a rendre</h2>
<?php
    
    echo("<table border=\"1\">");
    
    echo("<tr>");
    echo("<th>Nom du rapport</th>");
    echo("<th>Date d'expiration</th>");
    echo("<th>Enseignant</th>");
	echo("<th>Rendu</th>");
    echo("</tr>");
    
    for($i=0; $i<$liste_rapport->nb_rapports; $i++){
        $id = $liste_rapport->rapports[$i]['id'];
        $nom = $liste_rapport->rapports[$i]['nom'];
        $date = $liste_rapport->rapports[$i]['date'];
        $prof = $liste_rapport->rapports[$i]['nom_prof'];
    
        echo("<tr>");
        echo'<td><b><a href="depot_rapport.php?depot='.$id.'">'.$nom.'</a></b></td>';
        echo("<td>".$date."</td><td>".$prof."</td>");
		$trouve = 0;
		for($j=0; $j<$liste_rapport->nb_rapports_rendus; $j++) {
			if($liste_rapport->rapportsrendus[$j]['id_depot'] == $id) {
				$trouve = 1;
			}
		}
		if($trouve) {
			echo("<td>Oui</td>");
		} else {
			echo("<td>Non</td>");
		}
        echo("</tr>");
    }
    
    echo("</table><br>");

?>

    <a href="index.php">Retour</a>
    <a href="index.php?logout">Déconnexion</a>

<?php include('_footer.php'); ?>
