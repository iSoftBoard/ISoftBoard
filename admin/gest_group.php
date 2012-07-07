<?php include_once('log.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SoftBB - Administration</title>
<link rel="stylesheet" href="./install.css" type="text/css" />
<script language="JavaScript" type="text/JavaScript">
function decision(message, url)
{
	if(confirm(message)) location.href = url;
}
</script>
</head>
<body>
<div id="titre"><img src="./install.jpg" alt="SoftBB - Administration" /></div>
<div id="install">
	<div id="right">Administration du forum</div>
	<div class="clear"></div>
	
	<p><a href="index.php">Atteindre l'index de l'administration du forum.</a></p>
	<h1>Gestion des goupes </h1>
	<p>Vous &ecirc;tes dans la partie gestion des groupes, ici, vous allez pouvoir cr&eacute;er des groupes et les supprimers, pour ajouter un membres &agrave; un groupes, rendez-vous dans la section groupe du forum </p><p><strong>Liste de vos groupes</strong></p>
	<?php
  	
	function addslashes2 ($chaine) {
	
	return str_replace("'","\'",$chaine);
	
	}
	
	$sql = 'SELECT nom,id FROM '.$prefixtable.'groupe';
	$req = mysql_query($sql);
	
	
	if(mysql_num_rows($req) == 0) echo '<p>Vous n\'avez actuellement pas définit de groupe</p>';
	else {
		
		while($data = mysql_fetch_assoc($req)) echo '. '.htmlentities($data['nom']).' - <a href="#" onclick="decision(\'Voulez-vous vraiment supprimer \n ce groupe définitivement?\n('.addslashes2(htmlentities($data['nom'])).')\',\'delgroupe.php?idg='.intval($data['id']).'\')">[Supprimer ce groupe]</a><br />';
	}
	
	echo'<p><strong>Ajouter un groupe</strong></p>
		<form name="form1" method="post" action="addgroupe.php">
			<input name="groupe" type="text" class="bouton" size="30" maxlength="64"> 
			<input type="submit" name="Submit" class="bouton" value="Cr&eacute;er ce groupe">
		</form>';
	?>
</div>
</body>
</html>