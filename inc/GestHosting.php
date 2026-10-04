<?php 
if (isset($_SESSION['authentification']))
{
	echo Affichage_Entete($_SESSION['opensim_select']);
	echo $moteursOK = Securite_Simulateur();
    /* ************************************ */
	//SECURITE MOTEUR
	$btnN1 = "disabled";$btnN2 = "disabled";$btnN3 = "disabled";
	if ($_SESSION['privilege'] == 4) {$btnN1 = ""; $btnN2 = ""; $btnN3 = "";} // Niv 4
	if ($_SESSION['privilege'] == 3) {$btnN1 = ""; $btnN2 = ""; $btnN3 = "";} // Niv 3
	if ($_SESSION['privilege'] == 2) {$btnN1 = ""; $btnN2 = "";}              // Niv 2
	if ($moteursOK == "OK" )
	{
		if($_SESSION['privilege'] == 1)		{$btnN1 = "";$btnN2 = "";$btnN3 = "";}
	}
     //SECURITE MOTEUR
    /* ************************************ */


	//**************************
	// COMMANDE ENVOYE VIA API
	//**************************
	$messageInfo = '<div class="alert alert-success alert-anim" role="alert">
	<strong><center>Consulter le fichier log / Refer to the log file.<br> <br></center></strong>
	<div class="progress"><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 45%"><span class="sr-only">85% Complete</span></div></div>
	</div>';
	
	$url ="";
	$url = INI_Conf_Hosts($_SESSION['opensim_select'], "hosts_dns")."api/?api_key=".INI_Conf_Hosts($_SESSION['opensim_select'], "hosts_api_key");
	
	//**************************
	
	if(isset($_POST['cmd']))
	{
		if($_POST['cmd'] == 'Start')			{$url_send = $url."&opensim_select=".$_POST["simulator"]."&cmd=Start";}	
		if($_POST['cmd'] == 'Restart')			{$url_send = $url."&opensim_select=".$_POST["simulator"]."&cmd=Restart";}
		if($_POST['cmd'] == 'Stop')				{$url_send = $url."&opensim_select=".$_POST["simulator"]."&cmd=Stop";}
		
		if($_POST['cmd'] == 'StartLogin')		{$url_send = $url."&opensim_select=".$_POST["simulator"]."&cmd=StartLogin";}	
		if($_POST['cmd'] == 'StopLogin')		{$url_send = $url."&opensim_select=".$_POST["simulator"]."&cmd=StopLogin";}	
		if($_POST['cmd'] == 'StatusLogin')		{$url_send = $url."&opensim_select=".$_POST["simulator"]."&cmd=StatusLogin";}

		if($_POST['cmd'] == 'Windlight enable')	{$url_send = $url."&opensim_select=".$_POST["simulator"]."&cmd=WindlightEnable";}
		if($_POST['cmd'] == 'Windlight disable'){$url_send = $url."&opensim_select=".$_POST["simulator"]."&cmd=WindlightDisable";}
		if($_POST['cmd'] == 'Windlight load')	{$url_send = $url."&opensim_select=".$_POST["simulator"]."&cmd=WindlightLoad";}
		
		if($_POST['cmd'] == 'Generate Map')		{$url_send = $url."&opensim_select=".$_POST["simulator"]."&cmd=GenerateMap";}
		
		if($_POST['cmd'] == 'Reload Estate')	{$url_send = $url."&opensim_select=".$_POST["simulator"]."&cmd=ReloadEstate";}
		
		if($_POST['cmd'] == 'Alerte General')	{$url_send = $url."&opensim_select=".$_POST["simulator"]."&cmd=Alerte&msg_alert=".urlencode( $_POST["msg_alert"]);}
		
		if($_POST['cmd'] == 'Kick_User')		{$url_send = $url."&opensim_select=".$_POST["simulator"]."&cmd=Kick_User&avatar_name=".urlencode( $_POST["avatar_name"]);}
		if($_POST['cmd'] == 'Appearance_User')	{$url_send = $url."&opensim_select=".$_POST["simulator"]."&cmd=Appearance_User&avatar_name=".urlencode( $_POST["avatar_name"]);}

		if($_POST['cmd'] == 'Estate_Name')		{$url_send = $url."&opensim_select=".$_POST["simulator"]."&cmd=Estate_NameSet&estate_name=".urlencode($_POST["estate_name"]);}
		if($_POST['cmd'] == 'Estate_Owner')		{$url_send = $url."&opensim_select=".$_POST["simulator"]."&cmd=Estate_OwnerSet&estate_owner=".urlencode($_POST["estate_owner"]);}

		if($_POST['cmd'] == 'Region_Selected')	{$url_send = $url."&opensim_select=".$_POST["simulator"]."&cmd=Region_Selected&Region=".urlencode($_POST["region"]);}
	
		//**************************
		// ENVOI COMMANDE PAR API SUR OSMW
		//**************************
		if($_POST['cmd']<>""){
			Url_Api_OSMW($url_send);
			//echo $url_send;
		}	
	}
    //******************************************************
    //  Affichage page principale
    //******************************************************

	echo '<form class="form-inline" method="post" action="">';
	echo '<table class="table-condensed"><tr><td align=left >';

	echo '<button type="submit" class="btn btn-info btn-sm" value="section1" name="section1">';
	echo '<i class="glyphicon glyphicon-modal-window"></i> '.$osmw_menu_sim_section1.'</button> ';
	
	echo '<button type="submit" class="btn btn-info btn-sm" value="section2" name="section2">';
	echo '<i class="glyphicon glyphicon-th"></i> '.$osmw_menu_sim_section2.'</button> ';

	echo '</td></tr></table>';
	echo '</form>';
	
	//################################################
	// AFFICHAGE DES SIMULATEURS DU HOST SELECTIONNE
	//################################################	
	
	$url = INI_Conf_Hosts($_SESSION['opensim_select'], "hosts_dns")."api/?api_key=".INI_Conf_Hosts($_SESSION['opensim_select'], "hosts_api_key")."&cmd=get";
    if (Url_Api_OSMW($url) == "OK"){$ImgMap = "img/on.png";}else{$ImgMap = "img/off.png";}

	$url = INI_Conf_Hosts($_SESSION['opensim_select'], "hosts_dns")."api/?api_key=".INI_Conf_Hosts($_SESSION['opensim_select'], "hosts_api_key")."&cmd_gest=list_simulateurs";
	$SIMULATOR =  Url_Api_OSMW($url);
	$LIST_SIMULATOR = explode(";",$SIMULATOR);
	$NB_SIMU = count($LIST_SIMULATOR)-2;

	if ($LIST_SIMULATOR[0] == "OK")
	{
		$i=$j=0;
		// PAR CHAQUE SIMULATEURS

		for ($i = 1; $i <= $NB_SIMU; $i++) 
		{
			echo '<table  class="table table-hover">';
			echo '<tr class="info">';
			echo '<th>Etat serveur</th>';
			echo '<th>Simulateur</th>';
			echo '<th>Hosts</th>';
			echo '<th>DNS</th>';
			echo '</tr>';
			echo '<tr>';
			echo '<td><img style="height:32px;" class="img-thumbnail" alt="" src="'.$ImgMap.'"></td>';
			echo '<td><h5><b>'.$LIST_SIMULATOR[$i].'</b></h5></td>';
			echo '<td><h5>'.INI_Conf_Hosts($_SESSION['opensim_select'], "hosts_name").'</h5></td>';
			echo '<td><h5>'.INI_Conf_Hosts($_SESSION['opensim_select'], "hosts_dns").'</h5></td>';
			echo '</tr>';	
			
						
			$url = INI_Conf_Hosts($_SESSION['opensim_select'], "hosts_dns")."/api/?api_key=".INI_Conf_Hosts($_SESSION['opensim_select'], "hosts_api_key")."&opensim_select=".$LIST_SIMULATOR[$i]."&cmd_gest=list_regions";
			$REGIONS =  Url_Api_OSMW($url);
			$LIST_REGIONS = explode(";",$REGIONS);
			
			$NB_REGIONS = count($LIST_REGIONS)-2;	
			
			if ($NB_REGIONS  >= 2)
			{
				echo '<tr><td colspan = 4>';
				echo '<form class="form-inline" method="post" action="">';
				echo '<input type="hidden" name="simulator" value="'.$LIST_SIMULATOR[$i].'" >';
				echo '<input type="hidden" name="region" value="root" >';
				echo '<button type="submit" class="btn btn-Dark btn-sm" value="Region_Selected" name="cmd" '.$osmw_btn_select.'>Region ROOT</button></form>';
				echo '</td></tr>';				
			}
			
			// POUR CHAQUE REGIONS
			for ($j = 1; $j <= $NB_REGIONS; $j++) 
			{
				$LIST_REGIONS_MAP = explode("#",$LIST_REGIONS[$j]);

				$ImgMapHttp =  $LIST_REGIONS_MAP[1];
				$ImgMapLocal =  './img/'.$LIST_REGIONS_MAP[0].'.jpg';
					
				if (Test_Url($ImgMapHttp) == false)
				{
				 	$ImgMapLocal = "./img/offline.jpg";
					unlink($ImgMapLocal );
				}
				else
				{
					if(!file_exists($ImgMapLocal)){	copy($ImgMapHttp, $ImgMapLocal);	}
				}
				
				echo '<tr><td colspan = 4>';
				echo '<form class="form-inline" method="post" action="">';
				echo '<input type="hidden" name="simulator" value="'.$LIST_SIMULATOR[$i].'" >';
				echo '<input type="hidden" name="region" value="'.$LIST_REGIONS_MAP[0].'" >';
				echo '<button type="submit" class="btn btn-Dark btn-sm" value="Region_Selected" name="cmd" '.$osmw_btn_select.'>'.$LIST_REGIONS_MAP[0].'</button>' ;
				echo '<img style="height:64px;" class="img-thumbnail" alt="'.$LIST_REGIONS_MAP[0].'" src="'.$ImgMapLocal.'">';					
				echo '</div>';
				echo '</form>';
				echo '</td></tr>';		
			}
			
			

			//################################################
			// Section 1 : 
			//################################################
			if (isset($_POST['section1'])=="section1")
			{
				echo '<tr><td colspan =4>';
				
				echo '<form class="form-inline" method="post" action="">';
				echo '<input type="hidden" name="simulator" value="'.$LIST_SIMULATOR[$i].'" >';
				
				echo '<div class="btn-group" role="group" aria-label="...">';
				echo '<button type="submit" class="btn btn-success btn-sm" value="Start" name="cmd" '.$btnN3.'>';
				echo '<i class="glyphicon glyphicon-play"></i> Start</button>';
				echo '<button type="submit" class="btn btn-danger btn-sm" value="Stop" name="cmd" '.$btnN3.'>';
				echo '<i class="glyphicon glyphicon-stop"></i> Stop</button>';	
				echo '</div> ';

				echo '<div class="btn-group" role="group" aria-label="...">';
				echo '<button type="submit" class="btn btn-success btn-sm" value="StartLogin" name="cmd" '.$btnN2.'>';
				echo '<i class="glyphicon glyphicon-play"></i> Start login</button>';
				echo '<button type="submit" class="btn btn-danger btn-sm" value="StopLogin" name="cmd" '.$btnN2.'>';
				echo '<i class="glyphicon glyphicon-stop"></i> Stop login</button>';	
				echo '<button type="submit" class="btn btn-primary btn-sm" value="StatusLogin" name="cmd" '.$btnN2.'>';
				echo '<i class="glyphicon glyphicon-repeat"></i> Status login</button>';
				echo '</div> ';
		
				echo '</form>';
				echo '</td></tr><tr><td colspan = 2>';
				
				echo '<form class="form-inline" method="post" action="">';
				echo '<input type="hidden" name="simulator" value="'.$LIST_SIMULATOR[$i].'" >';
				echo '<div class="btn-group " role="group" aria-label="..."><div class="input-group col-xs-100">';
				echo '<input type="text" class="form-control" name="msg_alert" placeholder="'.$osmw_label_msg_send.'">';
				echo '<span class="input-group-btn"><button type="submit" class="btn btn-primary" value="Alerte General" name="cmd" '.$btnN2.'><i class="glyphicon glyphicon-bullhorn"></i> '.$osmw_btn_msg_send.'</button></span>';
				echo '</div></div>';
				echo '</form>';
				
				echo '<form class="form-inline" method="post" action="">';
				echo '<input type="hidden" name="simulator" value="'.$LIST_SIMULATOR[$i].'" >';
				echo '<br><div class="btn-group " role="group" aria-label="..."><div class="input-group col-xs-100">';
				echo '<input type="text" class="form-control" name="avatar name" placeholder="avatar name">';
				echo '<span class="input-group-btn"><button type="submit" class="btn btn-danger" value="Kick_User" name="cmd" '.$btnN2.'><i class="glyphicon glyphicon-eye-close"></i> Kick User</button></span>';
				echo '</div></div>';
				echo '</form>';

				echo '</td></tr>';	
				
			}
			//################################################
			// Section 2 : 
			//################################################
			if (isset($_POST['section2'])=="section2")
			{
				echo '<tr><td colspan =4>';
				
				echo '<form class="form-inline" method="post" action="">';
				echo '<input type="hidden" name="simulator" value="'.$LIST_SIMULATOR[$i].'" >';
				
				echo '<div class="btn-group" role="group" aria-label="...">';
				echo '<button type="submit" class="btn btn-danger btn-sm" value="Windlight disable" name="cmd" '.$btnN2.'>';
				echo '<i class="glyphicon glyphicon-stop"></i> Windlight disable </button>';
				echo '<button type="submit" class="btn btn-warning btn-sm" value="Windlight load" name="cmd" '.$btnN2.'>';
				echo '<i class="glyphicon glyphicon-retweet"></i> Windlight load</button>';
				echo '<button type="submit" class="btn btn-success btn-sm" value="Windlight enable" name="cmd" '.$btnN2.'>';
				echo '<i class="glyphicon glyphicon-play"></i> Windlight enable</button>';				
				echo '</div> ';

				echo '<form class="form-inline" method="post" action="">';
				echo '<input type="hidden" name="simulator" value="'.$LIST_SIMULATOR[$i].'" >';
				echo '<div class="btn-group" role="group" aria-label="...">';
				echo '<button type="submit" class="btn btn-primary btn-sm" value="Reload Estate" name="cmd" '.$btnN1.'>';
				echo '<i class="glyphicon glyphicon-picture"></i> Reload Estate</button>';	
				echo '</div> ';

				echo '<div class="btn-group" role="group" aria-label="...">';
				echo '<button type="submit" class="btn btn-success btn-sm" value="Generate Map" name="cmd" '.$btnN1.'>';
				echo '<i class="glyphicon glyphicon-picture"></i> Generate Map</button>';	
				echo '</div>';		
				echo '</form>';
				
				echo '</td></tr><tr><td colspan = 2>';
				
				echo '<form class="form-inline" method="post" action="">';
				echo '<input type="hidden" name="simulator" value="'.$LIST_SIMULATOR[$i].'" >';
				echo '<div class="btn-group " role="group" aria-label="..."><div class="input-group col-xs-100">';
				echo '<input type="text" class="form-control" name="estate_name" placeholder="Estate name">';
				echo '<span class="input-group-btn"><button type="submit" class="btn btn-success" value="Estate_Name" name="cmd" '.$btnN3.'><i class="glyphicon glyphicon-download-alt"></i> '.$osmw_btn_enregistrer.'</button></span>';
				echo '</div></div>';
				echo '</form>';
									
				echo '<form class="form-inline" method="post" action="">';
				echo '<input type="hidden" name="simulator" value="'.$LIST_SIMULATOR[$i].'" >';
				echo '<br><div class="btn-group " role="group" aria-label="..."><div class="input-group col-xs-100">';
				echo '<input type="text" class="form-control" name="estate_owner" placeholder="Estate owner avatar name">';
				echo '<span class="input-group-btn"><button type="submit" class="btn btn-success" value="Estate_Owner" name="cmd" '.$btnN3.'><i class="glyphicon glyphicon-download-alt"></i> '.$osmw_btn_enregistrer.'</button></span>';
				echo '</div></div>';
				echo '</form>';
				
				echo '</td></tr>';	

			}

			
		}
		echo '</table>';	
	}
	


}
else {header('Location: index.php');}
?>
