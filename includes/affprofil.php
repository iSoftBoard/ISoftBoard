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
 
?><table class="texte_base_gras" width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="cadre1_bas" style="padding:10px"><?php echo '<a href="index.php">Index : '.htmlentities($nomduforum).'</a>'; ?></td>
	</tr>
</table><?php 
if(isset($_POST['pseudo']))
{
	$sql = 'SELECT id,www,avatar,localisation,rang,valid,nbpost,pseudo,mail FROM '.$prefixtable.'membres WHERE pseudo = "'.add_gpc($_POST['pseudo']).'"';
	$req = mysql_query($sql);
	$requse++;
	if(mysql_num_rows($req) >0)
	{
		$data = mysql_fetch_assoc($req);
		$aff = 1;
	}
	else
	{
		$aff=0;
		echo'
		<table width="100%" class="texte_base_gras" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td height="29" class="titreforumend" style="padding-left:8px">Recherche d\'un membre</td>
			</tr>
			<tr>
				<td class="cadre1_bas" style="padding:15px">Aucun membre ne correspond &agrave; votre requ&ecirc;te,<br />
				R&eacute;essayez en faisant attention &agrave; ne pas faire de fautes de frappes.<br />
				<br />
				<a href="index.php?page=membre">Retour &agrave; la liste des membres</a>
				</td>
			</tr>
		</table>
		';
	}
}
else
{ 
	$sql = 'SELECT id,www,avatar,localisation,rang,valid,nbpost,pseudo,mail FROM '.$prefixtable.'membres WHERE id = "'.intval($_GET['id']).'"';
	$req = mysql_query($sql);
	$requse++;
	mysql_close();
	$data = mysql_fetch_assoc($req);
	$aff=1;
}

if($aff == 1)
{
	echo '
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="texte_base_gras">
		<tr align="center">
			<td width="150" height="29" class="titreforum">Avatar</td>
			<td class="titreforumend">Profil</td>
		</tr>
		<tr>
			<td align="center" class="cadre_clair" style="padding:10px">
			';
			if($data['avatar'] != "http://" && $data['avatar'] != "")
			{
				echo '<img src="'.$data['avatar'].'" alt="" />';
			}
			else
			{
				echo'-';
			}
			if($rang == -1)
			{
				$row = 1;
			}
			else
			{
				$row=3;
			}
			echo'
			</td>
			<td rowspan="'.$row.'" valign="top" class="cadre1_bas" style="padding:10px"><span class="admin">Nom d\'utilisateur : </span>';
			echo htmlentities($data['pseudo']);
			echo'
				<br /><span class="admin">Rang : </span>
				'; 
				if($data['valid'] == 0)
				echo 'banni'; 
					elseif($data['rang'] == 2) echo 'administrateur';
					elseif($data['rang'] == 1) echo 'moderateur';
					elseif($data['rang'] == 3) echo 'chef de groupe';
				else echo 'membre';
				echo'
					<br />
					<br />
					<span class="modo">Localisation : </span>'.htmlentities($data['localisation']).'<br /><span class="modo">Site Web : </span>';
					if(!empty($data['www']) && $data['www'] != "http://")
					{
						echo'<a href="'.htmlentities($data['www']).'">'.htmlentities($data['www']).'</a>';
					}
					else
					{
						echo '-';
					}
					echo' <br /><br /><span class="admin">Nombre de posts : </span> ';
					echo $data['nbpost'];
					if($rang == 2)
						echo'<br /><br />E-mail : </span>'.$data['mail'];
				echo'
			</td>
		</tr>
		';
		if($rang == 2) 
		echo '
			<tr><td height="29" width="280" class="titreforum"  align="center">Action</td>
		</tr>
		<tr>
			<td class="cadre_clair" style="padding:10px"><a href="index.php?page=profil&amp;id='.$data['id'].'"><span class="modo">[Administrer le compte de ce membre]</span></a><br /><a href="index.php?page=mpsend&amp;id='.$data['id'].'"><span class="modo">[Envoyer un message priv&eacute;]</span></a><br />
		</tr>
		<tr>';
		mysql_free_result($req); 
		if($rang != -1 && $rang != 2)
		echo'
			<tr><td height="29" width="250" class="titreforum"  align="center">Action</td>
		</tr>
		<tr>
			<td class="cadre_clair" style="padding:10px" align="center"><a href="index.php?page=mpsend&amp;id='.$data['id'].'"><span class="modo">[Envoyer un message priv&eacute;]</span></a></td>
			
		</tr>
	';
	echo '</table>';
}
?>