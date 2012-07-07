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

 
		$sql = 'SELECT idm FROM '.$prefixtable.'groupemembre WHERE idg = '.intval($_GET['idg']).' AND stat = 1';
		$req = mysql_query($sql);
		while($data = mysql_fetch_assoc($req))
		{
			$sql2 = 'SELECT idm FROM '.$prefixtable.'groupemembre WHERE idm = '.$data['idm'].' AND stat = 1';
			$req2 = mysql_query($sql2);
			if(mysql_num_rows($req2) <= 1) 
			{
				$sql3 = 'SELECT rang FROM '.$prefixtable.'membres WHERE id = "'.$data['idm'].'"';
				$req3 = mysql_query($sql3);
				$data3 = mysql_fetch_assoc($req3);
				if($data3['rang'] == 3)
				{							
					$sql4 = 'UPDATE '.$prefixtable.'membres SET rang = 0  WHERE id = '.$data['idm'];
					$req4 = mysql_query($sql4) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); 
				}
			}
		}

		$sql = 'DELETE FROM '.$prefixtable.'groupemembre WHERE idg = "'.$_GET['idg'].'"';
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); 
		
		$sql = 'DELETE FROM '.$prefixtable.'groupe WHERE id = "'.$_GET['idg'].'"';
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); 
		
		$sql4 = 'UPDATE '.$prefixtable.'forum SET groupe = -3  WHERE groupe = '.$_GET['idg'];
		$req4 = mysql_query($sql4) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); 
		
		include('valid_group_conf.php');
	
?>