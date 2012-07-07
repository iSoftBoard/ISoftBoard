<?php include_once('log.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SoftBB - Administration</title>
<link rel="stylesheet" href="./install.css" type="text/css" />
</head>
<body>
<div id="titre"><img src="./install.jpg" alt="SoftBB - Administration" /></div>
<div id="install">
	<div id="right">Administration du forum</div>
	<div class="clear"></div>
	
	<p><a href="index.php">Atteindre l'index de l'administration du forum.</a></p>
	<h1>Modification des autorisations d'un forum</h1>
	<?php
	$tgroupe = array();
	$tidg = array();
	$increm = 0;
	$sql = 'SELECT * FROM '.$prefixtable.'groupe ORDER BY id DESC';
	$req = mysql_query($sql);
	while($data = mysql_fetch_assoc($req))
	{
		$tgroupe[$increm] = $data['nom'];
		$tidg[$increm] = $data['id'];
		$increm++;
	}

	$sql = 'SELECT groupe,m,v,mg,nom FROM '.$prefixtable.'forum WHERE id = '.intval($_GET['id']).' AND fatt != 0';
	$req = mysql_query($sql);
	$data = mysql_fetch_assoc($req);
	if(mysql_num_rows($req) == 0) echo '<p>Il semble que le forum que vous voulez modifier n\'existe pas/plus</p>';
	else echo '
	<p>Vous allez modifier les autorisations du forum suivant : <strong>'.htmlentities($data['nom']).'</strong><br />L\'autorisation sert &agrave; restreindre l\'acces au forum pour le type de personne s&eacute;lectionn&eacute;.</p>
	<form name="form1" method="post" action="auto.php?id='.intval($_GET['id']).'">
		<p>S&eacute;lectionnez le type d\'autorisation que vous souhaitez pour ce forum.</p>
		<p>
			<select name="rang">
				<option value="0"'; if($data['groupe'] == 0) { echo ' selected ';} echo'>Tous les visiteurs</option>
				<option value="-2"'; if($data['groupe'] == -2) { echo ' selected ';} echo'>Membres seulement</option>
				<option value="-1"'; if($data['groupe'] == -1) { echo ' selected ';} echo'>Verrouillage (Anti-post)</option>
				<option value="-3"'; if($data['groupe'] == -3) { echo ' selected ';} echo'>Surverrouillage (total)</option>
				<option value="-4"'; if($data['groupe'] == -4) { echo ' selected ';} echo'>Personnalis&eacute; (pas de memb group)</option>
		';
	for($po=0;$po<$increm;$po++)
	{
  		echo'<option value="'.$tidg[$po].'"'; 
		if($data['groupe'] == $tidg[$po]) 
  		{ 
  			echo ' selected ';
  		} 
		echo'>'.htmlentities($tgroupe[$po]).'</option>';
	}
	echo'
			</select>
		</p>
		
		<p>S&eacute;lectionnez une option pour les <strong>visiteurs</strong> (Personnalis&eacute; ou groupe seulement)</p>
		
		<p>
			<select name="v" class="sbouton">
				<option value="0" '; if($data['v'] == 0) { echo ' selected ';} echo' class="red">Refus&eacute;</option>
				<option value="1" '; if($data['v'] == 1) { echo ' selected ';} echo' class="modo">Visite simple</option>
			</select>
		</p>
		
		<p>S&eacute;lectionnez une option pour les <strong>membres</strong> (Personnalis&eacute; ou groupe seulement)</p>
		
		<p>
			<select name="m" class="sbouton">
				<option value="0" '; if($data['m'] == 0) { echo ' selected ';} echo' class="red">Refus&eacute;</option>
				<option value="1" '; if($data['m'] == 1) { echo ' selected ';} echo' class="modo">Visite simple</option>
				<option value="2" '; if($data['m'] == 2) { echo ' selected ';} echo' class="admin">Poster, sans r&eacute;pondre</option>
				<option value="3" '; if($data['m'] == 3) { echo ' selected ';} echo' class="admin">Repondre, sans poster</option>
				<option value="4" '; if($data['m'] == 4) { echo ' selected ';} echo' class="admin">Poster et r&eacute;pondre</option>
			</select>
		</p>
		
		<p>S&eacute;lectionnez une option pour les <strong>membres du groupe</strong> (groupe seulement) : N\'oubliez pas que les chefs de groupes sont consider&eacute;s comme des mod&eacute;rateurs pour le forum attach&eacute; &agrave; ce groupe</p>
		
		<p>
			<select name="mg" class="sbouton">
				<option value="0" '; if($data['mg'] == 0) { echo ' selected ';} echo' class="red">Refus&eacute;</option>
				<option value="1" '; if($data['mg'] == 1) { echo ' selected ';} echo' class="modo">Visite simple</option>
				<option value="2" '; if($data['mg'] == 2) { echo ' selected ';} echo' class="admin">Poster, sans r&eacute;pondre</option>
				<option value="3" '; if($data['mg'] == 3) { echo ' selected ';} echo' class="admin">Repondre, sans poster</option>
				<option value="4" '; if($data['mg'] == 4) { echo ' selected ';} echo' class="admin">Poster et r&eacute;pondre</option>
			</select>
		</p>
		
		<p><input type="submit" name="Submit" value="Modifier ce forum" class="bouton" /></p>
	</form>
	
	<p>Pour vous aider, voici un petit descriptif des choix que vous pouvez faire :<br /><strong>Tous les visiteurs</strong> : signifie que tous ont acc&egrave;s, les visiteurs visitent et les membres postent &agrave; leur guise.<br /><strong>Membres seulement</strong> : signifie la m&ecirc;me chose que pr&eacute;c&eacute;dement, &agrave; part le fait que les visiteurs sont refus&eacute;s.<br /><strong>Verouillage</strong> : Signifie que tout le monde peut entrer, mais seuls les admins et modos peuvent poster.<br /><strong>Surverrouillage</strong> : Seuls les admins et modos peuvent avoir accès.<br /><strong>Personnalis&eacute;</strong> : Si s&eacute;lectionnez, cette option est combin&eacute;e avec les autres listes, elle permet de choisir une option particulière pour les visiteurs et membres s&eacute;par&eacute;ment.<br /><strong>Groupe</strong> : (facultatif)Le reste se trouve être les groupes que vous avez cr&eacute;&eacute;s, ils sont aussi r&eacute;gits par les listes qui suivent. L\'avantage &eacute;tant d\'avoir en plus une option pour les membres du groupe</p>
	';
  	?>
</div>
</body>
</html>