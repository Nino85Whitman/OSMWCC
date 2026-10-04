<?php
   
    /* ************************************ */
    /* FONCTION choix du simulateur */
    /* ************************************ */
    function Select_Simulateur($simu)
    {   
        require 'inc/config/config.php';
        
        // Formulaire de choix du moteur a selectionne
        // on se connecte a MySQL
        try{$bdd = new PDO('mysql:host='.$hostnameBDD.';dbname='.$database.';charset=utf8', $userBDD, $passBDD);}
        catch (Exception $e){       die('Erreur : ' . $e->getMessage());    }

        $reponse = $bdd->query('SELECT * FROM hosts');

        echo '<form class="form-group" method="post" action="">';   
        echo '<div class="form-inline">';
        echo '<select class="form-control form-control" name="OSSelect">';
			while ($data = $reponse->fetch())
			{
				$sel = "";
				if ($data['hosts_name'] == $_SESSION['opensim_select']) {$sel = "selected";}
				echo '<option value="'.$data['hosts_name'].'" '.$sel.'>'.$data['hosts_name'].'</option>';
			}
        echo'</select>';
        echo' <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-saved"></i></button>';
        echo '</div>';
        echo'</form>';

        $reponse->closeCursor();
    }       
 
    /* ************************************ */
    /* FONCTION affichage Entete Simulateur Selectionné et Niveau de securité */
    /* ************************************ */
    function Affichage_Entete($simu)
    {       
        if (isset($_POST['OSSelect'])) {$_SESSION['opensim_select'] = trim($_POST['OSSelect']);}
		return Select_Simulateur($_SESSION['opensim_select']);
    }   

    /* ************************************ */
    /* FONCTION Defini affichage bouton en fonction du Niveau de securité */
    /* ************************************ */
    function Securite_Simulateur()
    {       
        if($_SESSION['osAutorise'] != '')
        {
            $osAutorise = explode("|", $_SESSION['osAutorise']);
            // echo count($osAutorise);
            // echo $_SESSION['osAutorise'];
            for ($i = 0; $i < count($osAutorise); $i++)
            {
                if (INI_Conf_Hosts($_SESSION['opensim_select'], "idhosts") == $osAutorise[$i])
                {
                    $moteursOK = "OK";
                }
            }
        }
        else {$moteursOK = "";}
        return $moteursOK;
    }       
    
    /* ************************************ */
    /* FONCTION Recuperation en BDD de la config de OSMW */
    /* ************************************ */
    function INI_Conf($cles, $valeur)
    {
        require 'inc/config/config.php';
        // on se connecte a MySQL
        try{$bdd = new PDO('mysql:host='.$hostnameBDD.';dbname='.$database.';charset=utf8', $userBDD, $passBDD);}
        catch (Exception $e){       die('Erreur : ' . $e->getMessage());    }

        $reponse = $bdd->query('SELECT * FROM config');
        $data = $reponse->fetch();
        
        switch ($valeur)
        {
            default:
                $Version = "N.C";
            case "cheminAppli":
                $Version = $data['cheminAppli'];
                break;
            case "destinataire":
                $Version = $data['destinataire'];
                break;
            case "Autorized":
                $Version = $data['Autorized'];
                break;
            case "NbAutorized":
                $Version = $data['NbAutorized'];
                break;
            case "VersionOSMW":
                $Version = $data['VersionOSMW'];
                break;
            }
            $reponse->closeCursor(); 
        return $Version;
    }

    /* ************************************ */
    /* FONCTION Recuperation en BDD en fonction du simulateur sélectionné */
    /* ************************************ */
    function INI_Conf_Hosts($cles, $valeur)
    {
        require 'inc/config/config.php';
        // on se connecte a MySQL
        try{$bdd = new PDO('mysql:host='.$hostnameBDD.';dbname='.$database.';charset=utf8', $userBDD, $passBDD);}
        catch (Exception $e){       die('Erreur : ' . $e->getMessage());    }

        $reponse = $bdd->query("SELECT * FROM hosts WHERE hosts_name ='".$cles."'");
        $data = $reponse->fetch();

        $Version = "";

        switch ($valeur)
        {
            default:
                $Version = "N.C";
            case "hosts_name":
                $Version = $data['hosts_name'];
                break;
            case "hosts_dns":
                $Version = $data['hosts_dns'];
                break;
            case "hosts_api_key":
                $Version = $data['hosts_api_key'];
                break;
            case "idhosts":
                $Version = $data['idhosts'];
                break;
            }
            $reponse->closeCursor(); 
        return $Version;
    }
	
    /* ************************************ */
    /* FONCTION Retourne le nombre de simulateur */
    /* ************************************ */
    function NbOpensim()
    {
        require 'inc/config/config.php';
        // On se connecte à MySQL
        try{$bdd = new PDO('mysql:host='.$hostnameBDD.';dbname='.$database.';charset=utf8', $userBDD, $passBDD);}
        catch (Exception $e){       die('Erreur : ' . $e->getMessage());    }

        $reponse = $bdd->query("SELECT * FROM hosts");
        $num_rows = $reponse->rowCount();
        $reponse->closeCursor(); 
        
        return $num_rows;
    }

    /* ************************************ */
    /* FONCTION generation de UUID pour region */
    /* ************************************ */
    function GenUUID()
    {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
    }

    /* ************************************ */
    /* FONCTION Test url complete pour image de la region sélectionné */
    /* ************************************ */
    function Test_Url($server)
    {
        $tab = parse_url($server);
        $tab['port'] = isset($tab['port']) ? $tab['port'] : 40;

        error_reporting(E_ERROR | E_PARSE);
        if (!fsockopen($tab['host'], $tab['port'], $errno, $errstr, 5))
        {
             return false;
        } else 
        {
             return true;
        }
    
       error_reporting(-1);
    }

    /* ************************************ */
    /* FONCTION Test url complete pour image de la region sélectionné */
    /* ************************************ */
    function Url_Api_OSMW($url)
    {
		$arrContextOptions=array(
			  "ssl"=>array(
					"verify_peer"=>false,
					"verify_peer_name"=>false,
				),
			);  

		return $content = trim(file_get_contents($url, false, stream_context_create($arrContextOptions)));
		}    
    /* ************************************ */
    /* FONCTION Matrice pour transfert de fichiers */
    /* ************************************ */   
    function gen_matrice($cur)
    {
        global $PHP_SELF, $order, $asc, $order0;

        if ($dir = opendir($cur))
        {
            /* tableaux */
            $tab_dir = array();
            $tab_file = array();

            /* extraction */
            while($file = readdir($dir))
            {
                if (is_dir($cur."/".$file))
                {
                    if (!in_array($file, array(".", "..")))
                    {
                        $tab_dir[] = addScheme($file, $cur, 'dir');
                    }
                }
                else {$tab_file[] = addScheme($file, $cur, 'file');}
            }

            /* affichage */
            foreach($tab_file as $elem) 
            {
                if (assocExt($elem['ext']) <> 'inconnu')
                {
                    // echo "<p><input type='checkbox' name='matrice[]' value='".$elem['name']."'> ".$elem['name']."</p>";
                    echo '<div class="checkbox">';
                    echo '<label><input type="checkbox" name="matrice[]" value="'.$elem['name'].'">';
                    echo ' <i class="glyphicon glyphicon-saved text-success"></i> '.$elem['name'].' ';
                    echo '</label> ';
                    echo formatSize($elem['size']);
                    echo '</div>';
                }
            }
             closedir($dir);
        }
    }
    
//--------------------------------------------------------------------------------------------
    /* Fonction envoi commande SSH  */
    function CommandeSSH($hostname2,$usernameSSH2,$passwordSSH2,$commande)
    {
        if($commande <> '')
        {
            if (!function_exists("ssh2_connect")) die(" function ssh2_connect doesn't exist");
            // log in at server1.example.com on port 22
            if(!($con = ssh2_connect($hostname2, 22))){
                echo " fail: unable to establish connection\n";
            } else 
            {// try to authenticate with username root, password secretpassword
                if(!ssh2_auth_password($con,$usernameSSH2,$passwordSSH2)) {
                    echo "fail: unable to authenticate\n";
                } else {
                //echo " ok: logged in...\n";
                    if (!($stream = ssh2_exec($con, $commande ))) {
                        echo " fail: unable to execute command\n";
                    } else {
                        // collect returning data from command
                        stream_set_blocking($stream, true); $data = "";
                        while ($buf = fread($stream,4096)) 
                        {
                        $data .= $buf."\n";}
                       // echo $data;                   
                        fclose($stream);
                    }
                }
            } 
		return $data;    
        }
    }
