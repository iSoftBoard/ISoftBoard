<?php
/***************************************************************************
 *
 *   SoftBB - Forum de discussion 
 *   Version : 0.1
 *
 *   copyright            : (C) 2005 Jérémy Dombier [Belgium]
 *   email                : satapi@gmail.com
 *   site-web             : http://softbb.be/
 *
 *   Ce programme est un logiciel libre ; vous pouvez le redistribuer et/ou 
 *   le modifier au titre des clauses de la Licence Publique Générale GNU, 
 *   telle que publiée par la Free Software Foundation ; soit la version 2 de 
 *   la Licence, ou (à votre discrétion) une version ultérieure quelconque. 
 *   Ce programme est distribué dans l'espoir qu'il sera utile, mais 
 *   SANS AUCUNE GARANTIE ; sans même une garantie implicite de COMMERCIABILITE 
 *   ou DE CONFORMITE A UNE UTILISATION PARTICULIERE. Voir la Licence Publique 
 *   Générale GNU pour plus de détails. Vous devriez avoir reçu un exemplaire 
 *   de la Licence Publique Générale GNU avec ce programme ; si ce n'est pas le 
 *   cas, écrivez à la Free Software Foundation Inc., 
 *   51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 ***************************************************************************/
 
if(!defined('IN_SOFTBB')) exit('Not in SoftBB');
 
