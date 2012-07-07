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

if(!is_numeric($_GET['idf']))
{
	include('./includes/erreur.php'); exit();
}

$numreq = mysql_num_rows($req);

if(empty($numreq))
{
	include('./includes/erreur.php'); exit();
}

$nbentree = $data3['nbsujet'];
$tempslastv = $data3['temps'];

// On a le num du groupe

// Cas 1 : Forum pour membre et user pas membre
if($data3['groupe'] == -2 && $rang == -1)
{
	echo'
	<table width="100%" class="texte_base_gras" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td height="29" class="titreforumend" align="center" style="padding-left:8px">'.bbcode(htmlentities($data3['nom'])).'</td>
		</tr>
		<tr>
			<td class="cadre_clair" style="padding:10px" align="center"><p>Seuls les membres peuvent acceder &agrave; ce forum.</p></td>
	    </tr>
	</table>
	';
	$autorisation = -1;
}
elseif($data3['groupe'] == -4)
{
	if($rang != 1  && $rang != 2)
	{
		if(!isset($_SESSION['pseudo']))
		{
			if($data3['v'] == 1) $autorisation = 1; else $autorisation = -1;
			$vofpost = $data3['v'];
		}
		else
		{
			$vofpost = $data3['m'];
			if($data3['m'] > 0) $autorisation = 1; else $autorisation = -1;
		}
		if($autorisation == -1)
		echo'
	<table width="100%" class="texte_base_gras" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td height="29" align="center" class="titreforumend" style="padding-left:8px">'.bbcode(htmlentities($data3['nom'])).'</td>
		</tr>
		<tr>
			<td class="cadre1_bas" style="padding:10px" align="center"><p>Vous n\'avez l\'autorisation de visioner ce forum.</p></td>
		</tr>
	</table>
		';
	}
	else
	{
		$vofpost = 4; $autorisation = 1;
	}
}
elseif($data3['groupe'] == -3 && $rang != 1 && $rang != 2)
{
	echo'
	<table width="100%" class="texte_base_gras" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td height="29" class="titreforumend" align="center" style="padding-left:8px">'.bbcode(htmlentities($data3['nom'])).'</td>
		</tr>
		<tr>
			<td class="cadre_clair" style="padding:10px" align="center"><p>Ce forum est surverrouill&eacute;.</p></td>
    	</tr>
	</table>
	';
	$autorisation = -1;
}

