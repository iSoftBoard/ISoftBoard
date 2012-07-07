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
include('info_bdd.php');
if(isset($_SESSION['pseudo']))
{
	$pseudo = $_SESSION['pseudo'];
}
else 	
{ 
	header('Location: index.php?page=erreur'); 
}
$db = mysql_connect($host,$user,$mdpbdd);
mysql_select_db($bdd,$db);
$sql = 'SELECT rang FROM '.$prefixtable.'membres WHERE id = "'.intval($_SESSION['idlog']).'"  AND valid = "1"';
$req = mysql_query($sql);

if(mysql_num_rows($req) == 1) 
{
	$data = mysql_fetch_assoc($req); 
	if($data['rang'] == 2 || $data['rang'] == 1)
	{
		$sql = 'SELECT rang,id FROM '.$prefixtable.'membres WHERE id = "'.intval($_GET['idm']).'"';
		$req = mysql_query($sql);
		if(mysql_num_rows($req) == 1)
		{
			$data = mysql_fetch_assoc($req);
			$rangsave = $data['rang'];
			// Si rang suffisant
			if($data['rang'] == 1 || $data['rang'] == 2 || $data['rang'] == 0)
			{
				$sql = 'DELETE FROM '.$prefixtable.'groupemembre WHERE idm = "'.intval($_GET['idm']).'" AND idg = "'.intval($_GET['idg']).'"';
				$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); 
			}
			// Autrement
			else 
			{
				$sql = 'DELETE FROM '.$prefixtable.'groupemembre WHERE idm = "'.intval($_GET['idm']).'" AND idg = "'.intval($_GET['idg']).'"';
				$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); 
				
				$sql = 'SELECT id FROM '.$prefixtable.'groupemembre WHERE idm = "'.intval($_GET['idm']).'"  AND stat = 1';
				$req = mysql_query($sql);
				// Si chef dans autres groupes
				if(mysql_num_rows($req) == 0 && $rangsave == 3)
				{
					$sql = 'UPDATE '.$prefixtable.'membres SET rang = 0  WHERE id = "'.intval($_GET['idm']).'"';
					$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); 
				}
			}
			header('Location: index.php?page=affgroupe&groupe='.intval($_GET['idg']).'');
		}		
 		else 
		{ 
			header('Location: index.php?page=erreur&type=membreban'); 
		}
	}
	else 
	{ 
		header('Location: index.php?page=erreur'); 
	}	
}
else 
{ 
	header('Location: index.php?page=erreur'); 
}	
?>