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
if(isset($_SESSION['mprec'])) unset($_SESSION['mprec']);
if(!isset($_SESSION['pseudo']) || empty($_SESSION['pseudo'])) exit();
?>
<table class="texte_base_gras" width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="84" class="cadre1_bas" style="padding:10px"><a href="index.php?page=mpseek"><img src="./img/actions/nouveau.gif" alt"Nouveau MP" /></a></td>
		<td class="cadre1_bas" style="padding:10px"><?php echo'<a href="index.php">Index : '.htmlentities($nomduforum).'</a>'; ?> -&gt; <a href="index.php?page=mp"> <?php if(!isset($_GET['send'])) echo'<span class="admin">';?>Boite de r&eacute;ception<?php if(!isset($_GET['send'])) echo'</span>';?></a> - <a href="index.php?page=mp&amp;send"><?php if(isset($_GET['send'])) echo'<span class="admin">';?>El&eacute;ments envoy&eacute;s <?php if(isset($_GET['send'])) echo'</span>';?></a></td>
	</tr>
</table>
<?php
if($rang == -1)
{ 
	include('./includes/erreur.php');
}
else
{
	if($mp > 0)
	{
		$sql = 'UPDATE '.$prefixtable.'membres SET mp = 0 WHERE id = "'.intval($_SESSION['idlog']).'"';
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		$requse++;
	}
	
	if(!isset($_GET['send']))
	{
		$sql = 'SELECT p.*,m.pseudo FROM '.$prefixtable.'mp AS p LEFT JOIN '.$prefixtable.'membres AS m ON p.idde=m.id WHERE ida = '.intval($idmembre).' ORDER BY id DESC';
	}
	else
	{
		$sql = 'SELECT p.*,m.pseudo FROM '.$prefixtable.'mp AS p LEFT JOIN '.$prefixtable.'membres AS m ON p.ida=m.id WHERE idde = '.intval($idmembre).' ORDER BY id DESC';
	}

	$req = mysql_query($sql);
	mysql_close();
	$requse++;
	echo '
	<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td height="29" colspan="2" align="center" class="titreforum">Titre</td>
			<td width="130" class="titreforum">
				<div align="center">';
				if(!isset($_GET['send']))
				{
					echo 'Auteur&nbsp;';
				}
				else
				{
					echo'Reception';
				}
				echo'
				</div>
			</td>
			<td width="135" class="titreforum"><div align="center">Date</div></td>
			<td width="135" class="titreforumend"><div align="center">Action</div></td>
		</tr>
	';
	if(mysql_num_rows($req) == 0)
	{
		echo '
		<tr>
			<td colspan="5" class="cadre_fonce_end" align="center" style="padding:20px">Vous n\'avez pas de message</td>
		</tr>
		';
	}
	while($data = mysql_fetch_assoc($req))
	{
		echo '
		<tr>
			<td class="cadre_fonce" width="1">';
			if($data['lu'] == 1)
			{
				echo'<img src="./img/statut/sujet.gif" alt="Pas de nouveau Message" />';
			}
			else
			{
				echo'<img src="./img/statut/n_sujet.gif" alt="Nouveau MP" />';
			}	
			echo'
			</td>
			<td class="cadre_fonce" style="padding:5px"><a href="index.php?page=mpread&amp;idm='.$data['id'];
			if(isset($_GET['send'])) echo'&send=';
			echo'">'.sit(htmlentities($data['titre'])).'</a>';
			
			echo'
			</td>
			<td width="130" class="cadre_clair" align="center">
				<a href="index.php?page=affprofil&amp;id=';
				if(!isset($_GET['send']))
				{
					echo $data['idde'];
				}
				else
				{
					echo $data['ida'];
				}
				echo'">';
				if(!isset($_GET['send']))
				{
					echo htmlentities($data['pseudo']);
				}
				else
				{
					echo htmlentities($data['pseudo']);
				}
				echo'</a>
			</td>
			<td width="135" class="cadre_fonce" align="center">'.datefct($data['temps'],$gmt).'</td>
			<td width="135" class="cadre_fonce_end" align="center">'; 
			if(!isset($_GET['send'])) echo'<a href="delmp.php?id='.$data['id'].'">[Supprimer]</a>';      
			else echo '-';
			echo'
			</td>
		</tr>
		';
	}
	echo '
	</table>
	';
	}
?>