// Cas 2 : Forum pas locké et pas normal
elseif($data3['groupe'] != -1 && $data3['groupe'] != 0 && $data3['groupe'] != -2  && $data3['groupe'] != -4)
{
	// Cas 2.1 : Groupe particulier et pas membre
	if($rang == -1 && $data3['v'] == 0)
	{
		$autorisation = 0; $vofpost = $data3['v'];
	}
	elseif($rang == -1 && $data3['v'] == 1)
	{
		$autorisation = 1; $vofpost = $data3['v'];
	}
	
	// Cas 2.3 : Groupe particulier et membre
	elseif($rang != 2 && $rang != 1)
	{
		$sql = 'SELECT stat FROM '.$prefixtable.'groupemembre WHERE idg = '.intval($data3['groupe']).' AND idm = '.intval($idmembre);
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		$requse++;
		$goupas = mysql_num_rows($req);
		$data2 = mysql_fetch_assoc($req);
		if($goupas == 1 && $data2['stat'] == 0)
		{
			$vofpost = $data3['mg'];
			if($data3['mg'] >0) $autorisation = 1; else $autorisation = 0;
		}
		elseif($goupas == 1 && $data2['stat'] == 1)
		{
			$vofpost = 4;
			$autorisation = 1;
			$rang = 1;
		} 
		else
		{
			$vofpost = $data3['m'];
			if($data3['m'] >0) $autorisation = 1; else $autorisation = 0;
		}
		//$statgroupe = $data2['stat'];
		// Si t'es chef de groupe, tu reçois le rend modo ici
		//if($statgroupe == 1) $rang = 1;
	}
	// Rien de spécial (pas forcément logique)
	else { $autorisation = 1; $vofpost =4;}
}
// Cas ou le forum vaut forcément -1
else
{
	$autorisation = 1;
}
if(!isset($vofpost)) $vofpost = 4; 
//if(!isset($autorisation)) $autorisation = 1;
if($autorisation == 0 || $autorisation == -1)
{
	if($autorisation == 0)
	{
		echo'
		<table width="100%" class="texte_base_gras" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td height="29" align="center" class="titreforumend" style="padding-left:8px">'.bbcode(htmlentities($data3['nom'])).'</td>
			</tr>
			<tr>
				<td class="cadre1_bas" style="padding:10px" align="center"><p>Vous n\'avez l\'autorisation de visioner ce forum.</p></td>
			</tr>
		</table>
		';
	}
}
else
{
	// Tentative 1
	if(isset($_SESSION['pseudo']))
	{	
		if(isset($_SESSION['forumtime'.$_GET['idf']]) && $tempslastv  < $_SESSION['forumtime'.$_GET['idf']]) $revoir = false;
		elseif($tempslastv < $_SESSION['lastvisit']) $revoir = false;
		else $revoir = true;
		if($revoir)
		{
			if(isset($_SESSION['forumtime'.$_GET['idf']])) $sql = 'SELECT id2,tmppost,`lock` FROM '.$prefixtable.'post WHERE idsfa = '.intval($_GET['idf']).' AND idsa = 0 AND tmppost > '.intval($_SESSION['forumtime'.intval($_GET['idf'])]);
			else $sql = 'SELECT id2,tmppost,`lock` FROM '.$prefixtable.'post WHERE idsfa = '.intval($_GET['idf']).' AND idsa = 0 AND tmppost > '.intval($_SESSION['lastvisit']);
			$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
			$requse++;
			$nbneue = 0;
			while ($data = mysql_fetch_assoc($req))
			{
				if($data['tmppost'] > $_SESSION['lastvisit'] && $data['lock'] < 1)
				{
					$nbneue++;
					if(isset($_SESSION['post'.'-'.$data['id2'].'-'.$_GET['idf']]) )
					{
						if($_SESSION['post'.'-'.$data['id2'].'-'.$_GET['idf']] >= $data['tmppost']) { $nbneue += -1; }
					}
				}
			}
			$_SESSION['kk'.$_GET['idf']] = $nbneue;
			$_SESSION['forumtime'.$_GET['idf']] = time();
		}
	}
	// Fin de tentative
	
	mysql_free_result($req);
	if(isset($_GET['pg']))
	{
		$de = $_GET['pg']*$postparpage; $p2=$_GET['pg'];
	}
	else
	{
		$de = 0; $p2=0;
	}
	echo '
	<table class="texte_base_gras" width="100%"  border="0" cellpadding="0" cellspacing="0">
  		<tr>
	';
	if($rang != -1 && $vofpost > 1 && $vofpost != 3)
	{
		echo'
			<td class="cadre1_bas" style="padding:10px" width="84">';
			if($data3['groupe'] == -1 || $data3['groupe'] == -3)
			{
				if($rang == 2 || $rang == 1)
				{
					echo'<a href="index.php?page=postadd&amp;idf='.$_GET['idf'].'"><img src="./img/actions/verrouille.gif" alt="Vérouillé" /></a>';
				}
				else
				{
					echo'<img src="./img/actions/verrouille.gif" alt="Vérouillé" />'; 
				}
			}
			else
			{
				echo'<a href="index.php?page=postadd&amp;idf='.$_GET['idf'].'"><img src="./img/actions/nouveau.gif" alt"Nouveau Sujet" /></a>';
			}
			echo'
			</td>
		';
	}
		echo '
			<td class="cadre1_bas" style="padding:10px"><a href="index.php">Index du forum : '.htmlentities($nomduforum).'</a> -> '.bbcode(htmlentities($data3['nom'])).'</td>
			<td width="150" align="right" class="cadre1_bas" style="padding:5px">
		'; 
			if($nbentree != 0) echo'Page : '; 
			if($p2 > 1)
			{
				echo'... ';
			}
			$nbpage = ceil($nbentree/$postparpage); 
			if($p2 > 0)
			{
				$p=$p2-1; $pc=0;
			}
			elseif($nbpage == 2)
			{
				$p=0; $pc=0;
			}
			else
			{
				$p=0; $pc=1;
			}
			if($p2 < $nbpage-1)
			{
				$pmax=$p2+1+$pc;
			}
			else
			{
				$pmax = $nbpage-1;
			}
			for($p;$p<=$pmax;$p++)
			{ 
				echo '<a href="index.php?page=forum&amp;idf='.$_GET['idf'].'&amp;pg='.$p.'">';
				if($p2 == $p)
				{
					echo'<span class="admin">';
				}
				echo $p+1;
				if($p2 == $p)
				{
					echo'</span>';
				}
				echo '</a>';
				if($p != $nbpage-1)
				{
					echo',';
				}
			}
		if($p2 < $nbpage-2-$pc)
		{
			echo'... ';
		}
		echo'	
			</td>
		</tr>
	</table>';

	$sql = 'SELECT p.id2,p.titre,p.sondage,p.`lock`,p.tmppost,p.nbr,p.idde,p.pseudode,p.pseudodernier,p.tmpdernierpost,m.pseudo AS pseudoposter,r.pseudo AS pseudoreponder FROM '.$prefixtable.'post AS p LEFT JOIN '.$prefixtable.'membres AS m ON p.idde=m.id LEFT JOIN '.$prefixtable.'membres AS r ON p.pseudodernier=r.id  WHERE idsfa = '.intval($_GET['idf']).' AND idsa = 0 ORDER BY sondage DESC,tmppost DESC LIMIT '.intval($de).','.intval($postparpage);
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
	mysql_close();
	$requse++;
	$nbentree = mysql_num_rows($req);

	if($nbentree != 0)
	{
		echo'<table class="texte_base_gras"  width="100%"  border="0" cellspacing="0" cellpadding="0">';
		echo'<tr>
				<td height="29" colspan="2" align="center" class="titreforum">Sujet</td>
				<td width="80" class="titreforum"><div align="center">R&eacute;ponses</div></td>
				<td width="130" class="titreforum"><div align="center">Auteur&nbsp; </div></td>
				<td width="150" class="titreforumend"><div align="center">Dernier message </div></td>
			</tr>
		';
		while ($data = mysql_fetch_assoc($req)) 
		{
			if(isset($savetype))
			{
				if($savetype != $data['sondage'])
				{
				echo'<tr>
						<td colspan="5" class="espace"><img src="./img/space.gif" alt="" /></td>
					</tr>
				';
				}
			}
			$savetype = $data['sondage'];
			echo'
			<tr>
				<td width="25" height="25" class="cadre_fonce">';
				// Affichage des Lus / Non Lus
				if(($data['lock'] == -1 || $data['lock'] == -2) && $data['sondage'] != 2 && $data['sondage'] !=1 ) echo '<img src="./img/statut/sujet_clos.gif" alt="Sujet Clot" />';
				elseif($data['lock'] > 0) 
				{
					echo '<img src="./img/statut/deplace.gif" alt="Sujet Déplacé" />';
				}
				elseif(isset($_SESSION['post'.'-'.$data['id2'].'-'.$_GET['idf']]) && !empty($pseudo))
				{	
					if($_SESSION['post'.'-'.$data['id2'].'-'.$_GET['idf']] <  $data['tmppost'])
					{
						if($data['nbr']/$postparpageaff >= 3)
						{ 
							if($data['sondage'] == 2) echo '<img src="./img/statut/n_annonce.png" alt="Nouvelle Anonce" />';
							elseif($data['sondage'] == 2) echo '<img src="./img/statut/n_post_it.gif" alt="Nouveau Post-It" />';
							else echo '<img src="./img/statut/n_sujet_pop.gif" alt="Sujet Populaire - Nouveau Message" />';
						}
						else
						{
							if($data['sondage'] == 2) echo '<img src="./img/statut/n_annonce.png" alt="Nouvelle Anonce" />';
							elseif($data['sondage'] == 1) echo '<img src="./img/statut/n_post_it.gif" alt="Nouveau Post-It" />';
							else echo '<img src="./img/statut/n_sujet.gif" alt="Nouveau(x) Sujet / Message(s)" />';
						}
					}
					else 
					{
						if($data['nbr']/$postparpageaff >= 3)
						{
							if($data['sondage'] == 2) echo '<img src="./img/statut/annonce.png" alt="Annonce" />';
							elseif($data['sondage'] == 1) echo '<img src="./img/statut/post_it.gif" alt="Nouveau Post-It" />';
							else echo '<img src="./img/statut/sujet_pop.gif" alt="Sujet Populaire" />';
						}
						else
						{
							if($data['sondage'] == 2) echo '<img src="./img/statut/annonce.png" alt="Annonce" />'; elseif($data['sondage'] == 1) echo '<img src="./img/statut/post_it.gif" alt="Post-It" />'; 
							else echo '<img src="./img/statut/sujet.gif" alt="Pas de nouveau Message" />';
						}
					}
				}
				elseif(isset($_SESSION['lastvisit'])  && !empty($pseudo))
				{	
					if($_SESSION['lastvisit'] <  $data['tmppost'])
					{
						if($data['nbr']/$postparpageaff >= 3)
						{
							if($data['sondage'] == 2) echo '<img src="./img/statut/n_annonce.png" alt="Nouvelle Anonce" />';
							elseif($data['sondage'] == 1) echo '<img src="./img/statut/n_post_it.gif" alt="Nouveau Post-It" />';
							else echo '<img src="./img/statut/n_sujet_pop.gif" alt="Sujet Populaire - Nouveau Message" />';
						}
						else
						{
							if($data['sondage'] == 2) echo '<img src="./img/statut/n_annonce.png" alt="Nouvelle Anonce" />';
							elseif($data['sondage'] == 1) echo '<img src="./img/statut/n_post_it.gif" alt="Nouveau Post-It" />';
							else echo '<img src="./img/statut/n_sujet.gif" alt="Nouveau(x) Sujet / Message(s)" />';
						} 
					}
					else 
					{
						if($data['nbr']/$postparpageaff >= 3)
						{
							if($data['sondage'] == 2) echo '<img src="./img/statut/annonce.png" alt="Annonce" />';
							elseif($data['sondage'] == 1) echo '<img src="./img/statut/post_it.gif" alt="Post-It" />';
							else echo '<img src="./img/statut/sujet_pop.gif" alt="Sujet Populaire" />';
						}
						else
						{
							if($data['sondage'] == 2) echo '<img src="./img/statut/annonce.png" alt="Annonce" />';
							elseif($data['sondage'] == 1) echo '<img src="./img/statut/post_it.gif" alt="Post-It" />';
							else echo '<img src="./img/statut/sujet.gif" alt="Pas de nouveau Message" />';
						}
					}
				}
				else 
				{
					if($data['sondage'] == 2) echo '<img src="./img/statut/annonce.png" alt="Annonce" />';
					elseif($data['sondage'] == 1) echo '<img src="./img/statut/post_it.gif" alt="Post-It" />';
					else echo '<img src="./img/statut/sujet.gif" alt="Pas de nouveau Message" />';
				}
				// FIN Affichage des Lus / Non Lus
				/// FIN SESSION LU/PAS LU
					echo'
					</td>
					<td class="cadre_clair" style="padding:5px">';
					if($data['lock'] > 0)
					echo'<a href="index.php?page=post&amp;ids='.$data['lock'];
					else echo'<div class="messagevisite"><a href="index.php?page=post&amp;ids='.$data['id2'];
					//if($n == 1) { echo '&n'; $n=0; }
					echo '">';
					if($data['lock'] > 0) echo '[D&eacute;plac&eacute;] ';
					if($data['tmpdernierpost'] == 1) echo '[Sondage] ';
						echo sit(htmlentities($data['titre']));
					if($rang == 1 || $rang == 2)
					{
						if($data['lock'] == -1) echo' - <span class="admin">[Sujet verrouill&eacute;]</span> ';
						if($data['lock'] == -2) echo' - <span class="admin">[Sujet surverrouill&eacute;]</span> ';
					}
					echo'</a></div>';

					$nbdepage = ceil(($data['nbr']+1)/$postparpageaff);
					if($nbdepage > 1)
					{
						echo '<span class="allera">[ Aller à la page ';
						if($nbdepage <= 4)
						{
							for($p=0;$p<$nbdepage;$p++)
								{
								echo '<a href="index.php?page=post&amp;ids='.$data['id2'].'&amp;pg='.$p.'">'.($p+1).'</a>';
								if($p != ($nbdepage-1)) echo ',';
							}
						}
						else
						{
							echo '<a href="index.php?page=post&amp;ids='.$data['id2'].'&amp;pg=0">1</a>,...,';
							for($p=($nbdepage-3);$p<$nbdepage;$p++)
							{
								echo '<a href="index.php?page=post&amp;ids='.$data['id2'].'&amp;pg='.$p.'">'.($p+1).'</a>';
								if($p != ($nbdepage-1)) echo ',';
							}
						}
						echo ' ]</span>';
					}	
					echo'
					</td>
			    	<td width="80" class="cadre_fonce" align="center">';
					if($data['lock'] > 0) echo'-';
					else echo $data['nbr'];
					echo'
					</td>
			    	<td width="130" class="cadre_clair" align="center"><a href="index.php?page=affprofil&amp;id='.htmlentities($data['pseudode']).'">'.htmlentities($data['pseudoposter']).'</a></td>
				    <td width="150" class="cadre_fonce_end" align="center" style="padding:3px">'.datefct($data['tmppost'],$gmt).'<br />par <a href="index.php?page=affprofil&amp;id='.$data['pseudodernier'].'">'.htmlentities($data['pseudoreponder']).'</a></td>
				</tr>
			';
			}// Fin while
			echo'</table>';
		}
		else
		{ 
		echo'
			<table width="100%"  class="texte_base_gras" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="29" class="titreforumend"  align="center" style="padding-left:8px">'.bbcode(htmlentities($data3['nom'])).'</td>
				</tr>
				<td class="cadre1_bas" style="padding:10px" align="center"><p>Il n\'y a pas encore de message dans ce forum.</p></td>
				</tr>
			</table>
			';
		} 
	}

	mysql_free_result($req);
?> 