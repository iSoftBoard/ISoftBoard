<?php
/***************************************************************************
 *
 *   SoftBB - Forum de discussion 
 *   Version : 0
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
 
$sql = 'SELECT position FROM '.$prefixtable.'forum WHERE id = '.intval($_GET['id']).' AND fatt = 0';
$req = mysql_query($sql);
$data = mysql_fetch_assoc($req);
$nom = trim($_POST['nom']);

if(mysql_num_rows($req) == 0) echo '<p>Il semble que la cat�gorie dans laquelle vous voulez ajouter un forum n\'existe pas/plus</p>';

else
{ 
	$temps = time();
	
	$sql = 'UPDATE '.$prefixtable.'forum SET position = position+1  WHERE position > "'.$data['position'].'"';
	if(!empty($nom)) $req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); 
	
	$sql = 'UPDATE '.$prefixtable.'forum SET nbsf = nbsf+1  WHERE id = "'.intval($_GET['id']).'"';
	if(!empty($nom)) $req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); 
	
	if( strlen(add_gpc($_POST['description'])) > 255) $description = substr($_POST['description'],0,255);
	else $description = $_POST['description'];

	$sql = 'INSERT INTO '.$prefixtable.'forum (`nom`, `description`, `groupe`, `nbsujet`, `nbmessage`, `dernier`, `adernier`, `temps`, `fatt`, `position`, `nbsf`, `positionf`, `v`, `m`, `mg`) VALUES ("'.add_gpc($_POST['nom']).'","'.add_gpc($description).'","0","0","0","-","-","'.$temps.'","'.intval($_GET['id']).'","'.($data['position']+1).'","0","0","0","0","0")';
	if(!empty($nom)) $req = mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error()); 

include('valid_foru_conf.php'); 
}
?>