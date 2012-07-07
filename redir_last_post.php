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
ini_set("register_globals","off"); 
include('info_bdd.php');
include('info_options.php');
$db = mysql_connect($host,$user,$mdpbdd);
mysql_select_db($bdd,$db);

$sql = 'SELECT id2,idsa FROM '.$prefixtable.'post WHERE idsfa = "'.intval($_GET['forum']).'" AND (idsa !=0 OR idsa = 0 AND nbr = 0) ORDER BY tmppost DESC';
$req = mysql_query($sql)  or die('Erreur SQL !'.$sql.'<br />'.mysql_error());
$data = mysql_fetch_assoc($req); 

if(mysql_num_rows($req) == 0) header('Location: index.php?page=erreur');

if($data['idsa'] != 0)
{
	$sql = 'SELECT nbr FROM '.$prefixtable.'post WHERE id2 = '.$data['idsa'];
	$req = mysql_query($sql)  or die('Erreur SQL !'.$sql.'<br />'.mysql_error());
	$data2 = mysql_fetch_assoc($req); 

	header('Location: index.php?page=post&ids='.$data['idsa'].'&pg='.(ceil(($data2['nbr']+1)/$postparpageaff)-1).'#'.$data['id2']);
}

else header('Location: index.php?page=post&ids='.$data['id2']);
?>