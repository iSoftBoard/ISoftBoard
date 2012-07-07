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
include('./includes/gpc.php');
 
session_start();
include('info_bdd.php');

if(isset($_SESSION['pseudo'])) $pseudo = $_SESSION['pseudo'];
else header('Location: index.php?page=erreur'); 
		
$db = mysql_connect($host,$user,$mdpbdd);
mysql_select_db($bdd,$db);
$sql = 'SELECT rang FROM '.$prefixtable.'membres WHERE id = "'.intval($_SESSION['idlog']).'"  AND valid = "1"';
$req = mysql_query($sql);
if(mysql_num_rows($req) == 1) 
{
	$data = mysql_fetch_assoc($req); 
	if($data['rang'] == 2 || $data['rang'] == 1)
	{
		$sql2 = 'SELECT pseudo,id FROM '.$prefixtable.'membres WHERE pseudo = "'.add_gpc($_POST['pseudo']).'"  AND valid = "1"';
		$req2 = mysql_query($sql2);
		if(mysql_num_rows($req2) == 1)
		{
			$data = mysql_fetch_assoc($req2);	
			$sql = 'SELECT id FROM '.$prefixtable.'groupemembre WHERE idm = "'.$data['id'].'" AND idg ='.$_GET['groupe'];
			$req3 = mysql_query($sql);
			if(mysql_num_rows($req3) == 0)
			{
				$sql = 'INSERT INTO '.$prefixtable.'groupemembre (`idm`, `idg`, `pseudom`, `stat`) VALUES ("'.$data['id'].'","'.$_GET['groupe'].'",0,0)';
				$req = mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error());
			}  
			else 
			{ 
				$redir = 'index.php?page=erreurgroup&type=deja&retour='.$_GET['groupe'];  
			}
		}  
		else 
		{
			$redir = 'index.php?page=erreurgroup&type=membreban&retour='.$_GET['groupe'];  
		}
 		if(!isset($redir))
		{
			$redir = 'index.php?page=affgroupe&groupe='.$_GET['groupe'];
		}
	} 
 	else 
	{ 
		$redir = 'index.php?page=erreur';
	}
}
else
{
	$redir = 'index.php?page=erreur';
}
header('Location: '.$redir);
?>