<?php
/***************************************************************************
 *
 *   SoftBB - Forum de discussion 
 *   Version : 0.1
 *
 *   copyright            : (C) 2005 J�r�my Dombier [Belgium]
 *   email                : satapi@gmail.com
 *   site-web             : http://softbb.be/
 *
 *   Ce programme est un logiciel libre ; vous pouvez le redistribuer et/ou 
 *   le modifier au titre des clauses de la Licence Publique G�n�rale GNU, 
 *   telle que publi�e par la Free Software Foundation ; soit la version 2 de 
 *   la Licence, ou (� votre discr�tion) une version ult�rieure quelconque. 
 *   Ce programme est distribu� dans l'espoir qu'il sera utile, mais 
 *   SANS AUCUNE GARANTIE ; sans m�me une garantie implicite de COMMERCIABILITE 
 *   ou DE CONFORMITE A UNE UTILISATION PARTICULIERE. Voir la Licence Publique 
 *   G�n�rale GNU pour plus de d�tails. Vous devriez avoir re�u un exemplaire 
 *   de la Licence Publique G�n�rale GNU avec ce programme ; si ce n'est pas le 
 *   cas, �crivez � la Free Software Foundation Inc., 
 *   51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 ***************************************************************************/
 
if(!defined('IN_SOFTBB')) exit('Not in SoftBB');
  
if(isset($_POST['pseudo']))
{
	
	$pseudoreg = trim($_POST['pseudo']);
	$mdpt = trim($_POST['mdp']);
	$mdp = md5($mdpt);
	$mdpc = md5(trim($_POST['mdpc']));
	$mail = trim($_POST['mail']);
	
	$sql = 'SELECT pseudo,mail FROM '.$prefixtable.'membres WHERE pseudo = "'.add_gpc($pseudoreg).'" OR mail = "'.add_gpc($mail).'"';
	$req = mysql_query($sql); $requse++;
	
	while($data = mysql_fetch_assoc($req))
	{
		if(strip_gpc(strtolower($pseudoreg)) == strtolower($data['pseudo'])){ $p = 1;}
		if($mail == $data['mail']) $m = 1;
	}
	
	mysql_free_result($req);

	if(empty($pseudoreg)) $p = 2;
	if(empty($mdpt)) $mp = 2;
	
	if(!ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.'@'.'[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.'[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $mail)) $m = 4;
	
	elseif($mdpc != $mdp) $mp = 1;
	
	if(!isset($p)) $p = 0;
	if(!isset($mp)) $mp = 0;
	if(!isset($m)) $m = 0;

	if(strlen($mdpt) < 6)  $mp = 9;
	if(preg_match('!('.strip_gpc($pseudoreg).')+!',strip_gpc($mdpt)))  $mp = 10;
	
}

if(!isset($p)) $p = 3;
if(!isset($mp)) $mp = 3;
if(!isset($m)) $m = 3;

if(isset($_POST['condok']) && $_POST['condok'] == "true") $cond = true;
else $cond = false;

// ICI, commence la fin, si tout est bon
if($p == 0 && $mp == 0 && $m == 0 && $cond == true)
{

	$pass = "";
	$chaine = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	srand((double)microtime()*1000000);
	
	for($ct=0; $ct<8; $ct++) $pass .= $chaine{rand()%strlen($chaine)};
	
	if(!empty($smtp))ini_set("SMTP","$smtp");
	
	$headers = "To: $pseudoreg <$mail>\r\n";
	$headers .= "From: $nomduforum <$mailadmin>\r\n";
	
	if($mailconf)
	{
		$sql = 'INSERT INTO '.$prefixtable.'membres (`pseudo`, `mdp`, `mail`, `nbpost`, `valid`, `temps`, `tempspost`, `rang`, `avatar`, `localisation`, `www`, `mp`, `co`, `gmt`, `he`, `sign`, `signaff`, `rangspec`, `afflist`) VALUES("'.add_gpc($pseudoreg).'","'.$mdpc.'","'.add_gpc($mail).'","0","0","'.time().'",0,0,"","","",0,0,0,0,0,0,0,0)';
		
		$req = mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error());  $requse++;
		$iddumembrepourbdd = mysql_insert_id();

		$sql = 'INSERT INTO '.$prefixtable.'membresvalid VALUES("'.$iddumembrepourbdd.'","'.$pass.'")';
		$req = mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error());  $requse++;
		mysql_close();
		
		$mess = 'Vous vous etes enregistr� avec succes sur '.$adresse.'
		pseudo : '.strip_gpc($pseudoreg).'
		mot de passe : '.strip_gpc($mdpt).'
		Mais vous devez tout de meme confirmer votre inscription en cliquant sur ce lien
		'.$adresse.'confirm.php?pass='.strip_gpc($pass).'&pseudo='.$iddumembrepourbdd.'
		Si vous disposez d\'un compte hotmail(ou autres ...), la validation peut �chouer en raison d\'une manipulation du serveur hotmail, dans ce cas, recopiez le lien dans l\'emplacement pr�vu � cet effet dans votre navigateur.';
		
		mail($mail, 'Confirmation d\'inscription - '.$nomduforum, $mess, $headers);
		
		echo '
