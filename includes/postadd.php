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
 
function ippp ()
{
	$ipe = explode('.',$_SERVER['REMOTE_ADDR']);
	$j = count($ipe); $ip = "";
	for($i=0;$i<$j;$i++)
	{
		$ip .= dechex($ipe[$i]);
		if($i != $j-1) $ip .='$';
	}
	return $ip;
}
if(!isset($_SESSION['pseudo']) || empty($_SESSION['pseudo'])) exit('1');
if(isset($_GET['idf']))
{ 
	if(!is_numeric($_GET['idf'])) exit();

	$act = "post";
			
	$sql = 'SELECT groupe,nom,fatt,m,mg FROM '.$prefixtable.'forum WHERE fatt != 0 AND id = '.intval($_GET['idf']);
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
	$requse++;
	$numreq = mysql_num_rows($req);
	if(empty($numreq))
	{
		include('./includes/erreur.php');
		exit();
	} 

	$data3 = mysql_fetch_assoc($req);
	$nomsujetforum = $data3['nom'];
			
	if($data3['groupe'] == -1  && $rang != 1 && $rang != 2 || $data3['groupe'] == -3 && $rang != 1 && $rang != 2)
	{
		exit('2');
	} 
			
	if($data3['groupe'] == -4   && $rang != 1 && $rang != 2 && $data3['m'] != 4 && $data3['m'] != 2) exit('5');
			
	if($data3['groupe'] != 0 && $data3['groupe'] != -2)
	{
		$sql = 'SELECT stat FROM '.$prefixtable.'groupemembre WHERE idg = '.$data3['groupe'].' AND idm = '.$idmembre;
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		$requse++;
		$autorisation = mysql_num_rows($req);
		$d = mysql_fetch_assoc($req); 
		////////////////////////////////////////
		if($autorisation == 0 && $rang != 1 && $rang != 2)
		{
			if($data3['m'] != 2 && $data3['m'] != 4) exit('6');
		}
		if($autorisation == 1 && $d['stat'] == 0 && $rang != 1 && $rang != 2)
		{
			if($data3['mg'] != 2 && $data3['mg'] != 4) exit('7');
		}
		//if(empty($autorisation)  && $rang != 1 && $rang != 2) { exit(); }
	}

}
elseif(isset($_GET['edit']))
{
	if(!is_numeric($_GET['edit'])) exit();
		 
	$act = "edit";
	$sql = 'SELECT * FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['edit']);
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
	$requse++;
	$numreq = mysql_num_rows($req);
					
	if(empty($numreq)) exit();
	
	$dat = mysql_fetch_assoc($req);
	$idsfa = $dat['idsfa'];
	$titresujet = $dat['titre'];
	$editlock = $dat['lock'];
	$nedittitre = $dat['idsa'];
					
					
	if($dat['idde'] == $_SESSION['idlog'] && $rang != 2 && $rang != 1)
	{
		$rang = 1;
		$editaffmod = 1;
		$reptimeplus = true;
	}
					
	$sql = 'SELECT groupe,nom,m,mg FROM '.$prefixtable.'forum WHERE id = '.intval($idsfa);
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
	$requse++;
	$data3 = mysql_fetch_assoc($req);
	$nomsujetforum = $data3['nom'];
					
	if($data3['groupe'] == 0  && $rang != 1 && $rang != 2 || $data3['groupe'] == -1  && $rang != 1 && $rang != 2 || $data3['groupe'] == -3 && $rang != 1 && $rang != 2)
	{
		exit('3');
	} 
					
	if($data3['groupe'] != 0   && $rang != 1 && $rang != 2)
	{
		$sql = 'SELECT stat FROM '.$prefixtable.'groupemembre WHERE idg = '.intval($data3['groupe']).' AND idm = '.intval($idmembre).' AND stat = 1';
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		$requse++;
		$autorisation = mysql_num_rows($req);

		if(empty($autorisation)  && $rang != 1 && $rang != 2)
		{
			exit('6');
		}
		else
		{
			$rang = 1;
		}
	}
}
else
{
	if(!is_numeric($_GET['ids'])) exit('a');
	if(isset($_GET['cit']))
	{
		if(!is_numeric($_GET['cit'])) exit('b');
	}
	$act = "rep";
	$sql = 'SELECT * FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['ids']).' AND `lock` < 1 AND idsa = 0';
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
	$requse++;
	$numreq = mysql_num_rows($req);
	if(empty($numreq)) exit('Erreur');
	$dat = mysql_fetch_assoc($req);
	$idsfa = $dat['idsfa'];
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
	if($data3['groupe'] > 0)
	{
		$sql = 'SELECT stat FROM '.$prefixtable.'groupemembre WHERE idg = '.intval($data3['groupe']).' AND idm = '.intval($idmembre);
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		$requse++;
		$autorisation = mysql_num_rows($req);
		$d = mysql_fetch_assoc($req);
		
		////////////////////////////////////////
		if($autorisation == 0 && $rang != 1 && $rang != 2)
		{
			if($data3['m'] != 3 && $data3['m'] != 4) exit('2');
		} 

		if($autorisation == 1 && $d['stat'] == 0 && $rang != 1 && $rang != 2)
		{
			if($data3['mg'] != 3 && $data3['mg'] != 4) exit(3);
		}
		else $rang = 1;

		//if(empty($autorisation)  && $rang != 1 && $rang != 2) { exit('6'); }
		$data2 = mysql_fetch_assoc($req);
		$statgroupe = $data2['stat'];
		if($statgroupe == 1) $rang = 1;
	}
	if($editlock == -1  && $rang != 1 && $rang != 2 || $editlock == -2 && $rang != 1 && $rang != 2) exit('4');
}
			
