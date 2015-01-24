<?php
    
    
    include_once('global_data.php');
    
    // creation des variables de session
    session_start();
    
    print_r ($_SESSION);
    
    $tableau_donnees = array('stagiaire', 'entreprise', 'maitreStage', 'correspondantUniv', 'stage','cursus');
    
    // récuperation des variables de session dans des variables globales
    foreach ($tableau_donnees as $key=>$val) {
        if (!isset($_SESSION[$val])) {
            $$val = array();
        }
        else {
            $$val=$_SESSION[$val];
        }
    }
    
    //
    
   $missingRequiredFields =0;
    
    // verification des parametres de session
    
    // stockage des informations de session dans un fichier temporaire
    
    $key = substr(uniqid ('', true), -8);
    
    $date =date('Y-m-d,h:m:s');
    
    $ip_address = $_SERVER['REMOTE_ADDR']; 
    
    $new_entry = array($key, $date, $ip_address);

    $fp = fopen('internship_entries.csv', 'a');
    fputcsv($fp, $new_entry);
    fclose($fp);
    
   ////// 
    
    // extraction des données post, stockage dans des variables globales
    
    // stagiaire
    $form_stagiaire=array();
    $form_stagiaire['prenom']=array('form_correspondance' => 'stagiaire_prenom', 'critical' => 1);
    $form_stagiaire['nom']=array('form_correspondance' => 'stagiaire_nom', 'critical' => 1);
    $form_stagiaire['mail']=array('form_correspondance' => 'stagiaire_mail', 'critical' => 1);
    $form_stagiaire['telephone']=array('form_correspondance' => 'stagiaire_telephone', 'critical' => 1);
    $form_stagiaire['secours_prenom']=array('form_correspondance' => 'stagiaire_secours_prenom', 'critical' => 0);
    $form_stagiaire['secours_nom']=array('form_correspondance' => 'stagiaire_secours_nom', 'critical' => 1);
    $form_stagiaire['secours_telephone']=array('form_correspondance' => 'stagiaire_secours_telephone', 'critical' => 1);
    $form_stagiaire['secours2_prenom']=array('form_correspondance' => 'stagiaire_secours2_prenom', 'critical' => 0);
    $form_stagiaire['secours2_nom']=array('form_correspondance' => 'stagiaire_secours2_nom', 'critical' => 0);
    $form_stagiaire['secours2_telephone']=array('form_correspondance' => 'stagiaire_secours2_telephone', 'critical' => 1);
    $form_stagiaire['adresse']=array('form_correspondance' => 'stagiaire_adresse', 'critical' => 1);
    $form_stagiaire['adresse2']=array('form_correspondance' => 'stagiaire_adresse2', 'critical' => 0);
    $form_stagiaire['cp']=array('form_correspondance' => 'stagiaire_cp', 'critical' => 0);
    $form_stagiaire['ville']=array('form_correspondance' => 'stagiaire_ville', 'critical' => 0);
    $form_stagiaire['province']=array('form_correspondance' => 'stagiaire_province', 'critical' => 0);
    $form_stagiaire['pays']=array('form_correspondance' => 'stagiaire_pays', 'critical' => 0);
    $form_stagiaire['activite']=array('form_correspondance' => 'stagiaire_activite', 'critical' => 0);
    
    
    foreach ($form_stagiaire as $key=>$val) {
        if (isset ($_POST[$val['form_correspondance']])) {
            $stagiaire[$key]=$_POST[$val['form_correspondance']];
        }
        else {
            $stagiaire[$key]='';
        }
        if ($stagiaire[$key]=='' && $val['critical']==1) $missingRequiredFields++;
    }
    
    // cursus
    $form_cursus=array();
    $form_cursus['dep']=array('form_correspondance' => 'cursus_dep', 'critical' => 1);
    $form_cursus['annee']=array('form_correspondance' => 'cursus_annee', 'critical' => 1);
    
    foreach ($form_cursus as $key=>$val) {
        if (isset ($_POST[$val['form_correspondance']])) {
            $cursus[$key]=$_POST[$val['form_correspondance']];
        }
        else $cursus[$key]='';
        if ($cursus[$key]=='' && $val['critical']==1) $missingRequiredFields++;
    }
    
    // entreprise
    $form_entreprise=array();
    $form_entreprise['nom']=array('form_correspondance' => 'entreprise_nom', 'critical' => 1);
    $form_entreprise['adresse']=array('form_correspondance' => 'entreprise_adresse', 'critical' => 1);
    $form_entreprise['adresse2']=array('form_correspondance' => 'entreprise_adresse2', 'critical' => 0);
    $form_entreprise['cp']=array('form_correspondance' => 'entreprise_cp', 'critical' => 0);
    $form_entreprise['ville']=array('form_correspondance' => 'entreprise_ville', 'critical' => 0);
    $form_entreprise['province']=array('form_correspondance' => 'entreprise_province', 'critical' => 0);
    $form_entreprise['pays']=array('form_correspondance' => 'entreprise_pays', 'critical' => 0);
    $form_entreprise['activite']=array('form_correspondance' => 'entreprise_activite', 'critical' => 0);
     $form_entreprise['site']=array('form_correspondance' => 'entreprise_site', 'critical' => 0);  
    
    foreach ($form_entreprise as $key=>$val) {
        if (isset ($_POST[$val['form_correspondance']])) {
            $entreprise[$key]=$_POST[$val['form_correspondance']];
        }
        else $entreprise[$key]='';
        if ($entreprise[$key]=='' && $val['critical']==1) $missingRequiredFields++;
    }
    
    // maitreStage
    $form_maitreStage=array();
    $form_maitreStage['prenom']=array('form_correspondance' => 'maitreStage_prenom', 'critical' => 0);
    $form_maitreStage['nom']=array('form_correspondance' => 'maitreStage_nom', 'critical' => 1);
    $form_maitreStage['mail']=array('form_correspondance' => 'maitreStage_mail', 'critical' => 0);
    $form_maitreStage['telephone']=array('form_correspondance' => 'maitreStage_telephone', 'critical' => 0);
    
    
    foreach ($form_maitreStage as $key=>$val) {
        if (isset ($_POST[$val['form_correspondance']])) {
            $maitreStage[$key]=$_POST[$val['form_correspondance']];
        }
        else $maitreStage[$key]='';
        if ($maitreStage[$key]=='' && $val['critical']==1) $missingRequiredFields++;

    }
    
    // correspondantUniv
    $form_corresUniv=array();
    $form_corresUniv['prenom']=array('form_correspondance' => 'correspondantUniv_prenom', 'critical' => 0);
    $form_corresUniv['nom']=array('form_correspondance' => 'correspondantUniv_nom', 'critical' => 1);
    $form_corresUniv['mail']=array('form_correspondance' => 'correspondantUniv_mail', 'critical' => 0);
    
    foreach ($form_corresUniv as $key=>$val) {
        if (isset ($_POST[$val['form_correspondance']])) {
            $correspondantUniv[$key]=$_POST[$val['form_correspondance']];
        }
        else $correspondantUniv[$key]='';
        if ($correspondantUniv[$key]=='' && $val['critical']==1) $missingRequiredFields++;
    }
    
    // stage
    $form_stage=array();
    $form_stage['sujet']=array('form_correspondance' => 'stage_sujet', 'critical' => 1);
    
    $form_stage['datedebut']=array('form_correspondance' => 'stage_datedebut', 'critical' => 1);
    $form_stage['datefin']=array('form_correspondance' => 'stage_datefin', 'critical' => 1);
    
    foreach ($form_stage as $key=>$val) {
        if (isset ($_POST[$val['form_correspondance']])) {
            $stage[$key]=$_POST[$val['form_correspondance']];
        }
        else $stage[$key]='';
        if ($stage[$key]=='' && $val['critical']==1) $missingRequiredFields++;
    }
    
    
    // sauvegarde des variables globales dans les variables de session
    foreach ($tableau_donnees as $key=>$val) {
        $_SESSION[$val]=$$val;
    }
    

    ?>




