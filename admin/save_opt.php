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
	
	<h1>Enregistrement r&eacute;alis&eacute; avec succ&egrave;s </h1>
	<?php
	
	function addslashes2 ($chaine) {
	
	return str_replace("'","\'",str_replace("\\","\\\\",$chaine));
	
	}
	
	function verif_true_false ( $chaine ) {

	if( $chaine == 'true' ) return 'true';
	else if( $chaine == 'false' ) return 'false';
	else return 'false'; 

}
	
$insert =  '<?php

$nomduforum = \''.addslashes2(strip_gpc($_POST['nomduforum'])).'\'; 
$mailadmin = \''.addslashes2(strip_gpc($_POST['mailadmin'])).'\'; 
$smtp = \''.addslashes2(strip_gpc($_POST['smtp'])).'\'; 
$nbsondage = '.intval($_POST['nbsondage']).'; 

$mailconf = '.verif_true_false($_POST['mailconf']).';
$gzip = '.verif_true_false($_POST['gzip']).';
$autmodpseudo = '.verif_true_false($_POST['autmodpseudo']).';
$afflistdelauto = '.verif_true_false($_POST['afflistdelauto']).';
$autorisationsign = '.verif_true_false($_POST['autorisationsign']).';
$bbcodesign = '.verif_true_false($_POST['bbcodesign']).';
$ipaff = '.verif_true_false($_POST['ipaff']).';
$affreprapide = '.verif_true_false($_POST['affreprapide']).'; 

$lmax = '.intval($_POST['lmax']).'; 
$hmax = '.intval($_POST['hmax']).'; 
$pmax = '.intval($_POST['pmax']).'; 
$tmpfreepost = '.intval($_POST['tmpfreepost']).'; 

$membreparpage = '.intval($_POST['membreparpage']).'; 
$postparpage = '.intval($_POST['postparpage']).'; 
$postparpageaff = '.intval($_POST['postparpageaff']).';
$adresse = \''.addslashes2(strip_gpc($_POST['url'])).'\';

$lockforum = '.verif_true_false($_POST['lockforum']).';
$message_de_lock = \''.addslashes2(strip_gpc($_POST['message_de_lock'])).'\';
$upavatar = "1";
$cache_forum = '.verif_true_false($_POST['cache_forum']).';
 
?>';

$fp = fopen('../info_options.php','w+');
fseek($fp,0);
fputs($fp,$insert);
fclose($fp);
	
?>
	
	<p>Les options sont maintenant modifi&eacute;es </p>
	<p><a href="gest_opt.php">Atteindre la configuration des options </a></p>
</div>
</body>
</html>