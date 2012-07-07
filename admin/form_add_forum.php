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
	<h1>Ajout d'un forum</h1>
	<?php
  	$sql = 'SELECT nom FROM '.$prefixtable.'forum WHERE id = '.intval($_GET['ida']).' AND fatt = 0';
	$req = mysql_query($sql);
	$data = mysql_fetch_assoc($req);
	if(mysql_num_rows($req) == 0) echo '<p>Il semble que la catégorie dans laquelle vous voulez ajouter un forum n\'existe pas/plus</p>';
	else echo '
	<form name="form1" method="post" action="addforum.php?id='.$_GET['ida'].'">
		<p>Vous allez ajouter un forum dans la cat&eacute;gorie : <strong>'.htmlentities($data['nom']).'</strong><br />Un forum dans une cat&eacute;gorie y sera attach&eacute; et des personnes pouront y d&eacute;poser des messages.</p>
		<p>Entrez les informations que vous désirez dans le formulaire suivant et validez le pour ajouter un forum.<br />Ces informations seront toujours modifiables par la suite si besoin est.</p>
		<p><input name="nom" type="text" class="bouton" value="Nom du forum" size="20" maxlength="128" onFocus="if(value==\'Nom du forum\') value=\'\';" /></p>
		<p><textarea name="description" cols="" rows="" class="tbouton" onFocus="if(value==\'Description du forum\') value=\'\';">Description du forum</textarea></p>
		<input type="submit" name="Submit" value="Ajouter ce forum" class="bouton" />
	</form>
		';
	?>
</div>
</body>
</html>