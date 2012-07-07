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
	<h1>Configurer vos forums</h1>
	<?php

	echo '
	<table width="700" border="0" cellspacing="0" cellpadding="0">';
	
	$sql = 'SELECT * FROM '.$prefixtable.'forum ORDER BY position';
	$req = mysql_query($sql);
	mysql_close();
	while($data = mysql_fetch_assoc($req))
	{
		if($data['fatt'] == 0)
		{
			echo '
		<tr>
			<td height="29" colspan="3" class="titreforumdeb" style="padding-left:8px"><a name="'.$data['id'].'"></a>'.htmlentities($data['nom']).'</td>
			<td width="90" class="titreforum"><div align="center">Edition</div></td>
			<td width="90" class="titreforum"><div align="center">Position</div></td>
			<td width="90" class="titreforum"><div align="center">Autorisation</div></td>
		</tr>
			';
			$posrelat = 1;
			$posmax = $data['nbsf'];
			//$nomduforum = $data['nom'];
			//$posduforum = $data['position'];
			//$posduforumr = $data['positionf'];
			$ida = $data['id'];
		}
		else
		{
			echo'
		<tr>
			<td class="cadredeb" style="padding:10px"><a name="'.$data['id'].'"></a><b>'.htmlentities($data['nom']).'</b><br />'.htmlentities($data['description']).'</td>
			<td width="45" align="center" class="cadre">'.$data['nbsujet'].'</td>
			<td width="45" align="center" class="cadre">'.$data['nbmessage'].'</td>
			<td align="center" class="cadre"><a href="#" onclick="decision(\'Voulez-vous vraiment supprimer \n ce forum définitivement?\',\'del.php?id='.$data['id'].'\')">Supprimer</a>, <a href="#" onclick="decision(\'Voulez-vous vraiment vider \n ce forum définitivement?\',\'vidage.php?id='.$data['id'].'\')">Vider</a>, <a href="form_edit_forum.php?id='.$data['id'].'">Editer</a></td>
			<td align="center" class="cadre"><a href="possforum.php?id='.$data['id'].'&act=up">Monter,</a> <a href="possforum.php?id='.$data['id'].'&act=down">Descendre,</a></td>
			<td align="center" class="cadre"><a href="form_edit_aut.php?id='.$data['id'].'">Gerer</a></td>
		</tr>
			';
			$posrelat++;
		}
		if($posrelat-1 == $posmax || $posmax == 0)
		{
			echo '
		<tr>
			<td height="29" colspan="6" class="cadredeb" style="padding:5px"><a href="#" onclick="decision(\'Voulez-vous vraiment supprimer \n cette catégorie (+forums internes) définitivement?\',\'delforum.php?id='.$ida.'\')">Supprimer</a> - <a href="invforum.php?id='.$ida.'&act=up">Monter</a> - <a href="invforum.php?id='.$ida.'&act=down">Descendre</a> - <a href="form_edit_cat.php?id='.$ida.'">Modifier</a> - <a href="form_add_forum.php?ida='.$ida.'">Ajouter un forum</a></td>
		</tr>
		<tr style="height:20px">
			<td>&nbsp;</td>
		</tr>
			';
		}
	}
	echo '
</table>
<table class="texte_base_gras" width="700" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height="29" class="titreforumdeb" style="padding-left:8px">Ajouter une catégorie générale</td>
	</tr>
	<tr>
		<td class="cadredeb" style="padding:12px"><form action="adforum.php" method="post" name="adcat">Ajouter une catégorie : <input name="nom" class="bouton" type="text" value="Nom de la catégorie"  maxlength="128" size="35" onFocus="if(value == \'Nom de la catégorie\') value=\'\';" /> <input type="submit" class="bouton" name="Submit" value="Ajouter cette catégorie" /></form></td>
	</tr>
</table>
';

mysql_free_result($req);
?>
</div>
</body>
</html>