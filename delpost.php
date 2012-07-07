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

session_start();
if(empty($_SESSION['pseudo'])) exit();
$pseudo = $_SESSION['pseudo'];
include('info_bdd.php');
$db = mysql_connect($host,$user,$mdpbdd);
mysql_select_db($bdd,$db);
$sql = 'SELECT id,rang FROM '.$prefixtable.'membres WHERE id = "'.intval($_SESSION['idlog']).'"';
$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
if(mysql_num_rows($req) == 0) exit();
$data = mysql_fetch_assoc($req);
$rang = $data['rang'];
$idmembre = $data['id'];

if(empty($pseudo))
{
	include('./includes/erreur.php');
}
// Vérifie si ça vaut la peine d'aller plus loin
elseif($rang != 1 && $rang != 2 && $rang != 3 || !is_numeric($_GET['id2'])) include('./includes/erreur.php');
// Si ça en vaut la peine
else
{
	// Si c'est un chef de groupe qui veut modifier
	if($rang == 3)
	{
	// On cherche le forum de ce sujet
	$sql = 'SELECT idsfa FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['id2']);
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		if(mysql_num_rows($req) != 0)
		{
			$data = mysql_fetch_assoc($req);
			// On cherche le groupe de ce forum
			$sql = 'SELECT groupe FROM '.$prefixtable.'forum WHERE id = '.$data['idsfa'];
			$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
			if(mysql_num_rows($req) != 0)
			{
				$data = mysql_fetch_assoc($req);
				// Si c'est pas un groupe particulier, on arrete là
				if($data['groupe'] == 0 || $data['groupe'] == -1 || $data['groupe'] == -2 || $data['groupe'] == -3) $modifier = false;
				else
				{ 
					// Si c'est un groupe particulier, on vérifie s'il en est chef
					$sql = 'SELECT id FROM '.$prefixtable.'groupemembre WHERE idm = "'.$idmembre.'" AND idg = "'.$data['groupe'].'" AND stat = "1"';
					$req = mysql_query($sql);
					if(mysql_num_rows($req) == 0) $modifier = false;
					else $modifier = true;
				}
			}
			else
			{
				$modifier = false;
			}
		}
		else
		{
			$modifier = false;
		}
	}
	// Les modos et admins sont d'office acceptés
	else $modifier = true;
	// On va faire ce qu'il faut
	if($modifier)
	{
		//////////////////////////////////////////////////////////////////////////////////////
		$sql = 'SELECT idsfa,idfa,idsa,nbr FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['id2']);
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		if(mysql_num_rows($req) == 0) exit('1'); //Pas trop logique que ça bug
		$data = mysql_fetch_assoc($req);
		if($data['idsa'] > 0)
		{ 
			$sql = 'DELETE FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['id2']);
			$req = mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error()); 
		
			$sql = 'SELECT tmppost,pseudode FROM '.$prefixtable.'post WHERE idsa = '.$data['idsa'].' ORDER BY tmppost DESC';
			$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
			$data2 = mysql_fetch_assoc($req);
		
			$sql = 'UPDATE '.$prefixtable.'post SET tmppost = '.$data2['tmppost'].', pseudodernier = "'.addslashes($data2['pseudode']).'"  , nbr = nbr-1 WHERE id2 = '.$data['idsa'];
			if(mysql_num_rows($req) == 0)
			{
				$sql = 'SELECT tmppost,pseudode,tmpsave FROM '.$prefixtable.'post WHERE id2 = '.$data['idsa'].' ORDER BY tmppost DESC';
				$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
				$data2 = mysql_fetch_assoc($req);
			
				$sql = 'UPDATE '.$prefixtable.'post SET tmppost = '.$data2['tmpsave'].', pseudodernier = "'.addslashes($data2['pseudode']).'" , nbr = nbr-1 WHERE id2 = '.$data['idsa'];
			}
			$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());

			$sql = 'SELECT tmppost,pseudodernier,tmpdernierpost FROM '.$prefixtable.'post WHERE idsfa = '.$data['idsfa'].' AND `lock` < 1 AND idsa <1 ORDER BY tmppost DESC';
			$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
			$data3 = mysql_fetch_assoc($req);
		
			$sql = 'UPDATE '.$prefixtable.'forum SET temps = '.$data3['tmppost'].', adernier = "'.addslashes($data3['pseudodernier']).'" , dernier = "'.addslashes($data3['tmpdernierpost']).'" , nbmessage = nbmessage-1 WHERE id = '.$data['idsfa'];
			$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
			header('Location: index.php?page=delvalid&id2='.$data['idsa']);
		}
		else
		{
			$sql = 'DELETE FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['id2']).' OR `lock` = '.intval($_GET['id2']).' OR idsa = '.intval($_GET['id2']);
			$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		
			$sql = 'DELETE FROM '.$prefixtable.'sondage WHERE idpost = '.intval($_GET['id2']);
			$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());

			$sql = 'DELETE FROM '.$prefixtable.'voter WHERE idpost = '.intval($_GET['id2']);
			$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());

			$sql = 'SELECT tmppost,pseudodernier,tmpdernierpost FROM '.$prefixtable.'post WHERE idsfa = '.$data['idsfa'].' AND `lock` < 1 AND idsa <1 ORDER BY tmppost DESC';
			$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
			$data3 = mysql_fetch_assoc($req);
		
			$sql = 'UPDATE '.$prefixtable.'forum SET temps = '.$data3['tmppost'].', adernier = "'.addslashes($data3['pseudodernier']).'" , dernier = "'.addslashes($data3['tmpdernierpost']).'" , nbsujet = nbsujet-1 , nbmessage = nbmessage-'.$data['nbr'].' WHERE id = '.$data['idsfa'];
			if(mysql_num_rows($req) == 0)
			{
				$sql = 'UPDATE '.$prefixtable.'forum SET temps = 0, adernier = "-" , dernier = "-" , nbsujet = "0" , nbmessage = "0" WHERE id = '.$data['idsfa'];
			}
			$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
			header('Location: index.php?page=delvalid2&id2='.$data['idsfa']);
		}
		/*
		$sql = 'UPDATE '.$prefixtable.'post SET idfa = '.$data2['fatt'].', idsfa = '.$_POST['select'].' WHERE id2 = '.intval($_GET['id2']).' OR idsa = '.intval($_GET['id2']);
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
	
		$sql = 'UPDATE '.$prefixtable.'forum SET nbsujet = nbsujet-1, nbmessage = nbmessage-'.$data['nbr'].'  WHERE id = '.$data['idsfa'];
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
	
		$sql = 'UPDATE '.$prefixtable.'forum SET temps = '.time().', nbsujet = nbsujet+1, nbmessage = nbmessage+'.$data['nbr'].'  WHERE id = '.$_POST['select'];
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
	
		$sql = 'DELETE FROM '.$prefixtable.'post WHERE idsfa = '.$_POST['select'].' AND `lock` = '.intval($_GET['id2']);
		$req = mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error()); 
	
		$sql = 'INSERT INTO '.$prefixtable.'post VALUES("","'.addslashes($data['titre']).'","","'.$data['idfa'].'","'.$data['idsfa'].'","0","'.$data['pseudode'].'","'.$idmembre.'","0","0","'.time().'","'.$_SESSION['pseudo'].'","'.datefct().'","0","'.datefct().'","'.intval($_GET['id2']).'")';
		$req = mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error()); 

		$sql = 'UPDATE '.$prefixtable.'post SET tmppost = '.time().'  WHERE id2 = '.intval($_GET['id2']);
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		*/
	////////////////////////////////////////////////////////////////////////////////////////
	}
	else
	{
		header('Location: index.php?page=erreur');
	}
}
?>     