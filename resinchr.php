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
ini_set('magic_quotes_runtime', 0);
session_start();
if(empty($_SESSION['pseudo'])) exit();
$pseudo = $_SESSION['pseudo'];
include('info_bdd.php');
$db = mysql_connect($host,$user,$mdpbdd);
mysql_select_db($bdd,$db);
$sql = 'SELECT id,rang FROM '.$prefixtable.'membres WHERE id = "'.intval($_SESSION['idlog']).'"';
$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
if(mysql_num_rows($req) == 0) exit();
$data = mysql_fetch_assoc($req);
$rang = $data['rang'];
$idmembre = $data['id'];

if(empty($pseudo)) { include('./includes/erreur.php'); }
// Vérifie si ça vaut la peine d'aller plus loin
elseif($rang != 1 && $rang != 2 && $rang != 3 || !is_numeric($_GET['id2'])) include('./includes/erreur.php');
// Si ça en vaut la peine
else
{
	// Si c'est un chef de groupe qui veut modifier
	if($rang == 3)
	{
		// On cherche le forum de ce sujet
		$sql = 'SELECT idsfa FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['id2']);
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		if(mysql_num_rows($req) != 0)
		{
			$data = mysql_fetch_assoc($req);
			// On cherche le groupe de ce forum
			$sql = 'SELECT groupe FROM '.$prefixtable.'forum WHERE id = '.$data['idsfa'];
			$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
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
	// On va faire ce qu'il faut
	if($modifier)
	{
		//////////////////////////////////////////////////////////////////////////////////////
		$sql = 'SELECT idsfa,idfa,idsa,nbr FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['id2']);
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		if(mysql_num_rows($req) == 0) exit('1'); //Pas trop logique que ça bug
		$data = mysql_fetch_assoc($req);
		if($data['idsa'] == 0)
		{
			$sql = 'SELECT tmppost,pseudode FROM '.$prefixtable.'post WHERE idsa = '.intval($_GET['id2']).' ORDER BY tmppost DESC';
			$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
			$data3 = mysql_fetch_assoc($req);
			$nbr = mysql_num_rows($req);
			$sql = 'UPDATE '.$prefixtable.'post SET nbr = '.$nbr.' , tmppost = '.$data3['tmppost'].' , pseudodernier = "'.addslashes($data3['pseudode']).'" WHERE id2 = '.intval($_GET['id2']);

			if($nbr == 0)
			{
				$sql = 'SELECT tmpsave,pseudode,tmpdernierpost FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['id2']).' ORDER BY tmppost DESC';
				$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
				$data3 = mysql_fetch_assoc($req);
				$sql = 'UPDATE '.$prefixtable.'post SET nbr = 0 , tmppost = '.$data3['tmpsave'].' , pseudodernier = "'.addslashes($data3['pseudode']).'" WHERE id2 = '.intval($_GET['id2']);
			}
					
			$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
			header('Location: index.php?page=resynchok&ids='.intval($_GET['id2']));
		}
		else
		{
			header('Location: index.php?page=erreur1');
		}
		////////////////////////////////////////////////////////////////////////////////////////
	}
	else
	{
		header('Location: index.php?page=erreur');
	}
}
?>    