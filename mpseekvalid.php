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
 
include('./includes/gpc.php');

include('info_bdd.php');
$db = mysql_connect($host,$user,$mdpbdd);
mysql_select_db($bdd,$db);
if(isset($_POST['pseudo']))
{
	$sql = 'SELECT id FROM '.$prefixtable.'membres WHERE pseudo = "'.add_gpc($_POST['pseudo']).'"';
	$req = mysql_query($sql);
	if(mysql_num_rows($req) >0)
	{
		$data = mysql_fetch_assoc($req);
		header('Location: index.php?page=mpsend&id='.$data['id']);
	}
	else
	{
		header('Location: index.php?page=mpseek&bad=');
	}
}
else
{
	header('Location: index.php?page=mpseek&bad=');
}
?>