<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr align="center">
			<td height="29" class="titreforum">Enregistrement</td>
		</tr>
		<tr>
			<td align="center" class="cadre_clair" style="padding:30px">Vous vous &ecirc;tes enregistr&eacute; avec succ&egrave;s,<br />Vous allez recevoir un E-Mail pour confirmer votre inscription. Si vous disposez d\'un compte hotmail, le mail de validation sera peut &ecirc;tre dans le dossier Spam</td>
		</tr>
</table>
		';
	}
	else
	{
		$sql = 'INSERT INTO '.$prefixtable.'membres (`pseudo`, `mdp`, `mail`, `nbpost`, `valid`, `temps`, `tempspost`, `rang`, `avatar`, `localisation`, `www`, `mp`, `co`, `gmt`, `he`, `sign`, `signaff`, `rangspec`, `afflist`) VALUES("'.add_gpc($pseudoreg).'","'.$mdpc.'","'.add_gpc($mail).'","0","1","'.time().'",0,0,"","","",0,0,0,0,0,0,0,0)';
		$req = mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error());  $requse++;
		mysql_close();
		echo '
<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr align="center">
		<td height="29" class="titreforum">Enregistrement</td>
	</tr>
	<tr>
		<td align="center" class="cadre_clair" style="padding:30px">Vous vous &ecirc;tes enregistr&eacute; avec succ&egrave;s,<br />Vous pouvez vous connecter</td>
	</tr>
