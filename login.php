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
 
session_start();
$_SESSION = array();

include('info_bdd.php');

include('./includes/gpc.php');

if(!isset($_POST['pseudolog'])) exit('Il n\'y a pas de formulaire');

$pseudolog = trim($_POST['pseudolog']);

$sql = 'SELECT id,temps,pseudo FROM '.$prefixtable.'membres WHERE pseudo = "'.add_gpc($pseudolog).'" AND `mdp` = "'.md5($_POST['mdp']).'" AND valid = "1"';

$db = mysql_connect($host,$user,$mdpbdd);
mysql_select_db($bdd,$db);

$req = mysql_query($sql);

if(mysql_num_rows($req) == 1) 
{
	
		$data = mysql_fetch_assoc($req); 

		$_SESSION['pseudo'] = $data['pseudo'];
		$_SESSION['idlog'] = $data['id'];
		$_SESSION['ip_anti_vol'] = $_SERVER['REMOTE_ADDR'];
		
		mysql_free_result($req);
		
		$sql = 'UPDATE '.$prefixtable.'membres SET temps = "'.time().'" , co = "1" WHERE id = "'.intval($data['id']).'"';
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		 
		if(isset($_POST['souvenir']) && $_POST['souvenir'] == "auto") 
		{
			$expire = 365*24*3600;
			setcookie("idlog",$data['id'],time()+$expire);
			setcookie("mdp",md5($_POST['mdp']),time()+$expire);
		}

		$redir = 'Location: index.php?page=indexforum';
		$_SESSION['lastvisit'] = $data['temps'];

	mysql_close();
	header($redir);
}
else
{

	session_unset();
	session_destroy();
	mysql_free_result($req);
	mysql_close();

	header('Location: index.php?page=connexion&erreur=3');
}
?>