<?php
session_start();

$phrase_erreur = '<p>/!\ Seuls les administrateurs peuvent avoir accès à l\'administration.</p><p>Si vous êtes administrateur, passez pr&eacute;alablement sur le forum pour vous connecter</p>';

if(empty($_SESSION['idlog'])) exit($phrase_erreur);

include('../info_bdd.php');

$db = mysql_connect($host,$user,$mdpbdd)  or die('<p>/!\ Impossible de se connecter au serveur mysql, v&eacute;rifiez les options de connexion à la base de donn&eacute;e</p>');
mysql_select_db($bdd,$db)  or die('<p>/!\ Impossible de se connecter à la base de donn&eacute;e, v&eacute;rifiez qu\'elle existe</p>');

$sql = 'SELECT rang FROM '.$prefixtable.'membres WHERE id = "'.intval($_SESSION['idlog']).'"  AND valid = "1" AND rang= "2"';
$req = mysql_query($sql);
if(mysql_num_rows($req) == 0) exit($phrase_erreur); 

$quotes_gpc = get_magic_quotes_gpc();

ini_set('magic_quotes_runtime', 0);

function add_gpc ($chaine) {

	global $quotes_gpc;
	
	if($quotes_gpc) return $chaine;
	else return addslashes($chaine);

}

function strip_gpc ($chaine) {

	global $quotes_gpc;
	
	if($quotes_gpc) return stripslashes($chaine);
	else return $chaine;

}

?>