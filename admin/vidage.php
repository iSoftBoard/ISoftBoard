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
include_once('log.php');
$temps = time();

$sql = 'UPDATE '.$prefixtable.'forum SET temps = 0 , adernier = "-" , dernier = "-" , nbsujet = 0 , nbmessage = 0  WHERE id = "'.intval($_GET['id']).'"';
$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); 
							
$sql = 'DELETE FROM '.$prefixtable.'sondage WHERE sforumatt = '.intval($_GET['id']);
$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());

$sql = 'DELETE FROM '.$prefixtable.'voter WHERE sfofo = '.intval($_GET['id']);
$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());

$sql = 'DELETE FROM '.$prefixtable.'post WHERE idsfa = "'.intval($_GET['id']).'"';
$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());

include('conf_forum.php');
?>