<!DOCTYPE html>
<html>
    <style>
        body
        {
            position:relative;
            margin:10px;
            padding:0;
            width:850px;
        }
        
        .footer {
            position:relative;
            width:100%;
           
            text-align:center;
        }
        
        input[type=submit]
        {
        
            cursor:pointer;
            font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
            font-size: 12pt;
            right:0px:
            left:10px;
  
        }
        .personne
        {
            position:relative;
            width:390px;
            
            padding:10px;
            background-color:#eeeeee;
            
            margin-left: 0px;
            border: 1px solid black;
            font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
            font-size: 9pt;
            
        }
        
        .personne input[type=text]
        {
            
            background-color: #BFBDBD;
            border:solid 1px #BFBDBD;
            height: 13px;
            padding-left:10px;
            width: 120px;
            position:absolute;
            left:170px;
            box-shadow: 1px 1px 0 #828181 inset;
        }
        
        .personne select
        {
            margin:2px 2px 5px 5px;
            position:absolute;
                   
          left:170px;
        }
        .personne input[type=text].mail
        {
            width: 220px;
            font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
        }
        
        .adresse {
            padding:10px;
            width: 390px;
            border:solid 1px #0F0D0D;
            font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
            font-size: 9pt;
        }
        
        
        .adresse input[type=text] {
            background-color: #BFBDBD;
            border:solid 1px #BFBDBD;
            height: 13px;
            padding-left:10px;
            width: 120px;
            position:absolute;
            left:190px;
            box-shadow: 1px 1px 0 #828181 inset;
        }
        
        .adresse input[type=text].champadresse {
            
            width: 190px;
            
        }
        
        .adresse input[type=text].cp {
            position:absolute;
            border:solid 1px #BFBDBD;
            width: 50px;
        }
        
        .adresse .ville {
            position:absolute;
            border:solid 1px #BFBDBD;
            width: 200px;
            left:100px;      }
        
        h5{
            width:100%;
            margin-left: -1px;
            margin-top :0px;
            margin-right: -1px;
            margin-bottom: 6px;

            border: 1px solid red;
            font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
            font-size: 9pt;
            color: black;
            padding-top: 0px;
            padding-bottom: 0px;
            
        }
        
        .doubleTableau {
            position:relative;
            margin-top :10px;
            margin-bottom :10px;

            width:700;
            margin: 0px;
        }
        
        
        .tableauDroite {
            position:absolute;
           
            right:0px;

            top:0px;
        }
        #doubleTableauEntreprise {
            position:relative;
            margin-top :10px;
            margin-bottom :10px;
               height:180px;
            width:700;
            margin: 0px;
        }
        
        #tableauGaucheEntreprise {
            position:absolute;
            width:390px;
           height:160px;
              top:0px;
            padding:10px;
            left:0px;
            margin-left: 0px;
            border: 1px solid black;
            font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
            font-size: 9pt;

        }
        
        #tableauDroiteEntreprise {
            position:absolute;
            width:390px;
            right:0px;
            top:0px;
            height:160px;
            padding:10px;
            
            margin-left: 0px;
            border: 1px solid black;
            font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
            font-size: 9pt;
            
        }       
        #sujetStage
        {       
            width: 490px;
            
        }
        
        .donneesStage
        {
            position:relative;
            width:808px;
            
            padding:10px;
            background-color:#eeeeee;
            
            margin-left: 0px;
            margin-bottom : 5px;
            
            border: 1px solid black;
            font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
            font-size: 9pt;
            
        }
        
        .donneesStage input[type=text]
        {
            
            background-color: #BFBDBD;
            border:solid 1px #BFBDBD;
            height: 13px;
            padding-left:10px;
            width: 170px;
            position:absolute;
            left:310px;
            box-shadow: 1px 1px 0 #828181 inset;
        }
        
        
        .entreprise input[type=text]
        {
            
            background-color: #BFBDBD;
            border:solid 1px #BFBDBD;
            height: 13px;
            padding-left:10px;
            width: 200px;
            position:absolute;
            left:150px;
            box-shadow: 1px 1px 0 #828181 inset;
        }
        
        #personneSecours1 {
            
        }
        
        #personneSecours2 {
            position:absolute;
            right:0px;
            top:0px;
        }
        
        #correspondant {
            position:absolute;
            right:0px;
        }
        
        .cursus {
            position:static;
        }
        
        .section {
            background-color: #EEEEEE;   
            position:relative;
            padding:10px;
            margin:0px;
            
        }
        #infoEtudiant {
            clear:both;    
            
            
        }
        
        #infoEntreprise {
      clear:both
        }
        
        #infoStage {
           
        }
        
        
        #maitrestage {
            
        }
        
        #correspondant {
            position:absolute;
            right:0px;
            top:0px;
            
        }
        
        
        .warning {
            position:absolute;
            right:0px;
            top:0px;
            width:200px;
            height:50px;
            font-size:12pt;
            background-color: #EE0000;
            color : white;
            text-decoration:blink;
            border: 1px solid red;
            padding : 5px;
            margin : 5px;
        }

        
        </style>
    <head>
        
    </head>
    <body>
    <H5> personne(s) à contacter en cas d'urgence </H5>
    <UL> 
    <LI>
<?php
    if (isset($stagiaire['secours2_nom'])) {
    echo "<LI> Mme/Mr ".$stagiaire['secours2_prenom']." ".$stagiaire['secours2_nom'].":".$stagiaire['secours2_telephone'];
}
?>
</UL>
    </body>
<?php



?>
</html>