$timemin = time()-$tmpfreepost;
if($tempspostlast >= $timemin && $rang != 1 && $rang != 2 || $tempspostlast >= $timemin && isset($reptimeplus) && $rang != 2)
{
	echo '
<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr align="center">
			<td height="29" class="titreforumend">D&eacute;lais trop court.</td>
		</tr>
		<tr>
			<td align="center" class="cadre1_bas" style="padding:30px"><p>Vous devez attendre '.$tmpfreepost.' secondes avant de re-poster.</p></td>
		</tr>
</table>
	';
}
else
{
	if(empty($_POST['texte']) || empty($_POST['titre']) && isset($_GET['idf']) || (!empty($_POST['quest_sondage']) && empty($_POST['option_1'])) || !isset($_POST['sendage']))
	{
		echo'
<form action="index.php?page=postadd';
		
		if(isset($_GET['idf'])) echo '&amp;idf='.$_GET['idf'];
		elseif(isset($_GET['edit'])) echo '&amp;edit='.$_GET['edit'].'&amp;pg='.$_GET['pg'].'&amp;ids='.$_GET['ids'];
		else echo '&amp;ids='.$_GET['ids'];
		echo'
		" method="post" enctype="multipart/form-data" name="news">
<table class="texte_base_gras" width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="cadre1_bas" style="padding:10px">'; echo '<a href="index.php">Index : '.htmlentities($nomduforum).'</a>'; echo'</td>
	</tr>
</table>
		';
		
		if( isset($_POST['previsu']) && !empty($_POST['texte']))
		{
			echo '
<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr align="center">
		<td height="29" class="titreforumend">Prévisualisation</td>
	</tr>

	<tr>
		<td class="alternate1" style="padding:30px">'.bbcode(nl2br(sit(($_POST['texte'])))).'</td>
	</tr>
</table>
			';
		}
		
		echo'
<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr align="center">
		<td height="29" class="titreforumend">
		'; 
		if($act == "post") { echo 'Ajouter un sujet'; }	else { echo 'Ajouter une reponse'; }
		echo'
		</td>
	</tr>
</table>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="texte_base_gras">';
		if(  ((!empty($_POST['quest_sondage']) && empty($_POST['option_1'])) || (empty($_POST['texte'])) || (empty($_POST['titre']) && isset($_GET['idf']) )) && isset($_POST['sendage'])  )
		{
			echo '
	<tr>
		<td class="cadre_clair" style="padding:4px"><span class="red">Erreur</span></td>
		<td class="cadre1_bas" style="padding:4px">';
			if(!empty($_POST['quest_sondage']) && empty($_POST['option_1'])) echo 'Vous devez mettre plus d\'une option pour le sondage<br />';
			if(empty($_POST['titre']) && isset($_GET['idf']) ) echo'Vous devez mettre un titre<br />';
			if(empty($_POST['texte'])) echo'Vous devez mettre un contenu';
				echo '
		</td>
	</tr>
				';
		}
		echo '
	<tr>
		<td class="cadre_clair" style="padding:4px">';
			if($act == "post") { echo 'Forum'; } else { echo 'Sujet'; }
			echo'
		</td>
		<td class="cadre1_bas" style="padding:4px">
			';
			if($act == "post") { echo bbcode(htmlentities(($nomsujetforum))); } else { echo '<span class="admin">'.bbcode(htmlentities(($nomsujetforum))).'</span> '.sit((htmlentities('   ||    '.$titresujet))); }
			echo'
		</td>
	</tr>
	<tr>
		<td class="cadre_clair" style="padding:4px"><label for="titre">Titre</label></td>
		<td class="cadre1_bas" style="padding:4px">
			<input id="titre" maxlength="64" type="text"
			'; 
			if(isset($_POST['titre'])) echo ' value="'.sit(htmlentities(strip_gpc($_POST['titre']))).'" ';
			elseif(isset($_GET['edit']))
			{
				$sql = 'SELECT titre,pseudo,texte,idsa FROM '.$prefixtable.'post LEFT JOIN '.$prefixtable.'membres AS m ON pseudode=m.id WHERE id2 = '.intval($_GET['edit']);
				$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
				$requse++;
				$dated = mysql_fetch_assoc($req);
				$nedittitre = $dated['idsa'];
				echo 'value="'.sit(htmlentities(($dated['titre']))).'"';
			}
			else { echo''; }
			echo' class="bouton" name="titre" style="width:400px" tabindex="80" />
		</td>
	</tr>
			';
			if(isset($_GET['edit'])  && !isset($_POST['texte']))
			{
				echo'
	<tr>
		<td class="cadre_clair" style="padding:4px">De</td>
		<td class="cadre1_bas" style="padding:4px">'.htmlentities(($dated['pseudo'])).'</td>
	</tr>
	<tr>
				';
			}
			echo'
		<td width="160" valign="top" class="cadre_clair" style="padding:4px"><label for="texte">Corps du message</label>
			';
			$vs=0;
			if($emoticonnb != 0)
			{
				echo'
			<p>
				<table border="0" cellpadding="0" cellspacing="0" align="center" style="padding:10px">
					<tr>
				';
				if($emoticonnb > 20) $max=20;
				else $max=$emoticonnb;
				for($affem=0;$affem<$max;$affem++)
				{
					$vs++;
					echo'
						<td class="smileys"><a href="javascript:emoticon(\''.$emoticonc[$affem].'\')"><img src="./'.$emoticonv[$affem].'" alt="'.$emoticonc[$affem].'" title="Very Happy" border="0"></a></td>
					';
					if($vs>=4)
					{
						echo'
					</tr>
					<tr>
						';
						$vs=0;
					}
				}
				echo'
					</tr>';
				if($emoticonnb > 20) echo '					<tr>
						<td colspan="4" align="center" style="padding:5px"><a href="" onclick="window.open(\'emote.php\', \'\', \'HEIGHT=450,resizable=yes,scrollbars=yes,WIDTH=250\');return false;">Voir plus <br />de Smilies</a></td>
					</tr>';
				echo '</table>
			</p>
				';
			}
			echo'
		</td>
		<td class="cadre1_bas" style="padding:4px">
			<input class="bouton" type="button" value="b" onclick="storeCaret(\'b\')" />
			<input class="bouton" type="button" value="i" onclick="storeCaret(\'i\')" />
			<input class="bouton" type="button" value="u" onclick="storeCaret(\'u\')" />
			<input class="bouton" type="button" value="s" onclick="storeCaret(\'s\')" />
			<input class="bouton" type="button" value="quote" onclick="storeCaret(\'quote\')" />
			<input class="bouton" type="button" value="img" onclick="img()" />
			<input class="bouton" type="button" value="url" onclick="lien()" />
			<input class="bouton" type="button" value="code" onclick="storeCaret(\'code\')" />
			<input class="bouton" type="button" value="spoil" onclick="storeCaret(\'spoil\')" />
			
			<select name="coul" onchange="color(\'[color=\' + this.form.coul.options[this.form.coul.selectedIndex].value + \']\');this.selectedIndex=0;"  class="sbouton">
				<option style="color: black;" value="#444444" class="genmed">Couleur</option>
				<option style="color: darkred;" value="darkred" class="genmed">Rouge fonc&eacute;</option>
				<option style="color: red;" value="red" class="genmed">Rouge</option>
				<option style="color: orange;" value="orange" class="genmed">Orange</option>
				<option style="color: brown;" value="brown" class="genmed">Marron</option>
				<option style="color: yellow;" value="yellow" class="genmed">Jaune</option>
				<option style="color: green;" value="green" class="genmed">Vert</option>
				<option style="color: olive;" value="olive" class="genmed">Olive</option>
				<option style="color: cyan;" value="cyan" class="genmed">Cyan</option>
				<option style="color: blue;" value="blue" class="genmed">Bleu</option>
				<option style="color: darkblue;" value="darkblue" class="genmed">Bleu fonc&eacute;</option>
				<option style="color: indigo;" value="indigo" class="genmed">Indigo</option>
				<option style="color: violet;" value="violet" class="genmed">Violet</option>
				<option style="color: white;" value="white" class="genmed">Blanc</option>
				<option style="color: black;" value="black" class="genmed">Noir</option>
			</select>
			<br />
			<textarea name="texte" class="tbouton" id="texte" tabindex="90">';
				if(isset($_GET['cit']))
				{
					$sql = 'SELECT m.pseudo,texte FROM '.$prefixtable.'post  LEFT JOIN '.$prefixtable.'membres AS m ON pseudode=m.id WHERE id2 = '.intval($_GET['cit']).' AND idsa = '.intval($_GET['ids']);
					if($_GET['cit'] == $_GET['ids'])
					$sql = 'SELECT m.pseudo,texte FROM '.$prefixtable.'post  LEFT JOIN '.$prefixtable.'membres AS m ON pseudode=m.id WHERE id2 = '.intval($_GET['cit']);
					$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); mysql_close();
					$requse++;
					if(mysql_num_rows($req) == 0 && $_GET['cit'] != $_GET['ids'] ) { echo '[LEGERE ERREUR] :)'; }
					else
					{
						$dat1 = mysql_fetch_assoc($req);
						echo '[quote="'.htmlentities(($dat1['pseudo'])).'"]'.(sit(htmlentities($dat1['texte']))).'[/quote]';
					}
				}
				elseif(isset($_GET['edit']) && !isset($_POST['texte'])) { echo sit(htmlentities(($dated['texte']))); }
				else { if(isset($_POST['texte'])) echo sit(htmlentities(strip_gpc($_POST['texte']))); }
				echo'</textarea>';
			echo'
		</td>
	</tr>
</table>
			';
	if(isset($_GET['idf']) && $nbsondage > 1)
	{
		echo'
<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr align="center">
		<td height="29" class="titreforumend" colspan="2">Ajouter un sondage</td>
	</tr>
	<tr>
		<td class="cadre_clair" height="30" width="160" style="padding:4px"><label for="q_sond">Question du sondage</label></td>
		<td class="cadre1_bas" style="padding:4px"><input id="q_sond" type="text" maxlength="48" name="quest_sondage" class="bouton" name="titre" style="width:400px"'; if(isset($_POST['quest_sondage'])) echo ' value="'.strip_gpc($_POST['quest_sondage']).'" '; echo' tabindex="100" /></td>
	</tr>
		';
		for($sond=0;$sond<$nbsondage;$sond++)
		{
			if(isset($_POST['option_'.$sond]) && !empty($_POST['option_'.$sond]))
			{
				if(!isset($_POST[$sond]) && !isset($moinsun))
				{
					echo '
	<tr>
		<td class="cadre_clair" height="30" width="160" style="padding:4px"><label for="o_sond">Option du sondage</label></td>
		<td class="cadre1_bas" style="padding:4px"><input id="o_sond" maxlength="48" type="text" name="option_'.$sond.'" class="bouton" name="titre" style="width:400px" value="'.strip_gpc($_POST['option_'.$sond]).'" tabindex="110" /><input type="submit" name="'.$sond.'" class="bouton" value="Supprimer" tabindex="120" /></td>
	</tr>
					';
				}
				else
				{
					if(!isset($moinsun)) $sond++;
					$moinsun = true;
					if(isset($_POST['option_'.($sond)]) && !empty($_POST['option_'.($sond)]))
					echo '
	<tr>
		<td class="cadre_clair" height="30" width="160" style="padding:4px">Option du sondage</td>
		<td class="cadre1_bas" style="padding:4px"><input type="text" maxlength="48" name="option_'.($sond-1).'" class="bouton" name="titre" style="width:400px" value="'.strip_gpc($_POST['option_'.($sond)]).'" tabindex="130" /> <input type="submit" name="'.($sond-1).'" class="bouton" value="Supprimer" tabindex="140" /></td>
	</tr>
					';
					else $sond += -1;
				}
			}
			else break;
		}
		$_POST = array();
		if(isset($moinsun)) $sond += -1;
		if($sond < $nbsondage)
		echo'
	<tr>
		<td class="cadre_clair" height="30" width="160" style="padding:4px">Option du sondage</td>
		<td class="cadre1_bas" style="padding:4px"><input maxlength="48" type="text" name="option_'.($sond).'" class="bouton" name="titre" style="width:400px" tabindex="150" /> <input type="submit" name="option" class="bouton" value="Ajouter l\'option" tabindex="160" /></td>
	</tr>
	<tr>
		';
		echo'
		<td class="cadre_clair" height="30" width="160" style="padding:4px"><label for="d_sondage">Durée du sondage</label></td>
		<td class="cadre1_bas" style="padding:4px"><input id="d_sondage" type="text" name="temps_sondage" class="bouton" name="titre" style="width:30px" '; if(isset($_POST['temps_sondage'])) echo ' value="'.strip_gpc($_POST['temps_sondage']).'" '; echo' tabindex="170" /> jour(s) [laissez vide si vous ne voulez pas de limite de temps] </td>
	</tr>
</table>
		';
	}
	echo'        
<p align="center">
	<input type="submit" name="previsu" class="bouton" value="Prévisualiser" tabindex="180" />
	<input type="submit" name="sendage" class="bouton" value="Envoyer" tabindex="190" /> 
	<input type="reset" name="Submit" value="R&eacute;initialiser" class="bouton" tabindex="200" />
</p>
</form>
	';
}
else
{
	if(isset($_GET['idf']))
	{

		$sql = 'INSERT INTO '.$prefixtable.'post (`titre` , `texte` , `idfa` , `idsfa` , `idsa` , `pseudode` , `idde` , `sondage` , `nbr` , `tmppost` , `pseudodernier` , `ip` , `edit` , `tmpdernierpost` , `lock` , `tmpsave` ) VALUES ("'.add_gpc($_POST['titre']).'","'.add_gpc($_POST['texte']).'","'.intval($data3['fatt']).'","'.intval($_GET['idf']).'","0","'.intval($idmembre).'","'.intval($idmembre).'","0","0","'.time().'","'.intval($idmembre).'","'.ippp().'",0,0,0,"'.time().'")';
		$qs = trim($_POST['quest_sondage']);
		// DEBUT DU SONDAGE
		if(!empty($qs) && !empty($_POST['option_1']) && !empty($_POST['option_0']))
		{
			$option_1 = trim($_POST['option_1']);
			$option_0 = trim($_POST['option_0']);
			if(!empty($option_1) && !empty($option_0))	
				
			$sql = 'INSERT INTO '.$prefixtable.'post (`titre` , `texte` , `idfa` , `idsfa` , `idsa` , `pseudode` , `idde` , `sondage` , `nbr` , `tmppost` , `pseudodernier` , `ip` , `edit` , `tmpdernierpost` , `lock` , `tmpsave` ) VALUES ("'.add_gpc($_POST['titre']).'","'.add_gpc($_POST['texte']).'","'.intval($data3['fatt']).'","'.intval($_GET['idf']).'",0,"'.intval($idmembre).'","'.intval($idmembre).'",0,0,"'.time().'","'.intval($idmembre).'","'.ippp().'",0,"1",0,"'.time().'")';
		}
		else{$option_1 = '';$option_0 = '';
	}
	// petit out
	
	$req = mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error()); $requse++;
	$idretour = mysql_insert_id();
	
	// petit out

	if(!empty($option_1) && !empty($option_0))
	{
		$sqlsond = 'INSERT INTO `'.$prefixtable.'sondage` (`idpost` , `forumatt` , `sforumatt` , `texte` , `nboption` , `nbvote` , `tmpvote` ) VALUES';
		for($sondinsert=0;$sondinsert<$nbsondage;$sondinsert++)
		{
			if(!empty($_POST['option_'.$sondinsert])) ${'option_'.$sondinsert} = trim($_POST['option_'.$sondinsert]); else ${'option_'.$sondinsert} = '';
			if(!empty(${'option_'.$sondinsert}))
			{
				$sqlsond .= ' ("'.$idretour.'","'.intval($data3['fatt']).'","'.intval($_GET['idf']).'","'.add_gpc(${'option_'.$sondinsert}).'",0,0,0) , ';
			}
			else break;
		}

		if(!empty($_POST['temps_sondage'])) $tempsondage = time()+(3600*24*abs(intval($_POST['temps_sondage'])));
		else $tempsondage = 0;
		$sqlsond .= ' ("'.$idretour.'","'.intval($data3['fatt']).'","'.intval($_GET['idf']).'","'.add_gpc($qs).'","'.$sondinsert.'",0,"'.$tempsondage.'");';
		if(empty($sondinsert)) $sondinsert = 0;
		$reqsond = mysql_query($sqlsond) or die('Erreur SQL !'.$sql.'<br />'.mysql_error()); $requse++;
	}
	// FIN DU SONDAGE
	
	$sql = 'UPDATE '.$prefixtable.'forum SET nbsujet = nbsujet+1 , adernier = "'.intval($idmembre).'" , dernier = "" , temps = '.time().' WHERE id = "'.intval($_GET['idf']).'"';
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); $requse++;
	$sql = 'UPDATE '.$prefixtable.'membres SET nbpost  = nbpost +1 , tempspost = '.time().' WHERE id = "'.intval($idmembre).'"';
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); $requse++; mysql_close();

	echo '
