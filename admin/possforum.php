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

$sql = 'SELECT fatt,position FROM '.$prefixtable.'forum WHERE id = '.intval($_GET['id']).' AND fatt != 0';
$req = mysql_query($sql);
$data = mysql_fetch_assoc($req);

$sql = 'SELECT nbsf,position FROM '.$prefixtable.'forum WHERE id = '.$data['fatt'];
$req1 = mysql_query($sql);
$data2 = mysql_fetch_assoc($req1);

if(mysql_num_rows($req) != 0 && ($_GET['act'] == 'up' && $data['position'] > $data2['position']+1 || $_GET['act'] == 'down' && $data['position'] < $data2['position']+$data2['nbsf']) )
{
	if($_GET['act'] == 'up')
	{
		$pos1 = $data['position']-1;
		$pos2 = $data['position'];
	}
	elseif($_GET['act'] == 'down')
	{
		$pos1 = $data['position']+1;
		$pos2 = $data['position'];
	}

	if($_GET['act'] == 'down' || $_GET['act'] == 'up')
	{
		$sql = 'UPDATE '.$prefixtable.'forum SET position = '.$pos2.'  WHERE position = "'.$pos1.'"';
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		
		$sql = 'UPDATE '.$prefixtable.'forum SET position = '.$pos1.'  WHERE id = "'.intval($_GET['id']).'"';
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
	}
}
include('conf_forum.php'); 
?>