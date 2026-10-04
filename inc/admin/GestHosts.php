<?php 

if (isset($_SESSION['authentification']) && $_SESSION['privilege']>= 3)
{
	echo Affichage_Entete($_SESSION['opensim_select']);
	$moteursOK = Securite_Simulateur();
    /* ************************************ */
	//SECURITE MOTEUR
	$btnN1 = "disabled";$btnN2 = "disabled";$btnN3 = "disabled";
	if ($_SESSION['privilege'] == 4) {$btnN1 = ""; $btnN2 = ""; $btnN3 = "";} // Niv 4
	if ($_SESSION['privilege'] == 3) {$btnN1 = ""; $btnN2 = ""; $btnN3 = "";} // Niv 3
	if ($_SESSION['privilege'] == 2) {$btnN1 = ""; $btnN2 = "";}              // Niv 2
	if ($moteursOK == "OK" )
	{
		if($_SESSION['privilege'] == 1)
		{$btnN1 = "";$btnN2 = "";$btnN3 = "";}
	}
     //SECURITE MOTEUR
    /* ************************************ */

	
 	echo '<form class="form-group" method="post" action="">';
    echo '<input type="hidden" name="cmd" value="Ajouter" '.$btnN3.'>';
	echo '<button class="btn btn-success" type="submit" value="Ajouter un Simulateur" '.$btnN3.'><i class="glyphicon glyphicon-plus"></i></button> ';
	echo '</form>';
 
	//******************************************************
	// CONSTRUCTION de la commande pour ENVOI sur la console via  SSH
	//******************************************************
	if (isset($_POST['cmd']))
	{
		$osmw_simu =$_POST['name'];
		// on se connecte a MySQL
		try{$bdd = new PDO('mysql:host='.$hostnameBDD.';dbname='.$database.';charset=utf8', $userBDD, $passBDD);}
		catch (Exception $e){		die('Erreur : ' . $e->getMessage());	}
		
			
		//********************************************************
		if($_POST['cmd'] == 'Ajouter')
		{
			$i = NbOpensim() + 1;

			echo '<form method=post action="">';
			echo '<table class="table table-hover">';
			echo '<tr class="info">';
			echo '<th>Name</th>';
			echo '<th>Url</th>';
			echo '<th>API key</th>';
			echo '<th>Save</th>';
			echo '<th>Delete</th>';	
			echo '</tr>';		
			echo '<input type="hidden" name="idhosts" value="'.$data['idhosts'].'" >';
			echo '<tr>';
			echo '<td><input class="form-control" type="text" name="hosts_name" value="'.$data['hosts_name'].'" '.$btnN3.'></td>';
			echo '<td><input class="form-control" type="text" name="hosts_dns" value="'.$data['hosts_dns'].'" '.$btnN3.'></td>';
			echo '<td><input class="form-control" type="text" name="hosts_api_key" value="'.$data['hosts_api_key'].'" '.$btnN3.'></td>';
			echo '<td><button class="btn btn-success" type="submit" name="cmd" value="Enregistrer" '.$btnN3.'><i class="glyphicon glyphicon-edit"></i></button></td>';
			echo '<td><button class="btn btn-danger" type="submit" name="cmd" value="Supprimer" '.$btnN3.'><i class="glyphicon glyphicon-trash"></i></button></td>';
			echo '</tr></table></form>';

		
		}

		if ($_POST['cmd'] == 'Enregistrer')
		{	
			$sqlIns = "INSERT INTO hosts (`hosts_name`, `hosts_dns`, `hosts_api_key`) 
						VALUES ( '".$_POST['hosts_name']."', '".$_POST['hosts_dns']."', '".$_POST['hosts_api_key']."')";  
						
			echo "<p class='alert alert-success alert-anim'>";
            echo "<i class='glyphicon glyphicon-ok'></i>";
            echo " ".$osmw_simu." <strong>".$_POST['NewName']."</strong> ".$osmw_save_user_ok."</p>";
		} 
        
		if($_POST['cmd'] == 'Update')
		{
				$sqlIns = "
                UPDATE hosts 
                SET 
                    hosts_name = '".$_POST['hosts_name']."',
                    hosts_dns = '".$_POST['hosts_dns']."',
                    hosts_api_key = '".$_POST['hosts_api_key']."'
                WHERE idhosts = '".$_POST['idhosts']."'
            ";

			echo "<p class='alert alert-success alert-anim'>";
            echo "<i class='glyphicon glyphicon-ok'></i>";
            echo " ".$osmw_simu." <strong>".$_POST['hosts_name']."</strong> ".$osmw_edit_user_ok."</p>";
		}

		if($_POST['cmd'] == 'Supprimer')
		{			
			$sqlIns = "DELETE FROM hosts WHERE idhosts = ".$_POST['idhosts'];

			echo "<p class='alert alert-success alert-anim'>";
            echo "<i class='glyphicon glyphicon-ok'></i>";
            echo " ".$osmw_simu." <strong>".$_POST['NewName']."</strong> ".$osmw_delete_user_ok."</p>";
		}
		// Exécution de la requete
		if($sqlIns){$bdd->query($sqlIns);}
    }

    //******************************************************
    //  Affichage page principale
    //******************************************************

    echo '<p>'.$osmw_label_totl_simulator.' <span class="badge">'.NbOpensim().'</span></p>';
	echo '<table class="table table-hover">';
	echo '<tr class="info">';
	echo '<th>Name</th>';
	echo '<th>Url</th>';
	echo '<th>API key</th>';
	echo '<th>Save</th>';
	echo '<th>Delete</th>';	
	echo '</tr>';
	
		// on se connecte a MySQL
	try{$bdd = new PDO('mysql:host='.$hostnameBDD.';dbname='.$database.';charset=utf8', $userBDD, $passBDD);}
	catch (Exception $e){		die('Erreur : ' . $e->getMessage());	}

	$reponse = $bdd->query('SELECT * FROM hosts');

	// On affiche chaque entrée une à une
	while ($data = $reponse->fetch())
	{
		echo '<tr>';
		echo '<form method=post action="">';
		echo '<input type="hidden" name="idhosts" value="'.$data['idhosts'].'" >';
		echo '<tr>';
		echo '<td><input class="form-control" type="text" name="hosts_name" value="'.$data['hosts_name'].'" '.$btnN3.'></td>';
		echo '<td><input class="form-control" type="text" name="hosts_dns" value="'.$data['hosts_dns'].'" '.$btnN3.'></td>';
		echo '<td><input class="form-control" type="text" name="hosts_api_key" value="'.$data['hosts_api_key'].'" '.$btnN3.'></td>';
		echo '<td><button class="btn btn-success" type="submit" name="cmd" value="Update" '.$btnN3.'><i class="glyphicon glyphicon-edit"></i></button></td>';
        echo '<td><button class="btn btn-danger" type="submit" name="cmd" value="Supprimer" '.$btnN3.'><i class="glyphicon glyphicon-trash"></i></button></td>';
		echo '</tr>';	
		echo '</form>';
		echo '</tr>';
	}
	
	echo '</table>';
    $reponse->closeCursor(); 
}
else {header('Location: index.php');}
?>
