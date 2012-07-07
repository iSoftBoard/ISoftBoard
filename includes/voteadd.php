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
 
if(!defined('IN_SOFTBB')) exit('Not in SoftBB');
if(!is_numeric($_GET['ids'])) exit('a');
if(!isset($_SESSION['idlog'])) exit('Erreur');

$sql = 'SELECT * FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['ids']).' AND `lock` < 1';
$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
$requse++;
$numreq = mysql_num_rows($req);
if(empty($numreq)) exit('Erreur');
$dat = mysql_fetch_assoc($req);
$idsfa = $dat['idsfa'];
$idfa = $dat['idfa'];
$titresujet = $dat['titre'];
$editlock = $dat['lock'];

$sql = 'SELECT groupe,nom,m,mg FROM '.$prefixtable.'forum WHERE id = '.intval($idsfa);
$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
$requse++;
$data3 = mysql_fetch_assoc($req);
$nomsujetforum = $data3['nom'];
if($data3['groupe'] == -4   && $rang != 1 && $rang != 2 && $data3['m'] != 4 && $data3['m'] != 3) exit('1');

if($data3['groupe'] == -1  && $rang != 1 && $rang != 2 || $data3['groupe'] == -3 && $rang != 1 && $rang != 2)
{
	exit('3');
}
if($data3['groupe'] != 0)
{
	$sql = 'SELECT stat FROM '.$prefixtable.'groupemembre WHERE idg = '.intval($data3['groupe']).' AND idm = '.intval($idmembre);
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
	$requse++;
	$autorisation = mysql_num_rows($req);
	$d = mysql_fetch_assoc($req);
	////////////////////////////////////////
	if($autorisation == 0 && $rang != 1 && $rang != 2) { if($data3['m'] != 3 && $data3['m'] != 4) exit('2'); } 
	if($autorisation == 1 && $d['stat'] == 0 && $rang != 1 && $rang != 2) { if($data3['mg'] != 3 && $data3['mg'] != 4) exit(3); }  else $rang = 1;

	$data2 = mysql_fetch_assoc($req);
	$statgroupe = $data2['stat'];
	if($statgroupe == 1) $rang = 1;
}

if($editlock == -1  && $rang != 1 && $rang != 2 || $editlock == -2 && $rang != 1 && $rang != 2) exit('4');

$sql = 'SELECT idvoteur FROM '.$prefixtable.'voter WHERE idpost = '.intval($_GET['ids']).' AND idvoteur = '.$_SESSION['idlog'];
$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());  $requse++;
$datas = mysql_fetch_assoc($req);

$sql = 'SELECT tmpvote FROM '.$prefixtable.'sondage WHERE idpost = '.intval($_GET['ids']);
$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());  $requse++;
$datas2 = mysql_fetch_assoc($req);  $requse++;
if(!empty($datas['idvoteur']) || ($datas2['tmpvote'] < time() && $datas2['tmpvote'] != 0))
{
	include('./includes/erreur.php');
}
elseif(empty($_POST['id_option']))
{
	echo '
<table class="texte_base_gras" width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="cadre1_bas"  style="padding:10px">'; echo '<a href="index.php">Index : '.htmlentities($nomduforum).'</a>'; echo'</td>
    </tr>
</table>

<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr align="center">
		<td height="29" class="titreforumend">Sondage</td>
	</tr>
	<tr>              
		<td  align="center" class="cadre1_bas" style="padding:30px"><p>Vous devez selectionnez une option de vote pour voter</p><p><a href="index.php?page=post&amp;ids='.$_GET['ids'].'">&gt;&gt; Retour au sujet &lt;&lt;</a></p></td>
	</tr>
</table>
	';
}
else
{
	$sql = 'INSERT INTO '.$prefixtable.'voter ( `idvoteur` , `idpost` , `fofo` , `sfofo` ) VALUES ('.intval($_SESSION['idlog']).','.intval($_GET['ids']).','.$idfa.','.$idsfa.')';
    $req = mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error()); $requse++;
	
	$sql = 'UPDATE '.$prefixtable.'sondage SET nbvote = nbvote+1 WHERE idpost = '.intval($_GET['ids']).' AND nboption > 0 OR idpost = '.intval($_GET['ids']).' AND idsond = '.intval($_POST['id_option']);
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); $requse++;
	echo '
<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr align="center">
		<td height="29" class="titreforumend">Vote ajouté avec succès</td>
	</tr>
	<tr>
		<td align="center" class="cadre1_bas" style="padding:30px"><p>Votre vote a été pris en compte</p> <p><a href="index.php?page=post&amp;ids='.$_GET['ids'].'">&gt;&gt; Retour au post &lt;&lt;</a></p></td>
	</tr>
</table>
	';
}			
?>