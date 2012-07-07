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
if($rang == -1)
{ 
	include('./includes/erreur.php');
}
else
{
	$sql = 'SELECT m.*,s.pseudo AS sender , r.pseudo AS receveur FROM '.$prefixtable.'mp AS m LEFT JOIN '.$prefixtable.'membres AS s ON m.idde=s.id LEFT JOIN '.$prefixtable.'membres AS r ON m.ida=r.id WHERE m.ida  = '.intval($idmembre).'  AND  m.id = '.intval($_GET['idm']).' OR m.idde  = '.intval($idmembre).'  AND  m.id = '.intval($_GET['idm']).'';
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
	$requse++;

	// mysql_free_result($req);
	if(mysql_num_rows($req) > 0)
	{
		$data = mysql_fetch_assoc($req);
		echo'
		<table class="texte_base_gras" width="100%"  border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td';if(!isset($_GET['send'])) {echo ' width="180" ';}else{echo ' width="84" ';}echo' class="cadre1_bas" style="padding:10px">
					<a href="index.php?page=mpseek"><img src="./img/actions/nouveau.gif" alt"Nouveau MP" /></a>';
					if(!isset($_GET['send'])) echo' <a href="index.php?page=mpsend&amp;id='.$data['idde'].'&amp;rep='.$_GET['idm'].'"><img src="./img/actions/repondre.gif" alt="Répondre" /></a>';
				echo'
				</td>
				<td class="cadre1_bas" style="padding:10px"><a href="index.php">Index : '.htmlentities($nomduforum).'</a>-&gt; <a href="index.php?page=mp">Boite de r&eacute;ception</a> - <a href="index.php?page=mp&amp;send">El&eacute;ments envoy&eacute;s</a></td>
			</tr>
		</table>
		<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr align="center">
				<td height="29" class="titreforumend">Boite de r&eacute;ception - Archives</td>
			</tr>
		</table>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="texte_base_gras">
			<tr>
				<td class="cadre_clair" style="padding:5px" width="60">De :</td>
				<td class="cadre1_bas" style="padding:5px">'.bbcode(htmlentities($data['sender'])).'</td>
			</tr>
			<tr>
				<td class="cadre_clair" style="padding:5px">A :</td>
				<td class="cadre1_bas" style="padding:5px">'.bbcode(htmlentities($data['receveur'])).'</td>
			</tr>
			<tr>
				<td class="cadre_clair" style="padding:5px">Sujet :</td>
				<td class="cadre1_bas" style="padding:5px">'.sit(htmlentities($data['titre'])).'</td>
			</tr>
			<tr>
				<td class="cadre_clair" style="padding:5px">Date :</td>
				<td class="cadre1_bas" style="padding:5px">Le '.datefct($data['temps'],$gmt).'</td>
			</tr>
			<tr>
				<td colspan="2" class="alternate2" style="padding:10px">'.bbcode(nl2br(htmlentities($data['texte']))).'</td>
			</tr>
		</table>
		';
		if(!isset($_GET['send']) && $data['lu'] == 0) 
		{
			$sql = 'UPDATE '.$prefixtable.'mp SET lu = 1 WHERE id = '.intval($data['id']).' AND ida = '.intval($idmembre);
			$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); 
			$requse++;
			mysql_close();
		}
	}
	else
	{
		include('./includes/erreur.php');
	}
}
?>

