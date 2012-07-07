<?php
/***************************************************************************
 *
 *   SoftBB - Forum de discussion 
 *   Version : 0
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
include_once('log.php');
$temps = time();

$sql = 'SELECT nbsf,position FROM '.$prefixtable.'forum WHERE id = '.intval($_GET['id']).' AND fatt = 0';
$req = mysql_query($sql);

if(mysql_num_rows($req) != 0)
{
	$data = mysql_fetch_assoc($req);

	$sql = 'DELETE FROM '.$prefixtable.'forum WHERE id = "'.$_GET['id'].'" OR fatt = "'.$_GET['id'].'"';
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); 
	
	$sql = 'UPDATE '.$prefixtable.'forum SET positionf = positionf-1  WHERE position > "'.$data['position'].'" AND fatt = 0';
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());

	$sql = 'UPDATE '.$prefixtable.'forum SET position = position-'.($data['nbsf']+1).'  WHERE position > "'.$data['position'].'"';
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
	
	$sql = 'DELETE FROM '.$prefixtable.'post WHERE idfa = "'.$_GET['id'].'"';
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); 
	
	$sql = 'DELETE FROM '.$prefixtable.'sondage WHERE forumatt = '.intval($_GET['id']);
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
	
	$sql = 'DELETE FROM '.$prefixtable.'voter WHERE fofo = '.intval($_GET['id']);
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
}
include('conf_forum.php');
?>