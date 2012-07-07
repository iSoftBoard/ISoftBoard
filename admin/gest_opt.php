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
	<h1>Gestion des options</h1>
<?php

include('../info_options.php');

echo '<form name="form1" method="post" action="save_opt.php">
<p><input type="text" name="nomduforum"  value="'.htmlentities($nomduforum).'" class="bouton" /> : Le nom de votre forum</p>
			<p><input type="text" name="url" value="'.$adresse.'" class="bouton" /> : Adresse du forum <br /><em>Attention: ne mettez pas l\'adresse du site, l\'adresse du forum est n&eacute;cessaire pour plusieurs actions</em></p>
			<p><input type="text" name="mailadmin" value="'.$mailadmin.'" class="bouton" /> : L\'adresse E-Mail de l\'administrateur du forum</p>
			<p><input type="text" name="smtp" value="'.$smtp.'" class="bouton" /> : adresse d\'un serveur smtp <br /><em>si vous voulez changez celui par d&eacute;faut, autrement LAISSEZ VIDE</em></p>
			<p><input type="text" name="nbsondage" value="'.$nbsondage.'" class="bouton" /> : Le nombre d\'options maximum pour les sondages <br /><em>(1 ou 0 annulent les sondages)</em></p>
			<p><input name="gzip" type="radio" value="true" '.(($gzip) ? ' checked=" checked"' : '' ).'> Oui <input name="gzip" type="radio" value="false" '.((!$gzip) ? ' checked=" checked"' : '' ).'> Non || Activer ou non la compression gzip<br /><em>Ceci permet de comprimer les pages avant de les envoyer, v&eacute;rifiez la disponibilit&eacute; de ce service auprès de votre h&eacute;bergeur. Il n\'est pas n&eacute;cessaire de l\'activer</em></p>
			<p><input name="autmodpseudo" type="radio" value="true" '.(($autmodpseudo) ? ' checked=" checked"' : '' ).'/> Oui <input name="autmodpseudo" type="radio" value="false" '.((!$autmodpseudo) ? ' checked=" checked"' : '' ).' /> Non || Autoriser ou non les membres &agrave; modifier eux meme leur pseudo</p>
			<p><input name="afflistdelauto" type="radio" value="true" '.(($afflistdelauto) ? ' checked=" checked"' : '' ).' /> Oui <input name="afflistdelauto" type="radio" value="false" '.((!$afflistdelauto) ? ' checked=" checked"' : '' ).' /> Non || Autoriser ou non les membres &agrave; se masquer en ligne</p>
			<p><input name="autorisationsign" type="radio" value="true" '.(($autorisationsign) ? ' checked=" checked"' : '' ).' /> Oui <input name="autorisationsign" type="radio" value="false" '.((!$autorisationsign) ? ' checked=" checked"' : '' ).' /> Non || Autoriser ou non les membres &agrave; utiliser une signature</p>
			<p><input name="bbcodesign" type="radio" value="true" '.(($bbcodesign) ? ' checked=" checked"' : '' ).' /> Oui <input name="bbcodesign" type="radio" value="false" '.((!$bbcodesign) ? ' checked=" checked"' : '' ).' /> Non || Autoriser ou non les membres &agrave; utiliser du bbcode dans leur signature</p>
			<p><input name="ipaff" type="radio" value="true" '.(($ipaff) ? ' checked=" checked"' : '' ).' /> Oui <input name="ipaff" type="radio" value="false" '.((!$ipaff) ? ' checked=" checked"' : '' ).' /> Non || Afficher ou non l\'adresse ip d\'un posteur pour les administrateurs</p>
			<p><input name="affreprapide" type="radio" value="true" '.(($affreprapide) ? ' checked=" checked"' : '' ).' /> Oui <input name="affreprapide" type="radio" value="false" '.((!$affreprapide) ? ' checked=" checked"' : '' ).' /> Non || Afficher ou non un formulaire de r&eacute;ponse rapide en bas de discussion</p>
			<p><input name="mailconf" type="radio" value="true" '.(($mailconf) ? ' checked=" checked"' : '' ).' /> Oui <input name="mailconf" type="radio" value="false" '.((!$mailconf) ? ' checked=" checked"' : '' ).' /> Non || Verifier ou pas l\'adresse mail du membre avec un E-Mail de confirmation</p>
			<p><input name="cache_forum" type="radio" value="true" '.(($cache_forum) ? ' checked=" checked"' : '' ).' /> Oui <input name="cache_forum" type="radio" value="false" '.((!$cache_forum) ? ' checked=" checked"' : '' ).' /> Non || Masquer ou non les forums et les catégories pour lequelles on a aucun accès<br /><em>Avertissement, cette option coute une requete supplémentaire (pas pour les admins/modos), mais elle peut être utile en cas de forum fort chargé avec beaucoup de restrictions, il vaut parfois mieu une requete en plus, qu\'un contenu énorme.<br />A vous de trouver le juste milieu</em></p>
			<p><input name="lockforum" type="radio" value="true" '.(($lockforum) ? ' checked=" checked"' : '' ).' /> Oui <input name="lockforum" type="radio" value="false" '.((!$lockforum) ? ' checked=" checked"' : '' ).' /> Non || Verrouillé ou non le forum (le forum est indisponible s\'il est verrouillé)</p>
					<p><strong>Message de fermeture (attention : code html autorisé)</strong></p><p><textarea name="message_de_lock" cols="" rows="" class="tbouton">'.$message_de_lock.'</textarea></p>
			<p><input type="text" name="lmax" value="'.$lmax.'" class="bouton" /> : La largeur maximale des avatars</p>
			<p><input type="text" name="hmax" value="'.$hmax.'" class="bouton" /> : La hauteur maximale des avatars</p>
			<p><input type="text" name="pmax" value="'.$pmax.'" class="bouton" /> : Le poids maximal des avatars (en Octest : 20480 => 20Ko)</p>
			<p><input type="text" name="tmpfreepost" value="'.$tmpfreepost.'" class="bouton" /> : Le temps minimal avant lequel un membre ne peut reposter (en secondes)</p>
			<p><input type="text" name="membreparpage" value="'.$membreparpage.'" class="bouton" /> : Le nombre de membres affich&eacute;s par page dans la liste des membres</p>
			<p><input type="text" name="postparpage" value="'.$postparpage.'" class="bouton" /> : Le nombre de titres affich&eacute;s par page dans le forum</p>
			<p><input type="text" name="postparpageaff" value="'.$postparpageaff.'" class="bouton" /> : Le nombre de messages affich&eacute;s par page dans la r&eacute;ponse d\'un post </p>
			<p><input type="submit" name="Submit" value="Enregistrer les options" class="bouton" /></p></form>';

?>
</div>
</body>
</html>