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
	<h1>Gestion des &eacute;moticons</h1>
	<p>Voici le liste actuelle de vos &eacute;moticons, vous pouvez en ajouter et en supprimer, une fois que cela est fait, cliquez sur enregistrer les modifications. Il est inutile de sauvegarder apres chaque ajout, faites toutes vos modifications et seulement ensuite, enregistrez le tous.</p>
	<p>Les &eacute;motes supprim&eacute;s ne sertont plus disponibles dans les messages, ceci est r&eacute;troactif sur les anciens messages post&eacute;s.  Pour supprimer un &eacute;mote, il suffit de le d&eacute;cocher. Vous pouvez toujours revenir &agrave; la derni&egrave;re sauvegarde en cliquant sur le bouton &quot;Annuler les modifications&quot;. (Les nouveaux ajoutes seront supprim&eacute;s) </p>
	<?php
	
		function addslashes2 ($chaine) {
	
	return str_replace("'","\'",str_replace("\\","\\\\",$chaine));
	
	}

include('../info_emote.php');

echo '<form name="form1" method="post" action="">';

if(!isset($_POST['ajout']) && !isset($_POST['reg']) || isset($_POST['re'])) {

	for($i=0;$i<$emoticonnb;$i++) echo '<p><input name="'.$i.'" type="checkbox" value="true" checked /> <input class="bouton" name="emote'.$i.'" type="text" value="'.htmlentities($emoticonc[$i]).'" /> <input class="bouton" name="emotei'.$i.'" type="text" value="'.htmlentities($emoticonv[$i]).'" /> <img src="../'.htmlentities($emoticonv[$i]).'" alt="emote'.$i.'"></p>'."\n";
	echo '<input name="nb" type="hidden" value="'.$i.'" /><h1>Ajouter un émoticon</h1><p>Symbole : <input class="bouton" name="emote'.($i).'" type="text" value="" /><br />Adresse : <input class="bouton" name="emotei'.($i).'" type="text" value="" /> (Relative à l\'index)</p><input class="bouton" type="submit" name="ajout" value="Ajouter" /> <input class="bouton" type="submit" name="reg" value="Enregistrer" /> <input class="bouton" type="submit" name="re" value="Annuler les modifications" /></form>';
	
}

elseif(isset($_POST['ajout'])) {

	$nb = 0;

	for($i=0;$i<=$_POST['nb'];$i++) {
	if( (isset($_POST[$i]) && $_POST[$i] == 'true') || ($_POST['nb'] == ($i) && !empty($_POST['emote'.$i]) )) {
		
		echo '<p><input name="'.$nb.'" type="checkbox" value="true" checked /> <input class="bouton" name="emote'.$nb.'" type="text" value="'.htmlentities(strip_gpc($_POST['emote'.$i])).'" /> <input class="bouton" name="emotei'.$nb.'" type="text" value="'.htmlentities(strip_gpc($_POST['emotei'.$i])).'" /> <img src="../'.htmlentities(strip_gpc($_POST['emotei'.$i])).'" alt="emote'.$nb.'"></p>'."\n";
		$nb++;
		}
	}
	echo '<input name="nb" type="hidden" value="'.$nb.'" /><h1>Ajouter un émoticon</h1><p>Symbole : <input class="bouton" name="emote'.($nb).'" type="text" value="" /><br />Adresse : <input class="bouton" name="emotei'.($nb).'" type="text" value="" /> (Relative à l\'index)</p><input class="bouton" type="submit" name="ajout" value="Ajouter" /> <input class="bouton" type="submit" name="reg" value="Enregistrer" /> <input class="bouton" type="submit" name="re" value="Annuler les modifications" /></form>';


}



else {

	$nb = 0;
	$arr = '$emoticonc = array(';

	for($i=0;$i<=$_POST['nb'];$i++) {
		
		if( (isset($_POST[$i]) && $_POST[$i] == 'true') || ($_POST['nb'] == ($i) && !empty($_POST['emote'.$i]) )) {
			$arr .= '\''.addslashes2(strip_gpc($_POST['emote'.$i])).'\',';
			$nb++;
		}
	}
	
	$arr = substr($arr,0,strlen($arr)-1).');';
	
	$nb = 0;
	$arri = '$emoticonv = array(';

	for($i=0;$i<=$_POST['nb'];$i++) {
		
		if( (isset($_POST[$i]) && $_POST[$i] == 'true') || ($_POST['nb'] == ($i) && !empty($_POST['emotei'.$i]) )) {
			$arri .= '\''.addslashes2(strip_gpc($_POST['emotei'.$i])).'\',';
			$nb++;
		}
	}
	
	$arri = substr($arri,0,strlen($arri)-1).');';
	
	$fp = fopen('../info_emote.php','w+');
	fseek($fp,0);
	fputs($fp,'<?php
	
'.$arri.'

'.$arr.'
	
$emoticonnb = count($emoticonv); 

?>');
	fclose($fp);
	
	echo '<h1>Enregistrement confirmé !</h1>Vos informations ont bien étées enregistrées<p><a href="index.php">Atteindre l\'index de l\'administration du forum.</a></p>';

}



?>
    
</div>
</body>
</html>