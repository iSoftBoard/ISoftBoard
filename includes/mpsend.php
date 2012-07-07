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
if(!is_numeric($_GET['id'])) exit();

if(!isset($_SESSION['pseudo']) || empty($_SESSION['pseudo'])) exit();
if($rang == -1)
{ 
	include('./includes/erreur.php');
}
else
{
	$sql = 'SELECT pseudo FROM '.$prefixtable.'membres WHERE id = "'.intval($_GET['id']).'"';
	$req = mysql_query($sql);
	$requse++;
	$numreq = mysql_num_rows($req);
	if(empty($numreq)) exit();

	$data = mysql_fetch_assoc($req);
	$pseudosend = $data['pseudo'];
	mysql_free_result($req);
	if(isset($_POST['texte']))
	{
		$titre = trim(strip_gpc($_POST['titre'])); $texte = trim(strip_gpc($_POST['texte']));
	}
	
	else
	{
		$titre="";$texte="";
	}
	$timemin = time()-$tmpfreepost;
	if($idmembre == $_GET['id'])
	{
		echo'
		<table width="100%" class="texte_base_gras" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td height="29" class="titreforumend" style="padding-left:8px">Envoyer un message priv&eacute; </td>
			</tr>
			<tr>
				<td align="center" class="cadre1_bas" style="padding:20px">Erreur, on ne peut pas s\'auto-envoyer un mp</td>
    		</tr>
		</table>
		';
	}
	
	elseif($tempspostlast >= $timemin && $rang != 1 && $rang != 2)
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
	
	elseif(empty($titre) || empty($texte))
	{
		echo '
		<form action="index.php?page=mpsend&amp;id='.$_GET['id'].'" method="post" enctype="multipart/form-data" name="news">
        <table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr align="center">
				<td height="29" class="titreforumend">Envoyer un message priv&eacute; </td>
			</tr>
		</table>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="texte_base_gras">
			<tr>
				<td class="cadre_clair" style="padding:4px">Nom d\'utilisateur </td>
				<td class="cadre1_bas" style="padding:4px">'.htmlentities($pseudosend).'</td>
			</tr>
			';
		
			if(isset($_POST['titre']))
			{
				if(empty($titre) || empty($exte))
				{
					echo '
			<tr>
				<td class="cadre_clair" style="padding:4px"><span class="red">Erreur</span></td>
				<td class="cadre1_bas" style="padding:4px">';if(empty($titre)) echo'Vous devez mettre un titre<br />';if(empty($texte)) echo'Vous devez mettre un contenu';
			echo'</td>
            </tr>
			';
				}
			}
		echo'
			<tr>
				<td class="cadre_clair" style="padding:4px">Sujet</td>
				<td class="cadre1_bas" style="padding:4px"><input  maxlength="64" type="text" value="';
				if(isset($_GET['rep']))
				{
					$sql = 'SELECT titre FROM '.$prefixtable.'mp WHERE id = '.intval($_GET['rep']).' AND ida = '.intval($idmembre);
					$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
					mysql_close();
					$requse++;
					$dat1 = mysql_fetch_assoc($req);
					if(substr($dat1['titre'],'0','3') != 'RE:')
					{
						echo 'RE: '.htmlentities($dat1['titre']);
					}
					else
					{
						echo htmlentities($dat1['titre']);
					}
				}
				else
				{
					echo htmlentities($titre);
				}
				echo'" class="bouton" name="titre" style="width:400px" tabindex="10" /></td>
			</tr>
			<tr>
				<td width="160" valign="top" class="cadre_clair" style="padding:4px">Corps du message';
				$vs=0;
				if($emoticonnb != 0)
				{
					echo'
					<p>
						<table border="0" cellpadding="0" cellspacing="0" align="center" style="padding:10px">
							<tr>';
							for($affem=0;$affem<20;$affem++)
							{
								$vs++;
								echo'
								<td style="padding:5px"><a href="javascript:emoticon(\''.$emoticonc[$affem].'\')"><img src="'.$emoticonv[$affem].'" alt="'.$emoticonc[$affem].'" title="Very Happy" border="0"></a></td>';
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
							</tr>
							<tr>
								<td colspan="4" align="center" style="padding:5px"><a href="" onclick="window.open(\'emote.php\', \'\', \'HEIGHT=450,resizable=yes,scrollbars=yes,WIDTH=250\');return false;">Voir plus <br />de Smilies</a></td>
							</tr>
						</table>
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
						<option style="color: black;" value="#444444" class="genmed">D&eacute;faut</option>
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
				   
				<textarea name="texte" class="tbouton" id="texte" tabindex="20">'.htmlentities($texte).'</textarea>';
				echo'
				</td>
			</tr>
		</table>
		
		<p align="center"><input type="submit" name="Submit" class="bouton" value="Envoyer" tabindex="30" /><input type="reset" name="Submit" value="R&eacute;initialiser" class="bouton" tabindex="40" /></p>
		</form>
		';
	}
	else
	{
		$sql = 'INSERT INTO '.$prefixtable.'mp (`lu` , `ida` , `idde` , `pseudode` , `pseudoa` , `titre` , `texte` , `temps` ) VALUES (0,"'.intval($_GET['id']).'","'.intval($idmembre).'","'.add_gpc($pseudo).'","'.add_gpc($pseudosend).'","'.add_gpc(trim($_POST['titre'])).'","'.add_gpc(trim($_POST['texte'])).'","'.time().'")';
		$req = mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error()); 
		$requse++;
		
		$sql = 'UPDATE '.$prefixtable.'membres SET mp = mp+1  WHERE id = "'.intval($_GET['id']).'"';
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); 
		$requse++;
		
		$sql = 'UPDATE '.$prefixtable.'membres SET tempspost = '.time().' WHERE id = "'.intval($idmembre).'"';
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); 
		$requse++;
		
		mysql_close();

	echo'
	<table width="100%" class="texte_base_gras" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td height="29" class="titreforumend" style="padding-left:8px">Message evoyé</td>
		</tr>
		<tr>
			<td class="cadre1_bas" style="padding:10px">Message envoyé -> <a href="index.php?page=mp">Retour à la boite de r&eacute;ception</a></td>
		</tr>
	</table>
	';
	}
}
?>

