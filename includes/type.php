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
 
if(empty($pseudo)) { include('./includes/erreur.php'); }
// Vérifie s'il vaut la peine d'aller plus loin
elseif($rang != 1 && $rang != 2 && $rang != 3 || !is_numeric($_GET['ids']) || $_GET['stat'] != 0 && $_GET['stat'] != 1 && $_GET['stat'] != 2) include('./includes/erreur.php');
// Si ça en vaut la peine
else
{
	// Si c'est un chef de groupe qui veut modifier
	if($rang == 3)
	{
		// On cherche le forum de ce sujet
		$sql = 'SELECT idsfa FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['ids']);
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		$requse++;
		if(mysql_num_rows($req) != 0)
		{
			$data = mysql_fetch_assoc($req);
			// On cherche le groupe de ce forum
			$sql = 'SELECT groupe FROM '.$prefixtable.'forum WHERE id = '.$data['idsfa'];
			$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
			$requse++;
			if(mysql_num_rows($req) != 0)
			{
				$data = mysql_fetch_assoc($req);
				// Si c'est pas un groupe particulier, on arrete là
				if($data['groupe'] == 0 || $data['groupe'] == -1 || $data['groupe'] == -2 || $data['groupe'] == -3) $modifier = false;
				else
				{ 
					// Si c'est un groupe particulier, on vérifie s'il en est chef
					$sql = 'SELECT id FROM '.$prefixtable.'groupemembre WHERE idm = "'.$idmembre.'" AND idg = "'.$data['groupe'].'" AND stat = "1"';
					$req = mysql_query($sql);
					$requse++;
					if(mysql_num_rows($req) == 0) $modifier = false;
					else $modifier = true;
				}
			}
			else
			{
				$modifier = false;
			}
		}
		else
		{
			$modifier = false;
		}
	}
	// Les modos et admins sont d'office acceptés
	else $modifier = true;
	//On va faire ce qu'il faut
	if($modifier)
	{
		$sql = 'UPDATE '.$prefixtable.'post SET sondage = "'.$_GET['stat'].'"  WHERE id2 = "'.$_GET['ids'].'"';
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); 
		$requse++;
		mysql_close();
		echo'
<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr align="center">
		<td height="29" class="titreforum">Changement de statut du sujet</td>
	</tr>
	<tr>
		<td align="center" class="cadre_clair" style="padding:30px"><p>Le type de ce sujet a été modifié pour :
		';
		if($_GET['stat'] == 0) echo 'Simple post';
		elseif($_GET['stat'] == 1) echo 'Post-it';
		elseif($_GET['stat'] == 2) echo 'Annonce';
		echo'
			.</p><p><a href="index.php?page=post&amp;ids='.$_GET['ids'].'">&gt;&gt; Retour au sujet. &lt;&lt;</a></p>
		</td>
	</tr>
</table>
		';
	}
	else
	{
		echo $modifier;
		include('./includes/erreur.php');
	}
}
?>