</table>';
	}
}
// ICI, commence le forulaire vide
else
{	 
	echo'
<form name="form1" method="post" action="index.php?page=reg">
<table class="texte_base_gras" width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="cadre1_bas" style="padding:10px">'; 
			if($p == 1) echo 'Ce pseudonyme est d&eacute;j&agrave; utilis&eacute; par un membre.<br />';
			if($m == 1) echo 'Cette adresse mail est d&eacute;j&agrave; utilis&eacute;e par un membre.<br />';
			if($mp == 1) echo 'Vous n\'avez pas entrez deux fois le meme mot de passe.<br />';
			if($p == 2) echo 'Ce pseudonyme est compos&eacute; uniquement d\'espaces.<br />';
			if($mp == 2) echo 'Ce mot de passe est compos&eacute; uniquement d\'espaces.<br />';	
			if($cond == false) echo 'Vous ne pouvez vous inscrire si vous n\'acceptez pas les conditions d\'utilisation.<br />';	
			if($m == 4) echo 'Cette adresse E-mail n\'est pas dans un format valide.<br />';
			if($mp == 9) echo 'Pour votre s�curit� et la notre, utilisez un mot de passe avec minimum 6 caract�res<br />';
			if($mp == 10) echo 'Pour votre s�curit� et la notre, utilisez un mot de passe ne contenant pas votre pseudo<br />';
			echo 'Les champs marqu�s d\'un (*) sont obligatoires.
		</td>
	</tr>
</table>

<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr align="center">
		<td height="29" class="titreforum">Enregistrement sur le forum</td>
	</tr>
</table>
          
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="texte_base_gras">
	<tr>
		<td width="30%" class="cadre_clair" style="padding:4px">Nom d\'utilisateur* : </td>
		<td class="cadre_clair" style="padding:4px">
			<input name="pseudo" type="text" id="pseudo"'; 
			  if(isset($_POST['pseudo'])  && $p == 0){ echo'value="'.strip_gpc(htmlentities($_POST['pseudo'])).'" class="bouton"'; } elseif(isset($_POST['pseudo'])) { echo'value="'.strip_gpc(htmlentities($_POST['pseudo'])).'" class="boutonb"'; } else { echo'class="bouton"'; } echo 'size="32" maxlength="20" />
		</td>
	</tr>
	<tr>
		<td width="30%" class="cadre_clair" style="padding:4px">Mot de passe* : </td>
		<td class="cadre_clair" style="padding:4px"><input name="mdp" type="password" class="bouton" id="mdp" size="32" maxlength="64" /> (espaces retir&eacute;s)</td>
	</tr>
	<tr>
		<td width="30%" class="cadre_clair" style="padding:4px">Mot de passe* :</td>
		<td class="cadre_clair" style="padding:4px"><input name="mdpc" type="password" class="bouton" id="mdpc" size="32" maxlength="64" /> (confirmation)</td>
	</tr>
	<tr>
		<td width="30%" class="cadre_clair" style="padding:4px">E-mail* (confirmation par mail)</td>
		<td class="cadre_clair" style="padding:4px"><input name="mail" type="text"'; if(isset($_POST['mail'])   && $m == 0){ echo'value="'.$_POST['mail'].'" class="bouton"'; } elseif(isset($_POST['mail'])) { echo'value="'.strip_gpc(htmlentities($_POST['mail'])).'" class="boutonb"'; } else { echo'class="bouton"'; }  echo'id="mail" size="32" maxlength="64" /></td>
	</tr>
	<tr>
		<td class="cadre_clair" style="padding:10px" colspan="2">
			<p>Conditions d\'utilisation</p>
				<textarea class="tcond" readonly="readonly">
Les administrateurs et mod�rateurs de ce forum s\'efforceront de supprimer ou �diter tous les messages � caract�re r�pr�hensible aussi rapidement que possible. Toutefois, il leur est impossible de passer en revue tous les messages. Vous admettez donc que tous les messages post�s sur ces forums expriment la vue et opinion de leurs auteurs respectifs, et non pas des administrateurs, ou mod�rateurs, ou webmestres (except� les messages post�s par eux-m�me) et par cons�quent ne peuvent pas �tre tenus pour responsables.
			
Vous consentez � ne pas poster de messages injurieux, obsc�nes, vulgaires, diffamatoires, mena�ants, sexuels ou tout autre message qui violerait les lois applicables. Le faire peut vous conduire � �tre banni imm�diatement de fa�on permanente (et votre fournisseur d\'acc�s � internet en sera inform�). L\'adresse IP de chaque message est enregistr�e afin d\'aider � faire respecter ces conditions. Vous �tes d\'accord sur le fait que le webmestre, l\'administrateur et les mod�rateurs de ce forum ont le droit de supprimer, �diter, d�placer ou verrouiller n\'importe quel sujet de discussion � tout moment. En tant qu\'utilisateur, vous �tes d\'accord sur le fait que toutes les informations que vous donnerez ci-apr�s seront stock�es dans une base de donn�es. Cependant, ces informations ne seront divulgu�es � aucune tierce personne ou soci�t� sans votre accord. Le webmestre, l\'administrateur, et les mod�rateurs ne peuvent pas �tre tenus pour responsables si une tentative de piratage informatique conduit � l\'acc�s de ces donn�es.

Ce forum utilise les cookies pour stocker des informations sur votre ordinateur. Ces cookies ne contiendront aucune information que vous aurez entr� ci-apr�s, ils servent uniquement � am�liorer le confort d\'utilisation. L\'adresse e-mail est uniquement utilis�e afin de confirmer les d�tails de votre enregistrement ainsi que votre mot de passe (et aussi pour vous envoyer un nouveau mot de passe dans la cas o� vous l\'oublieriez).

En vous enregistrant, vous vous portez garant du fait d\'�tre en accord avec le r�glement ci-dessus.
				</textarea>
			<p><input name="condok" type="checkbox" value="true" '.( ($cond) ? ' checked="checked" ' : '' ).'> J\'accepte les conditions d\'utilisation du forum</p>
		</td>
	</tr>
</table>

<p align="center"><input type="submit" name="Submit" class="bouton" value="Envoyer" /><input type="reset" name="Submit" value="R&eacute;initialiser" class="bouton" /></p>
</form>
	';
}
?>