<table class="texte_base_gras" width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="cadre1_bas" style="padding:10px">'; echo '<a href="index.php">Index : '.htmlentities($nomduforum).'</a>'; echo'</td>
	</tr>
</table>
<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr align="center">
		<td height="29" class="titreforumend">Sujet envoy&eacute;.</td>
	</tr>
	<tr>
		<td align="center" class="cadre1_bas" style="padding:30px"><p>Vous avez envoy&eacute; un nouveau sujet. Vous allez être redirigé dans 5 secondes</p>
			<p>Si vous ne voulez pas attendre, cliquez sur ce lien : <a href="index.php?page=forum&amp;idf='.$_GET['idf'].'">Retourner au forum.</a>
				<script type="text/javascript">window.setTimeout("location=(\'index.php?page=forum&idf='.$_GET['idf'].'\');",5000)</script>
			</p> 
		</td>
	</tr>
</table>
	';
}
elseif(isset($_GET['edit']))
{
	$tt = trim($_POST['titre']);
	if($nedittitre > 0  || !empty($tt))
	{
		$sql = 'UPDATE '.$prefixtable.'post SET titre = "'.add_gpc($_POST['titre']).'" , texte = "'.add_gpc($_POST['texte']).'"  WHERE id2 = "'.intval($_GET['edit']).'" OR `lock` = "'.intval($_GET['edit']).'"';
		if(isset($editaffmod)) $sql = 'UPDATE '.$prefixtable.'post SET titre = "'.add_gpc($_POST['titre']).'" , texte = "'.add_gpc($_POST['texte']).'" , edit = "'.time().'" WHERE id2 = "'.intval($_GET['edit']).'"';
	}
	else
	{
		$sql = 'UPDATE '.$prefixtable.'post SET  texte = "'.add_gpc($_POST['texte']).'"  WHERE id2 = "'.intval($_GET['edit']).'" OR `lock` = "'.intval($_GET['edit']).'"';
		if(isset($editaffmod)) $sql = 'UPDATE '.$prefixtable.'post SET  texte = "'.add_gpc($_POST['texte']).'" , edit = "'.time().'" WHERE id2 = "'.intval($_GET['edit']).'"';

	}
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); $requse++;
	
	$sql = 'UPDATE '.$prefixtable.'membres SET tempspost = '.time().' WHERE id = "'.intval($_SESSION['idlog']).'"';
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); $requse++; mysql_close();
	echo '
