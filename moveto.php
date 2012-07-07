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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Déplacement de sujet</title>
<link href="img/style.css" rel="stylesheet" type="text/css"></head>
<body>
<table  class="texte_base_gras" height="130" width="100%" border="0" style="border: 1px solid #FFFFFF" cellspacing="0" cellpadding="0">
	<tr align="center">
		<td height="29" class="titreforum">D&eacute;placer le sujet </td>
	</tr>
	<tr>
		<td align="center" class="cadre_clair" style="padding:13px">
		<?php
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
		elseif($rang != 1 && $rang != 2 && $rang != 3 || !is_numeric($_GET['ids'])) include('./includes/erreur.php');
		// Si ça en vaut la peine
		else
		{
			// Si c'est un chef de groupe qui veut modifier
			if($rang == 3)
			{
				// On cherche le forum de ce sujet
				$sql = 'SELECT idsfa FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['ids']);
				$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
				if(mysql_num_rows($req) != 0)
				{
					$data = mysql_fetch_assoc($req);
					// On cherche le groupe de ce forum
					$sql = 'SELECT groupe FROM '.$prefixtable.'forum WHERE id = '.intval($data['idsfa']);
					$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
					if(mysql_num_rows($req) != 0)
					{
						$data = mysql_fetch_assoc($req);
						// Si c'est pas un groupe particulier, on arrete là
						if($data['groupe'] == 0 || $data['groupe'] == -1 || $data['groupe'] == -2 || $data['groupe'] == -3) $modifier = false;
						else
						{ 
							// Si c'est un groupe particulier, on vérifie s'il en est chef
							$sql = 'SELECT id FROM '.$prefixtable.'groupemembre WHERE idm = "'.intval($idmembre).'" AND idg = "'.intval($data['groupe']).'" AND stat = "1"';
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
				if(!isset($_POST['select']) || $_POST['select'] == "non")
				{
					echo'
					<p>Selectionnez le forum dans lequel vous voulez d&eacute;placer ce sujet.</p>
					<form name="form1" method="post" action="moveto.php?ids='.$_GET['ids'].'&amp;f='.$_GET['f'].'">
					<select name="select" class="sbouton" size="12">';
					$sql = 'SELECT nom,id,fatt FROM '.$prefixtable.'forum ORDER BY position';
					$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
					mysql_close();
					while($data = mysql_fetch_assoc($req))
					{
						if($data['fatt'] != 0) echo '<option  value="'.$data['id'].'"';
						if($data['fatt'] == 0) echo '<option value="non" ';
						if($data['fatt'] == 0) echo '  disabled="disabled" ';
						if($data['id'] == $_GET['f']) echo ' selected ';
						if($data['fatt'] == 0) echo ' class="admin"';
						echo'>';
						if($data['fatt'] != 0) echo '...';
						echo htmlentities($data['nom']);
						echo'</option>'; 
					}
					echo '
					</select>
					<p><input type="checkbox" name="checkbox" value="checkbox" />Laisser un lien &agrave; l\'ancien emplacement</p>
					<input type="submit" name="Submit" value="Deplacer" class="bouton" />
					</form>
					';
				}
				else
				{
					$sql = 'SELECT id2,titre,idfa,idsfa,pseudode,nbr,idde FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['ids']);
					$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
					$data = mysql_fetch_assoc($req);

					$sql = 'SELECT fatt FROM '.$prefixtable.'forum WHERE id = '.intval($_POST['select']);
					$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
					$data2 = mysql_fetch_assoc($req);
					if($_POST['select'] != $data['idsfa'])
					{
						$sql = 'UPDATE '.$prefixtable.'post SET idfa = '.$data2['fatt'].', idsfa = '.intval($_POST['select']).' WHERE id2 = '.intval($_GET['ids']).' OR idsa = '.intval($_GET['ids']);
						$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
					
						$sql = 'SELECT tmpdernierpost,pseudodernier,tmppost FROM '.$prefixtable.'post WHERE idsfa = '.intval($data['idsfa']).' AND idsa = 0 AND `lock` < 1 ORDER BY tmppost DESC';
						$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
						$data18 = mysql_fetch_assoc($req);

						$sql = 'UPDATE '.$prefixtable.'forum SET temps = "'.$data18['tmppost'].'" , adernier = "'.addslashes($data18['pseudodernier']).'" , dernier = "'.addslashes($data18['tmpdernierpost']).'" , nbsujet = nbsujet-1, nbmessage = nbmessage-'.$data['nbr'].'  WHERE id = '.$data['idsfa'];
						if(mysql_num_rows($req) == 0)
						$sql = 'UPDATE '.$prefixtable.'forum SET temps = "0" , adernier = "-" , dernier = "-" , nbsujet = nbsujet-1, nbmessage = nbmessage-'.$data['nbr'].'  WHERE id = '.$data['idsfa'];
						$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());

						$sql = 'SELECT tmpdernierpost,pseudodernier,tmppost FROM '.$prefixtable.'post WHERE idsfa = '.$_POST['select'].' AND idsa = 0 AND `lock` < 1 ORDER BY tmppost DESC';
						$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
						$data18 = mysql_fetch_assoc($req);

						$sql = 'UPDATE '.$prefixtable.'forum SET temps = "'.$data18['tmppost'].'" , adernier = "'.addslashes($data18['pseudodernier']).'" , dernier = "'.addslashes($data18['tmpdernierpost']).'" , nbsujet = nbsujet+1, nbmessage = nbmessage+'.$data['nbr'].'  WHERE id = '.intval($_POST['select']);
						$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());

						$sql = 'DELETE FROM '.$prefixtable.'post WHERE idsfa = '.intval($_POST['select']).' AND `lock` = '.intval($_GET['ids']);
						$req = mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error());
						if(isset($_POST['checkbox']))
						{
							$sql = 'INSERT INTO '.$prefixtable.'post (`titre`, `texte`, `idfa`, `idsfa`, `idsa`, `pseudode`, `idde`, `sondage`, `nbr`, `tmppost`, `pseudodernier`, `ip`, `edit`, `tmpdernierpost`, `lock`, `tmpsave`)  VALUES ("'.addslashes($data['titre']).'","","'.$data['idfa'].'","'.$data['idsfa'].'","0","'.addslashes($data['pseudode']).'","'.$data['idde'].'","0","0","'.time().'","'.addslashes($_SESSION['pseudo']).'","","","0","'.intval($_GET['ids']).'","0")';
							$req = mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error()); 
						}
						// $sql = 'UPDATE '.$prefixtable.'post SET tmppost = '.time().'  WHERE id2 = '.$_GET['ids'];
						// $req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
						echo'
						<p>Sujet transfer&eacute; avec succ&egrave;s.</p>
						<p><a href="javascript:close();">Fermer la fen&ecirc;tre</a></p>';
					}
					else
					{
						echo'<p>Vous avez deplace ce sujet<br /> dans son forum actuel !!!</p><p><a href="javascript:close();">Fermer la fen&ecirc;tre</a></p>';
					}
					mysql_close();
				}
			}
			else
			{
				echo 'Retournez sur le forum! <p><a href="javascript:close();">Fermer la fen&ecirc;tre</a></p>';
			}
		}
		?>
		</td>
	</tr>
</table>
</body>
</html>          