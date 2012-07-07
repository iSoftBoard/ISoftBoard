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
$db = mysql_connect($host,$user,$mdpbdd);
mysql_select_db($bdd,$db);

if(isset($_SESSION['pseudo']))
{
	$pseudoa = $_SESSION['pseudo'];
}		
else 	
{ 
	header('Location: index.php?page=erreur='); 
}

if(isset($_GET['id']))
{ 
	$sql1 = 'SELECT rang FROM '.$prefixtable.'membres WHERE pseudo = "'.add_gpc($pseudoa).'"';
	$req1 = mysql_query($sql1);
	$data = mysql_fetch_assoc($req1); 
	$rang = $data['rang'];

	if($rang == 2)
	{
		$sql = 'DELETE FROM '.$prefixtable.'membres WHERE id = "'.intval($_GET['id']).'"';
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); 

	}
}
mysql_close();
header('Location: index.php?page=membre');
?>