if($rang == -1 || !isset($rang))
{ 
	include('./includes/erreur.php');
}
else
{
	if(isset($_GET['id']))
	{ 
		if($rang == 2 && $_GET['id'] != "")
		{
			$id = $_GET['id'];
			$pasmod=0;
			$sql = 'SELECT www,avatar,sign,signaff,rangspec,afflist,localisation,rang,valid,pseudo,mail,gmt,he FROM '.$prefixtable.'membres WHERE id = "'.intval($id).'"';
		}
		else
		{
			$pasmod=1; $sql = 'SELECT www,avatar,sign,signaff,afflist,localisation,pseudo,mail,gmt,he FROM '.$prefixtable.'membres WHERE id = "'.intval($_SESSION['idlog']).'"';
		}
	}
	else
	{
		$pasmod=1;  $sql = 'SELECT id,pseudo,sign,rangspec,signaff,afflist,www,avatar,localisation,rang,valid,mail,gmt,he FROM '.$prefixtable.'membres WHERE id = "'.intval($_SESSION['idlog']).'"';
	}

	$req = mysql_query($sql);
	mysql_close();
	$requse++;
	$data = mysql_fetch_assoc($req);
	echo'
<form action="profils.php';
if(isset($_GET['id']) && $pasmod == 0) { echo'?id='.$id; }
echo'" method="post" enctype="multipart/form-data" name="form1">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="texte_base_gras">
	<tr>
    	<td class="cadre1_bas" style="padding:10px">'; echo '<a href="index.php">Index : '.htmlentities($nomduforum).'</a>'; 
	echo '
      </td>
	</tr>
</table>

<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr align="center">
		<td height="29" class="titreforumend">Compte</td>
	</tr>
</table>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="texte_base_gras">
	<tr>
		<td width="30%" class="cadre_clair" style="padding:4px"><label for="pseudo">Pseudo</label></td>
		<td class="cadre1_bas" style="padding:4px"><input id="pseudo" name="pseudoren" '; if($rang != 2 && !$autmodpseudo) echo 'disabled="true"'; echo' type="text" class="bouton" size="32" maxlength="64" value="'.htmlentities($data['pseudo']).'" /></td>
	</tr>
	<tr>
		<td width="30%" class="cadre_clair" style="padding:4px"><label for="email">Adresse E-Mail</label></td>
		<td class="cadre1_bas" style="padding:4px"><input id="email" name="mail"  '; if($rang != 2) echo 'disabled="true"'; echo'  type="text" class="bouton" size="32" maxlength="64" value="'.htmlentities($data['mail']).'" /></td>
	</tr>
	<tr>
		<td width="30%" class="cadre_clair" style="padding:4px"><label for="mdpa">Mot de passe actuel *</label><br /><span class="texte_base_fin">Vous devez confirmer votre mot de passe si vous souhaitez modifier votre mot de passe</span></td>
		<td class="cadre1_bas" style="padding:4px"><input id="mdpa" name="mdp" type="password" class="bouton" size="32" maxlength="64" />'; if(isset($_GET['countmdp'])) echo ' <span class="red">Vous avez commis une erreur en chageant de mot de passe</span>'; echo'</td>
	</tr>
	<tr>
		<td width="30%" class="cadre_clair" style="padding:4px"><label for="nmdp">Nouveau mot de passe</label></td>
		<td class="cadre1_bas" style="padding:4px"><input id="nmdp" name="mdp1" type="password" class="bouton" size="32" maxlength="64" /></td>
	</tr>
	<tr>
		<td width="30%" class="cadre_clair" style="padding:4px"><label for="nmdpc">Nouveau mot de passe (confirmation)</label></td>
		<td class="cadre1_bas" style="padding:4px"><input id="nmdpc" name="mdp2" type="password" class="bouton" size="32" maxlength="64" /></td>
	</tr>
</table> 
			
<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr align="center">
		<td height="29" class="titreforumend">Profil'; if(isset($_GET['id']) && $pasmod == 0) { echo ' - '.htmlentities($data['pseudo']); } echo'</td>
	</tr>
</table>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="texte_base_gras">
	<tr>
		<td width="30%" class="cadre_clair" style="padding:4px"><label for="loca">Localisation</label></td>
		<td class="cadre1_bas" style="padding:4px"><input id="loca" name="localisation" type="text" class="bouton" size="32" maxlength="64" value="'.htmlentities($data['localisation']).'" /></td>
	</tr>
	<tr>
		<td width="30%" class="cadre_clair" style="padding:4px"><label for="site_web">Site web personnel</label></td>
		<td class="cadre1_bas" style="padding:4px"><input id="site_web" name="urlwww" type="text" class="bouton" value="'.htmlentities($data['www']).'" size="32" maxlength="128" /></td>
    </tr>
	';
	if($rang == 2)
	{
		echo'
	<tr>
		<td width="30%" class="cadre_clair" style="padding:4px">Rang d\'utilisateur</td>
		<td class="cadre1_bas" style="padding:4px">   
			<select name="rang"  class="sbouton" id="rang">
				<option value="2" '; if($data['rang'] == 2){ echo 'selected'; } echo'>Administrateur</option>
				<option value="1" ';if($data['rang'] == 1){ echo 'selected'; } echo'>Mod&eacute;rateur</option>
				<option value="3" '; if($data['rang'] == 3){ echo 'selected'; } echo'>Chef de groupe</option>
				<option value="0" '; if($data['rang'] == 0){ echo 'selected'; } echo'>Membre</option>
			</select>
			<input name="rangi" type="hidden" id="rangi" value="'.$data['rang'].'" /> 
			<br />&quot;Chef de groupe&quot; ne conf&egrave;re aucun pouvoir, il sert uniquement &agrave; indiquer qu\'un membre est mod&eacute;rateur d\'un groupe, il suffit donc de le laisser pour lui accorder cette mod&eacute;ration, un chef de groupe qui re&ccedil;oit comme rang &quot;membre&quot;, se voit enlever cette mod&eacute;ration pour tout les groupes qu\'il mod&egrave;re.
		</td>    
	</tr>
	<tr>
		<td width="30%" class="cadre_clair" style="padding:4px">Validation/Banissement</td>
		<td class="cadre1_bas" style="padding:4px">   
			<select name="valid"  class="sbouton" id="valid">
				<option value="1" '; if($data['valid'] == 1){ echo 'selected'; } echo'>Compte valid&eacute;</option>
				<option value="0" '; if($data['valid'] == 0){ echo 'selected'; } echo'>Compte banni</option>
			</select> 
			<input name="validi" type="hidden" id="validi" value="'.$data['valid'].'" /> 
			<br />
			Un membre qui n\'a pas valid&eacute; son compte par mail est consider&eacute; comme banni. En validant son inscription, il sera valid&eacute;.
		</td>
	</tr>
		';
		if(isset($_GET['id']) && $pasmod == 0)
		{			     
			echo'
	<tr>
		<td width="30%" class="cadre_clair" style="padding:4px">Supprimer cet utilisateur</td>
		<td class="cadre1_bas" style="padding:4px"><input type="button" class="bouton" VALUE="Supprimer ce compte" NAME="button1" onclick="decision(\'Voulez-vous vraiment supprimer\n definitivement le compte de : '.addslashes(htmlentities($data['pseudo'])).'\',\'delutil.php?id='.$_GET['id'].'\')" />
		</td>
	</tr>
			';
		}
		echo'
	<tr>
		<td width="30%" class="cadre_clair" style="padding:4px">Rang spécial</td>
		<td class="cadre1_bas" style="padding:4px">
			<select name="rangspec" class="sbouton">
				<option value="0" '; if($data['rangspec'] == 0) echo ' selected="selected" '; echo'>[Membre]</option>';
				for($si=0;$si<count($rangnom);$si++)
				{
					echo '<option value="'.($si+1).'"';
					if($data['rangspec'] == ($si+1)) echo ' selected="selected" ';
					echo'>'.$rangnom[$si].'</option>';
				}
  
				echo'
			</select>
		</td>
	</tr>
				';
	}
	echo'
	<tr>
		<td width="30%" class="cadre_clair" style="padding:4px"><label for="sign">Signature</label><br /><span class="texte_base_fin">Ceci est un bloc de texte qui peut être ajouté aux messages que vous postez.<br />/!\ Attention ! Votre signature est limit&eacute;e &agrave; 255 caractères</span></td>
		<td class="cadre1_bas" style="padding:4px"><textarea id="sign" name="signtxt" class="tsign">'.(htmlentities($data['sign'])).'</textarea></td>
	</tr>
	<tr>
		<td width="30%" class="cadre_clair" style="padding:4px">Afficher la signature';  if(!$autorisationsign)echo' (Désactivé)'; echo'</td>
		<td class="cadre1_bas" style="padding:4px">
			<input type="radio" name="sign" value="1"'; if($data['signaff'] == 1) echo '  checked '; echo' /> Oui
			<input type="radio" name="sign" value="0"'; if($data['signaff'] == 0) echo '  checked '; echo' /> Non
		</td>
	</tr>
	<tr>
		<td width="30%" class="cadre_clair" style="padding:4px">Masquer ma présence en ligne';  if(!$afflistdelauto)echo' (Désactivé)'; echo'</td>
		<td class="cadre1_bas" style="padding:4px">
			<input type="radio" name="ligne" value="1"'; if($data['afflist'] == 1) echo '  checked '; echo' /> Oui
			<input type="radio" name="ligne" value="0"'; if($data['afflist'] == 0) echo '  checked '; echo' /> Non
		</td>
	</tr>
	';
	echo'
	<tr>
		<td width="30%" class="cadre_clair" style="padding:4px">Fuseau horaire</td>
		<td class="cadre1_bas" style="padding:4px">
			<select name="gmt" class="sbouton">
				<option value="-12" '; if($data['gmt'] == -12) echo 'selected="selected"'; echo'>GMT - 12 Heures</option>
				<option value="-11" '; if($data['gmt'] == -11) echo 'selected="selected"'; echo'>GMT - 11 Heures</option>
				<option value="-10" '; if($data['gmt'] == -10) echo 'selected="selected"'; echo'>GMT - 10 Heures</option>
				<option value="-9" '; if($data['gmt'] == -9) echo 'selected="selected"'; echo'>GMT - 9 Heures</option>
				<option value="-8" '; if($data['gmt'] == -8) echo 'selected="selected"'; echo'>GMT - 8 Heures</option>
				<option value="-7" '; if($data['gmt'] == -7) echo 'selected="selected"'; echo'>GMT - 7 Heures</option>
				<option value="-6" '; if($data['gmt'] == -6) echo 'selected="selected"'; echo'>GMT - 6 Heures</option>
				<option value="-5" '; if($data['gmt'] == -5) echo 'selected="selected"'; echo'>GMT - 5 Heures</option>
				<option value="-4" '; if($data['gmt'] == -4) echo 'selected="selected"'; echo'>GMT - 4 Heures</option>
				<option value="-3.5"  '; if($data['gmt'] == -3.5) echo 'selected="selected"'; echo'>GMT - 3:30 Heures</option>
				<option value="-3" '; if($data['gmt'] == -3) echo 'selected="selected"'; echo'>GMT - 3 Heures</option>
				<option value="-2" '; if($data['gmt'] == -2) echo 'selected="selected"'; echo'>GMT - 2 Heures</option>
				<option value="-1" '; if($data['gmt'] == -1) echo 'selected="selected"'; echo'>GMT - 1 Heure</option>
				<option value="0" '; if($data['gmt'] == 0) echo 'selected="selected"'; echo'>GMT</option>
				<option value="1" '; if($data['gmt'] == 1) echo 'selected="selected"'; echo'>GMT + 1 Heure</option>
				<option value="2" '; if($data['gmt'] == 2) echo 'selected="selected"'; echo'>GMT + 2 Heures</option>
				<option value="3" '; if($data['gmt'] == 3) echo 'selected="selected"'; echo'>GMT + 3 Heures</option>
				<option value="3.5" '; if($data['gmt'] == 3.5) echo 'selected="selected"'; echo'>GMT + 3:30 Heures</option>
				<option value="4" '; if($data['gmt'] == 4) echo 'selected="selected"'; echo'>GMT + 4 Heures</option>
				<option value="4.5" '; if($data['gmt'] == 4.5) echo 'selected="selected"'; echo'>GMT + 4:30 Heures</option>
				<option value="5" '; if($data['gmt'] == 5) echo 'selected="selected"'; echo'>GMT + 5 Heures</option>
				<option value="5.5" '; if($data['gmt'] == 5.5) echo 'selected="selected"'; echo'>GMT + 5:30 Heures</option>
				<option value="6" '; if($data['gmt'] == 6) echo 'selected="selected"'; echo'>GMT + 6 Heures</option>
				<option value="6.5" '; if($data['gmt'] == 6.5) echo 'selected="selected"'; echo'>GMT + 6:30 Heures</option>
				<option value="7" '; if($data['gmt'] == 7) echo 'selected="selected"'; echo'>GMT + 7 Heures</option>
				<option value="8" '; if($data['gmt'] == 8) echo 'selected="selected"'; echo'>GMT + 8 Heures</option>
				<option value="9" '; if($data['gmt'] == 9) echo 'selected="selected"'; echo'>GMT + 9 Heures</option>
				<option value="9.5" '; if($data['gmt'] == 9.5) echo 'selected="selected"'; echo'>GMT + 9:30 Heures</option>
				<option value="10" '; if($data['gmt'] == 10) echo 'selected="selected"'; echo'>GMT + 10 Heures</option>
				<option value="11" '; if($data['gmt'] == 11) echo 'selected="selected"'; echo'>GMT + 11 Heures</option>
				<option value="12" '; if($data['gmt'] == 12) echo 'selected="selected"'; echo'>GMT + 12 Heures</option>
				<option value="13" '; if($data['gmt'] == 13) echo 'selected="selected"'; echo'>GMT + 13 Heures</option>
			</select>
			<input type="checkbox" name="he" value="1" '; if($data['he'] == 1) echo 'checked="checked"'; echo' />Activer le passage &agrave; l\'heure d\'&eacute;t&eacute; / heure d\'hiver
		</td>
	</tr> 
</table>

<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr align="center">
		<td height="29" class="titreforumend">Avatar</td>
	</tr>
</table>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="texte_base_gras">
	<tr>
		<td colspan="2" class="cadre1_bas" style="padding:4px">
			<table width="70%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td><span class="gensmall">Affiche une petite image au-dessous de vos d&eacute;tails (pseudo et rang) dans vos messages. Une seule image peut &ecirc;tre affich&eacute;e &agrave; la fois, sa largeur ne peut pas d&eacute;passer '.$lmax.' pixels et sa hauteur '.$hmax.' pixels. : max '.floor($pmax/1024).' Ko (SI VOUS MODIFIEZ VOTRE AVATAR ET QU\'IL N\'A PAS CHANGE, VIDEZ VOTRE CACHE OU APPUYEZ SUR CTRL + F5) 
	'; 
	if(isset($_GET['s'])) {$s=$_GET['s'];} else { $s=0; }
	if(isset($_GET['f'])) {$f=$_GET['f'];} else { $f=0; }
	if(isset($_GET['p'])) {$p=$_GET['p'];} else { $p=0; }
	if(isset($_GET['m'])) {$m=$_GET['m'];} else { $m=0; }

	if($s == 1) { echo '<br /><span class="red">/!\ Vous n\'avez pas respecté les limitations de taille /!\</span>'; }
	if($p == 1) { echo '<br /><span class="red">/!\ Vous n\'avez pas respecté les limitations de poids /!\</span>'; }
	if($f == 1) { echo '<br /><span class="red">/!\ Vous n\'avez pas respecté le format /!\</span>'; }
	if($m == 1) { echo '<br /><span class="red">/!\ Vous n\'avez pas indiquer un lien valide /!\</span>'; }
	
	echo'
						</span>
					</td>
					<td>
	'; 
	if(!isset($_GET['h'])) { $h=0; } else { $h = $_GET['h']; }
	if(!isset($_GET['l'])) { $l=0; } else { $l = $_GET['l']; }
	if(!isset($_GET['ma'])) { $ma=0; } else { $ma = $_GET['ma']; }
	
	if($h == 1) { echo '<span class="red">Hauteur de l\'avatar sup&eacute;rieur &agrave; '.$hmax.' px</span><br />'; }
	if($l == 1) { echo '<span class="red">Largeur de l\'avatar sup&eacute;rieur &agrave; '.$lmax.' px</span>'; }
	if($ma == 1) { echo '<span class="red">Mauvais url de l\'avatar </span>'; }
	if($data['avatar'] != "http://" && $data['avatar'] != "" && $h != 1 && $l != 1 && $ma != 1 )
	{
		echo '
						<p><img src="'.$data['avatar'].'"></p>
		';
	}
			  
	echo'
						<br />
						<input name="delavatar" type="checkbox" id="delavatar" value="ok" /> Supprimer l\'avatar 
						<input type="hidden" name="avatarr"  value="'.$data['avatar'].'" />
					</td>
				</tr>
			</table>
		</td>
	</tr>
	';
	echo '           
	<tr>
		<td width="30%" class="cadre_clair" style="padding:4px"><label for="avatar_url">Importer l\'avatar depuis un url</label></td>
		<td class="cadre1_bas" style="padding:4px"><input id="avatar_url" name="avatar" type="text" class="bouton" id="avatar" size="32" maxlength="128" /></td>
	</tr>
	<tr>
		<td width="30%" class="cadre_clair" style="padding:4px"><label for="avatar">Importer un avatar</label></td>
		<td class="cadre1_bas" style="padding:4px"><input id="avatar" name="avatarup" type="file" class="bouton" id="avatarup" size="25" /></td>
	</tr>
	';
	mysql_free_result($req);
	echo'
</table>
	';
	if($rang != -1)
	{
		echo '
	<p align="center"><input type="submit" name="Submit" class="bouton" value="Sauvegarder"><input type="reset" name="Submit" value="R&eacute;initialiser" class="bouton" /></p>
</form>
		';
	}
}
?>