<table class="texte_base_gras" width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
    	<td class="cadre1_bas" style="padding:10px">'; echo '<a href="index.php">Index : '.htmlentities($nomduforum).'</a>'; echo'</td>
	</tr>
</table>
<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr align="center">
		<td height="29" class="titreforumend">Message modifi&eacute;</td>
	</tr>
	<tr>
		<td align="center" class="cadre1_bas" style="padding:30px">
			<p>Message modifi&eacute;. Vous allez &ecirc;tre redirig&eacute; dans 5 secondes.</p> 
			<p>Si vous ne voulez pas attendre, cliquez sur ce lien : <a href="index.php?page=post&amp;ids='.$_GET['ids'].'&amp;pg='.$_GET['pg'].'#'.$_GET['edit'].'">Retourner au forum.</a>
				<script type="text/javascript">window.setTimeout("location=(\'index.php?page=post&ids='.$_GET['ids'].'&pg='.$_GET['pg'].'#'.$_GET['edit'].'\');",5000)</script>
			</p> 
		</td>
	</tr>
</table>
	';
}
elseif(isset($_GET['ids']))
{
	$sql = 'INSERT INTO '.$prefixtable.'post (`titre` , `texte` , `idfa` , `idsfa` , `idsa` , `pseudode` , `idde` , `sondage` , `nbr` , `tmppost` , `pseudodernier` , `ip` , `edit` , `tmpdernierpost` , `lock` , `tmpsave` ) VALUES("'.add_gpc($_POST['titre']).'","'.add_gpc($_POST['texte']).'","'.intval($dat['idfa']).'","'.intval($dat['idsfa']).'","'.intval($_GET['ids']).'","'.intval($idmembre).'","'.intval($idmembre).'",0,0,"'.time().'","","'.ippp().'",0,0,0,"'.time().'");';
	$req = mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error()); $requse++;
	$idretour = mysql_insert_id();

	$sql = 'UPDATE '.$prefixtable.'forum SET nbmessage = nbmessage+1 , adernier = "'.intval($idmembre).'" , dernier = "" , temps = '.time().' WHERE id = "'.intval($dat['idsfa']).'"';
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); $requse++;

	$sql = 'UPDATE '.$prefixtable.'post SET nbr = nbr+1 , pseudodernier = "'.intval($idmembre).'" , tmppost = '.time().' WHERE id2 = "'.intval($_GET['ids']).'"';
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); $requse++;

	$sql = 'UPDATE '.$prefixtable.'membres SET nbpost  = nbpost +1 , tempspost = '.time().' WHERE id = "'.intval($idmembre).'"';
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); $requse++;
	
	////////////////////

	$sql = 'SELECT id2 FROM '.$prefixtable.'post WHERE id2 = '.intval($_GET['ids']).' OR idsa = '.intval($_GET['ids']);
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); $requse++;
	$nbentree2 = mysql_num_rows($req);
	mysql_free_result($req);
	$nbpage = ceil($nbentree2/$postparpageaff)-1; 		
	mysql_close();
	
	///////////////////// 

	echo '
<table class="texte_base_gras" width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="cadre1_bas"  style="padding:10px">'; echo '<a href="index.php">Index : '.htmlentities($nomduforum).'</a>'; echo'</td>
	</tr>
</table>
<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr align="center">
		<td height="29" class="titreforumend">R&eacute;ponse envoy&eacute;e.</td>
	</tr>
	<tr>
		<td align="center" class="cadre1_bas" style="padding:30px">
			<p>Vous avez envoy&eacute; une nouvelle reponse. Vous allez être redirigé dans 5 secondes.</p>
			<p>Si vous ne voulez pas attendre, cliquez sur ce lien : <a href="index.php?page=post&amp;ids='.$_GET['ids'].'&amp;pg='.$nbpage.'#'.$idretour.'">Retourner au forum.</a>				
				<script type="text/javascript">window.setTimeout("location=(\'index.php?page=post&ids='.$_GET['ids'].'&pg='.$nbpage.'#'.$idretour.'\');",5000)</script>
			</p> 
		</td>
	</tr>
</table>
	';
}
}
} // Alors la comprend pas ce que j'ai foutu dans l'indentage ... mais ya du fuckage quelque part !
?>