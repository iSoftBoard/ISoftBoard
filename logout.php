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
 
session_start();
include('info_bdd.php');
if(isset($_SESSION['idlog']))
{
	$db = mysql_connect($host,$user,$mdpbdd);
	mysql_select_db($bdd,$db);
	$sql = 'UPDATE '.$prefixtable.'membres SET co = "0" WHERE id = "'.intval($_SESSION['idlog']).'"';
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); 
	$_SESSION = array();
}

$_SESSION['idlog'] = "";

if(isset($_COOKIE));
{
	setcookie("idlog","",time()-(365*24*3600));
	setcookie("mdp","",time()-(365*24*3600));
}
setcookie("lastvisit","",time()-(365*24*3600));

mysql_close();
header('Location: index.php');
?>