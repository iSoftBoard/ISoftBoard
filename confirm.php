<?php
/***************************************************************************
 *
 *   SoftBB - Forum de discussion 
 *   Version : 0.1
 *
 *   copyright            : (C) 2005 J�r�my Dombier [Belgium]
 *   email                : satapi@gmail.com
 *   site-web             : http://softbb.be/
 *
 *   Ce programme est un logiciel libre ; vous pouvez le redistribuer et/ou 
 *   le modifier au titre des clauses de la Licence Publique G�n�rale GNU, 
 *   telle que publi�e par la Free Software Foundation ; soit la version 2 de 
 *   la Licence, ou (� votre discr�tion) une version ult�rieure quelconque. 
 *   Ce programme est distribu� dans l'espoir qu'il sera utile, mais 
 *   SANS AUCUNE GARANTIE ; sans m�me une garantie implicite de COMMERCIABILITE 
 *   ou DE CONFORMITE A UNE UTILISATION PARTICULIERE. Voir la Licence Publique 
 *   G�n�rale GNU pour plus de d�tails. Vous devriez avoir re�u un exemplaire 
 *   de la Licence Publique G�n�rale GNU avec ce programme ; si ce n'est pas le 
 *   cas, �crivez � la Free Software Foundation Inc., 
 *   51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 ***************************************************************************/
 ini_set('magic_quotes_runtime', 0);
include('./includes/gpc.php');

include('info_bdd.php');
$pseudo3 = $_GET['pseudo'];
$code = $_GET['pass'];
$db = mysql_connect($host,$user,$mdpbdd);
mysql_select_db($bdd,$db);
$sql = 'SELECT code FROM '.$prefixtable.'membresvalid WHERE pseudo = "'.add_gpc($pseudo3).'"';
$req = mysql_query($sql);
$data = mysql_fetch_assoc($req);

if($code ==  $data['code'])
{
	mysql_free_result($req);
	$sql = 'UPDATE '.$prefixtable.'membres SET valid = "1" WHERE id = "'.add_gpc($pseudo3).'"';
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); 
	header('Location: index.php?page=regok2');
	$sql = 'DELETE FROM '.$prefixtable.'membresvalid WHERE pseudo = "'.add_gpc($pseudo3).'"';
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); 
}
else
{
	header('Location: index.php?page=erreur');
	mysql_free_result($req);
}
?>