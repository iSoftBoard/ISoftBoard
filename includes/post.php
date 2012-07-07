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
 
// [1] Expulsé si pas connecté
if(!isset($pseudo)) header('Location: index.php?page=erreur');

// [1bis] Expulsé si variable get bidouillée
if(!is_numeric($_GET['ids']))
{
	include('./includes/erreur.php');
	exit('2');
}

// [2] On sélectionne les donées relatives au post dont l'id est GET['ids'], en y joignant les données du groupe

$idsfa = $data3['idsfa'];
$sondage = $data3['sondage'];
$titresujet = $data3['titre'];
$editlock = $data3['lock'];
$nbentree2 = $data3['nbr']+1;

// /!\ Petit eexplication : le champ tmpdernierpost correspond à l'id du sondage (désolé pour la bidouille)
$sondageaff = $data3['tmpdernierpost'];
	
// [5] Si le membre est connecté, il a droit au système d'affichage post lu/non lu
if(isset($_SESSION['idlog']))
{
	// [5.1] Si le temps de dernière vue du post est déjà en cache
	if(isset($_SESSION['post'.'-'.$_GET['ids'].'-'.$idsfa]))
	{ 
		// [5.1.1] Alors on vérifie si le temps du post est suppérieur
		if($_SESSION['post'.'-'.$_GET['ids'].'-'.$idsfa] <= $data3['tmppost']) $revoir = true;
		// [5.1.2] Cas contraire
		else $revoir = false;
	}
	
	// [5.2] Autrement si le temps du post est suppérieur au temps de dernière visite
	elseif($_SESSION['lastvisit'] <= $data3['tmppost'])
	{
		$revoir = true;
	}
		
	// [5.3] Autrement, on ne fait rien
	else {
		$revoir = false;
	} 

	// [5.A] Si les conditions sont là pour vérifier
	if($revoir)
	{	
		// [5.A.1] on met à jour le temps du post actuel, puisque le membre y est
		$_SESSION['post'.'-'.$_GET['ids'].'-'.$idsfa] = time();
	
		// [5.A.2] on sélectionne les post susceptibles d'etre non lu
		$sql = 'SELECT id2,tmppost,`lock` FROM '.$prefixtable.'post WHERE tmppost > '.intval($_SESSION['lastvisit']).' AND idsfa = '.intval($idsfa).' AND idsa = 0';
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); $requse++;
		mysql_num_rows($req);

		// [5.A.3]  On ca compter le nombre de post réellement non-lu, donc pas ceux dont le temps est mit en cache
		$nbneue = 0;
		while ($data = mysql_fetch_assoc($req))
		{
			// [5.A.3.1] On ajoute 1 au nombre de post non lu 
			$nbneue++;
			// [5.A.3.2] On vérifie si un temps en cache existe
			if(isset($_SESSION['post'.'-'.$data['id2'].'-'.$idsfa]))
			{
				// [5.A.3.2.1] Si, c'est le cas, on regarde si le temps en cache est supérieur ou pas au temps du post, si ce n'est pas le cas, on retire 1
				if($_SESSION['post'.'-'.$data['id2'].'-'.$idsfa] >= $data['tmppost'])
				{
					$nbneue--;
				}
			}
		}
		// [5.A.4]  On met à jour le nombre de post et le temps du cache du forum
		$_SESSION['kk'.$idsfa] = $nbneue;
		$_SESSION['forumtime'.$idsfa] = time();
	}  // Fin de [5.A]
} //Fin de [5]

// [6] on commence par vérifier les autorisations

// [6.1] Si le groupe vaut -2 (membre seul) et que c'est un membre qui veut visioner
if($data3['groupe'] == -2 && $rang == -1)
{
	echo'
	<table width="100%" class="texte_base_gras" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td height="29" class="titreforumend" align="center" style="padding-left:8px">'.bbcode(htmlentities(($data3['nom']))).'</td>
		</tr>
		<tr>
			<td class="cadre_clair" style="padding:10px" align="center"><p>Seuls les membres peuvent acceder &agrave; ce forum.</p></td>
		</tr>
	</table>
	';
	// [6.1.1] On lui refuse l'accès pour plus tard
	$autorisation = -1;
}

elseif(!empty($data3['idsa']) || $editlock>0)
{
	include('./includes/erreur.php');
	$autorisation = -1;
}

// [6.2] Si le groupe vaut -4 (personalisé)
elseif($data3['groupe'] == -4)
{
	// [6.2.1] Si ce n'est ni un admin ni modo qui veut visioner
	if($rang != 1  && $rang != 2)
	{
		// [6.2.1.1] Si c'est un non membre
		if(!isset($_SESSION['idlog']) || empty($_SESSION['idlog']))
		{
			// [6.2.1.1.A] On vérifie si il peut visioner
			if($data3['v'] == 1) $autorisation = 1; 
			// [6.2.1.1.B] Cas conntraire
			else $autorisation = -1;
			// [6.2.1.1.B] $vofpost, mise en cache de l'autorisation de visionage, je sais plus pourquoi je devais faire ça comme ça
			$vofpost = $data3['v'];
		}
		
		// [6.2.1.2] Si c'est un membre
		else
		{
			// [6.2.1.2.A] On vérifie si il peut visioner
			$vofpost = $data3['m'];
			if($data3['m'] > 0) $autorisation = 1; 
			// [6.2.1.2.B] Cas contraire
			else $autorisation = -1;
		}
		
		// [6.2.1.A] On affiche le message d'erreur si le membre ne peut pas aller plus loin
		if($autorisation == -1)
		echo'
		<table width="100%" class="texte_base_gras" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td height="29" align="center" class="titreforumend" style="padding-left:8px">'.bbcode(htmlentities($data3['nom'])).'</td>
			</tr>
			<tr>
				<td class="cadre_clair" style="padding:10px" align="center"><p>Vous n\'avez l\'autorisation de visioner ce forum.</p></td>
			</tr>
		</table>
		';
	}
	
	// [6.2.2] On met les autorisations maximums pour un admin ou modo
	else
	{
		$vofpost = 4; $autorisation = 1;
	}
}

// [6.3] Si le groupe vaut -3 (surverouillage), seul les admins ou modos (total, pas chef de groupe) peuvent visioner
elseif($data3['groupe'] == -3 && $rang != 1 && $rang != 2)
{
	echo'
	<table width="100%" class="texte_base_gras" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td height="29" class="titreforumend"  align="center" style="padding-left:8px">'.bbcode(htmlentities($data3['nom'])).'</td>
		</tr>
		<tr>
			<td class="cadre_clair" style="padding:10px" align="center"><p>Ce forum est surverrouill&eacute;.</p></td>
		</tr>
	</table>
	';
	$autorisation = -1;
}

elseif($editlock == -2 && $rang != 1 && $rang != 2 && ($data3['groupe'] <= 0 || $rang == -1) )
{
	echo'
	<table width="100%" class="texte_base_gras" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td height="29" class="titreforumend"  align="center" style="padding-left:8px">'.bbcode(htmlentities($data3['nom'])).'</td>
		</tr>
		<tr>
			<td class="cadre_clair" style="padding:10px" align="center"><p>Ce sujet est surverrouill&eacute;.</p></td>
		</tr>
	</table>
	';
	$autorisation = -1;
}

// [6.4] Si le groupe est un groupe créé, donc, un groupe particulié
elseif($data3['groupe'] != -1 && $data3['groupe'] != 0 && $data3['groupe'] != -2 && $data3['groupe'] != -4)
{
	// [6.4.1] Si le groupe est un groupe particulié et que le membre est non connecté
	if($rang == -1 && $data3['v'] == 0)
	{
		$autorisation = 0; $vofpost = $data3['v'];
	}
	
	// [6.4.1.false] Cas contraire
	else
	{
		$autorisation = 1; $vofpost = $data3['v'];
	}
	
	// [6.4.1] Si le groupe est un groupe particulié et que le membre est connecté
	if($rang != 2 && $rang != 1 && $rang != -1)
	{
		// [6.4.1.1] On récupère les infos relatives au groupes : le membre en fait partie? grade spécial?
		$sql = 'SELECT stat FROM '.$prefixtable.'groupemembre WHERE idg = '.$data3['groupe'].' AND idm = '.$idmembre;
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); $requse++;
		$goupas = mysql_num_rows($req);
		$data2 = mysql_fetch_assoc($req);
		
		// [6.4.1.2] Si c'est un membre du groupe, on reprend les infos relatives au groupe
		if($goupas == 1 && $data2['stat'] == 0 && $editlock != -2)
		{
			// [6.4.1.2.1] On récupère le information relative au visionage pour le membre du groupe
			$vofpost = $data3['mg'];
			if($data3['mg'] >0) $autorisation = 1; else $autorisation = 0;
		}
		
		// [6.4.1.3] Si c'est un chef de ce groupe, on le considère comme un modérateur temporairement (juste sur cette page)
		elseif($goupas == 1 && $data2['stat'] == 1)
		{
			// [6.4.1.3.1] On met toutes les autorisations au max (sauf admin, mais, meme, ça changerai rien ici)
			$vofpost = 4;
			$autorisation = 1;
			$rang = 1;
		} 
		
		// [6.4.1.4] Ici, il ne reste que les membres, non membres du groupe
		elseif($goupas == 0)
		{
			// [6.4.1.4.1] On récupère le information relative au visionage pour le simple membre
			$vofpost = $data3['m'];
			if($data3['m'] >0 && $editlock != -2) $autorisation = 1; else $autorisation = 0;
		}
		
		else
		{
			// [6.4.1.4.1] On récupère le information relative au visionage pour le simple membre
			$autorisation = 0;
		}
	}
	// [6.4.2] Cas où rien prévu, donc admin ou md
	if($rang == 1 || $rang == 2)
	{ 
		$autorisation = 1; 
		$vofpost =4;
	}
}

// [6.5] Cas où rien n'est prévu, c'est qu'on a donc, l'autorisation
else
{
	$autorisation = 1;
}

// [7] Si les autorisations, ne sont pas fixées, on les mets au max (logiquement, il ne reste que les dm ou admins)
if(!isset($vofpost)) $vofpost = 4; 

// [8] On commence l'affichage
// [8.1] Cas où on refuse l'accès
if($autorisation == 0 || $autorisation == -1)
{	
	// [8.1.1] Cas où on a pas afficher de message d'erreur
	if($autorisation == 0)
	{
		echo'
		<table width="100%" class="texte_base_gras" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td height="29" align="center" class="titreforumend" style="padding-left:8px">'.bbcode(htmlentities($data3['nom'])).'</td>
			</tr>
			<tr>
				<td class="cadre_clair" style="padding:10px" align="center"><p>Vous n\'avez pas l\'autorisation de visioner ce Sujet.</p></td>
			</tr>
		</table>';
		}
	// [8.1.false] autrement, on a déjà affiché toutes les infos relatives à la restriction
	}
	
	// [8.2] Cas où on autorise l'accès
	else
	{

	// [8.2.1] Calcule du système d'affichage page par page
	
	//  /!\ Mini avertissement !!
	//  Les pages sont comme suit : la page un correspond à la variable GET 0 et ansi de suite, 
	//  on commce à numéroter depuis 0
	
	// [8.2.1.1] On a une page en GET
	if(isset($_GET['pg']))
	{ 
		$de = intval($_GET['pg'])*$postparpageaff; $p2=$_GET['pg']; 
	}
	// [8.2.1.2] Autrement, on considère qu'on commence à zéro
	else
	{
		$de = 0; $p2=0;
	}

	// [8.2.1.pause] On utilisera ça plus tard

	// [8.2.2] Affichage des boutons répondre/nouveau
	
	echo '
	<table class="texte_base_gras" width="100%" border="0" cellpadding="0" cellspacing="0">
	  	<tr>
	';

		// [8.2.2.1] On vérifie si il y a lieu d'afficher, l'espace pour les boutons
		if($rang != -1 && ($vofpost == 3 || $vofpost == 4 || $vofpost == 2))
		{
			echo'
			<td  class="cadre1_bas" style="padding:10px" width="175">';
  	
			// [8.2.2.1.1] Le membre peut-il poster?
			if($vofpost == 2 || $vofpost == 4)
			{
				// [8.2.2.1.1.A] On vérifie si il n'y a pas de contre indication
				if($data3['groupe'] != -1 && $data3['groupe'] != -3)
				{
					echo ' <a href="index.php?page=postadd&amp;idf='.$idsfa.'"><img src="./img/actions/nouveau.gif" alt"Nouveau Sujet" /></a>';
				} 
				// [8.2.2.1.1.B] Cas des admin ou modo, ils sont autorisés à poster
				elseif($rang == 2 || $rang == 1)
				{
					echo ' <a href="index.php?page=postadd&amp;idf='.$idsfa.'"><img src="./img/actions/verrouille.gif" alt="Vérouillé" /></a> ';
				} 
				// [8.2.2.1.1.C] Il y a contre indication
				else
				{
					echo ' <img src="./img/actions/verrouille.gif" alt="Vérouillé" /> ';
				}
			}
		
			// [8.2.2.1.false] Cas contraire, on affiche un bouton du type verouillé
			else echo ' <img src="./img/actions/verrouille.gif" alt="Vérouillé" />';
    
			// [8.2.2.1.2] Le membre peut-il répondre?
			if($vofpost == 3 || $vofpost == 4)
			{
				// [8.2.2.1.2.A] On vérifie si il n'y a pas de contre indication
  				if($editlock != -1 && $editlock != -2 && $data3['groupe'] != -1 && $data3['groupe'] != -1 && $data3['groupe'] != -3)
				{
					echo' <a href="index.php?page=postadd&amp;ids='.$_GET['ids'].'&amp;idfret='.$idsfa.'"><img src="./img/actions/repondre.gif" alt="Répondre" /></a> ';
				} 
				// [8.2.2.1.2.B] Cas des admin ou modo, ils sont autorisés à poster
				elseif($rang == 2 || $rang == 1)
				{
					echo ' <a href="index.php?page=postadd&amp;ids='.$_GET['ids'].'&amp;idfret='.$idsfa.'"><img src="./img/actions/verrouille.gif" alt="Vérouillé" /></a> ';
				} 
				// [8.2.2.1.1.C] Il y a contre indication
				else
				{ 
					echo ' <img src="./img/actions/verrouille.gif" alt="Vérouillé" /> '; 
				}
			}
		
			// [8.2.2.1.false] Cas contraire, on affiche un bouton du type verouillé
		 	else echo ' <img src="./img/actions/verrouille.gif" alt="Vérouillé" /> ';
			echo'</td>
			';
		}
	
		// [8.2.3] Affichage du nom du forum
		echo'
			<td class="cadre1_bas" style="padding:10px"><a href="index.php">'.htmlentities($nomduforum).'</a> -> <a href="index.php?page=forum&amp;idf='.$idsfa.'">'.(bbcode(htmlentities($data3['nom']))).'</a></td>
			<td width="180" align="right" class="cadre1_bas" style="padding:5px">
		'; 
	
		// [8.2.4] Creation de la fonction d'affichage du page par page
		function page_par_page ()
		{
			// [8.2.4.0] importation de variable
			global $nbentree2,$postparpageaff,$de,$p2;
	
			// [8.2.4.1] Si il y a des entrées
			if($nbentree2 != 0) echo'Page : '; 
			$p3 = $p2-1;

			// [8.2.4.2] Si on est pas sur la première page, on peut donc mettre le lien vers la page précedente
			if($p2 != 0)
			{
				echo '<a href="index.php?page=post&amp;ids='.$_GET['ids'].'&amp;pg='.$p3.'">Pr&eacute;c.</a>,';
			}
		
			// [8.2.4.3] Si on est pas sur la première page, on peut donc mettre le lien vers la page précedente
			if($p2 > 1)
			{
				echo '<a href="index.php?page=post&amp;ids='.$_GET['ids'].'&amp;pg=0">1,</a>'; 
			}

			// [8.2.4.etc] Enfin, c'est de la logique, pas besoin d'y toucher, je passe ça
			if($p2 > 2)
			{
				echo'...,';
			}
			$nbpage = ceil($nbentree2/$postparpageaff); 
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
				echo '<a href="index.php?page=post&amp;ids='.$_GET['ids'].'&amp;pg='.$p.'">';
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
			if($p2 < $nbpage-3-$pc)
			{
				echo'...,';
			}
			$p5 = $nbpage-1;
			if($p2 < $nbpage-2 && $nbpage > 3)
			{
				echo '<a href="index.php?page=post&amp;ids='.$_GET['ids'].'&amp;pg='.$p5.'">'.$nbpage.'</a>';
			}
			if($p2 < $nbpage-3 && $nbpage <= 3)
			{
				echo '<a href="index.php?page=post&amp;ids='.$_GET['ids'].'&amp;pg='.$p5.'">'.$nbpage.'</a>';
			}
			$p4 = $p2+1;
			if($p2 != $nbpage-1)
			{
				echo '<a href="index.php?page=post&amp;ids='.$_GET['ids'].'&amp;pg='.$p4.'">,Suiv.</a>';
			}
		}
		// [8.2.4.fin] Fin de la fonction d'affichage du page par page

		echo page_par_page().'	
			</td>
		</tr>
	</table>
	';

// [8.2.5] Affichage du nom du post et de ses réponses
// /!\ Petit explication le champ sondage correspond au type de post (annonce,post-it,...) (dsl pour la bidouille)
// [8.2.5.1] On sélectionne ce qu'on affichera, en tenant compte des limites
$sql = '
	SELECT id2,rangspec,titre,sign,signaff,edit,ip,texte,pseudode,idsa,tmpsave,pseudo,nbpost,idde,rang,id,avatar,tmppost,www FROM '.$prefixtable.'post
	LEFT JOIN '.$prefixtable.'membres ON '.$prefixtable.'membres.id = '.$prefixtable.'post.idde
	WHERE '.$prefixtable.'post.id2 = '.intval($_GET['ids']).' OR '.$prefixtable.'post.idsa = '.intval($_GET['ids']).' ORDER BY '.$prefixtable.'post.id2 LIMIT '.$de.','.$postparpageaff;

$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
$requse++;
$nbentree = mysql_num_rows($req);

echo'
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="2" class="texte_base_gras">
			<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
			';
		  	if($sondageaff == 1)
			{
				$sqls = 'SELECT s.idsond,s.texte,s.nbvote,s.nboption,s.tmpvote,v.idvoteur  FROM '.$prefixtable.'sondage AS s LEFT JOIN '.$prefixtable.'voter AS v ON (s.idpost=v.idpost) AND (v.idvoteur=0) WHERE s.idpost = '.$_GET['ids'].' ORDER BY s.nboption DESC, s.idsond ASC';
				if(!empty($_SESSION['idlog'])) $sqls = 'SELECT s.idsond,s.texte,s.nbvote,s.nboption,s.tmpvote,v.idvoteur  FROM '.$prefixtable.'sondage AS s LEFT JOIN '.$prefixtable.'voter AS v ON (s.idpost=v.idpost) AND (v.idvoteur='.$_SESSION['idlog'].') WHERE s.idpost = '.$_GET['ids'].' ORDER BY s.nboption DESC,s.idsond ASC';
				$reqs = mysql_query($sqls) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); $requse++;
			echo '
				<tr>
					<td colspan="2">
						<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr align="center">
								<td height="29" class="titreforumend">Sondage</td>
							</tr>
							<tr align="center">
								<td height="29" class="cadre1_bas" style="padding-top:15px;padding-bottom:15px">'; 
								$datas = mysql_fetch_assoc($reqs);
								$sur = $datas['nbvote'];
								$nbentreevote = $datas['nboption'];
								$timeend = $datas['tmpvote'];
								$iddejaopost= $datas['idvoteur'];
								if(($timeend > time() || $timeend == 0) && empty($iddejaopost) && ($vofpost == 3 || $vofpost == 4) && !empty($_SESSION['idlog']) && !isset($_GET['affsond']) && $editlock != -1) $nbcols = 2; else $nbcols = 3;
								echo '
									<form action="index.php?page=voteadd&amp;ids='.$_GET['ids'].'" method="post">
									<table>
										<tr>
									';
									if($nbcols == 2)
										echo '
										';
										echo'
											<td colspan="'.$nbcols.'" align="center" style="padding-bottom:8px">
										';
										echo (htmlentities($datas['texte'])).'</td>
										</tr>
										';
									while($datas = mysql_fetch_assoc($reqs))
									{
										if(($timeend > time() || $timeend == 0) && empty($iddejaopost) && ($vofpost == 3 || $vofpost == 4) && !empty($_SESSION['idlog']) && !isset($_GET['affsond'])  && $editlock != -1)
										{
										echo '
										<tr>
											<td width="5"><input type="radio" name="id_option" value="'.$datas['idsond'].'"></td>
											<td>'.(htmlentities($datas['texte'])).'</td>
										</tr>
										';
										}
										else
										{
											if($datas['nbvote'] > 0 && $sur > 0) $size = round(($datas['nbvote']/$sur)*150); else $size = 1;
											if($datas['nbvote'] > 0 && $sur > 0) $pourc = round(($datas['nbvote']/$sur)*100); else $pourc = 0;
										echo '
										<tr>
											<td align="right" style="padding-right:4px">'.(htmlentities($datas['texte'])).'</td>
											<td align="left"><img src="./img/sondeleft.gif" alt="" /><img src="./img/sondecentre.gif"  width="'.$size.'" height="13" alt="" /><img src="./img/sonderight.gif" alt="" /></td>
											<td style="padding-right:6px" align="left">'.$pourc.'% ['.$datas['nbvote'].']</td>
										</tr>
										';
										}
									}
									echo '
										<tr>
											<td colspan="'.$nbcols.'" align="center" style="padding-top:8px">
									';
												if($nbcols == 2)
												echo '
												<input type="submit" name="Submit" value="Envoyer" class="bouton" />
													<br />
													<br />
													<a href="index.php?page=post&amp;ids='.$_GET['ids'].'&amp;affsond">Afficher les votes</a><br />
												';
												else
												echo 'Nombre d\'option : '.$nbentreevote.' ['.$sur.']
											</td>
										';
	  								// if($nbcols == 2) echo '</form>';
									echo'
									</tr>
								</table>
									</form>
								</td>
							</tr>
						</table>
			';
			} else echo'<tr><td>';
			mysql_close();		  
			echo'  
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="texte_base_gras">          
			<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="150" height="29" align="center" class="titreforum">Auteur</td>
					<td class="titreforumend"  align="center">Message</td>
				</tr>
				';
				if(isset($_GET['pg']))
				{
					if($_GET['pg'] == 0)
					{
						$cmt = 0;
					}
					else
					{
						$cmt=1;
					}
				}
				else
				{
					$cmt=0;
				}
				$color = 'alternate1';
				
				while ($data = mysql_fetch_assoc($req)) 
				{
					$cmt++;
					if($color == "alternate1")
					{
						$color = "alternate2";
					}
					else
					{
						$color = "alternate1";
					}
				echo'
				<tr>
					<td width="150" height="50" align="center" valign="top" class="cadre_clair" style="padding:10px"><a name="'.$data['id2'].'"></a><b>'.htmlentities($data['pseudo']).'</b>
					<br />
					';
					if($data['rangspec'] > 0)
					{
						$kk = $data['rangspec']-1;
						if(!empty($rangimage[$kk])) echo '<img src="'.$rangimage[$kk].'" alt="image du rang" /><br />';
						echo '<span style="color: '.$rangcouleur[$kk].'">'.$rangnom[$kk].'</span>';
					}
					else
					{
						$cont=0;
						for($kk=0;$cont<1;$kk++)
						{
							if($rangpostmin[$kk] <= $data['nbpost']) 
							{ 
								if(!empty($rangimagem[$kk])) echo '<img src="'.$rangimagem[$kk].'" alt="image du rang" /><br />';
								$cont=1;
								echo ''.$rangmembre[$kk];
							}
						}
					}
					
					echo'
					<br />
					<br />
					';
					$idavatar = $data['id'];
					if($data['avatar'] != "" && $data['avatar'] != "http://") echo '<img src="'.$data['avatar'].'" alt="'.$data['pseudo'].'" />';
					
					echo'
					<br />                  
					Messages: '.$data['nbpost'].'<br />';
					if($rang == 2 && $ipaff) echo 'ip : '.professordekodor($data['ip']);

					echo'
					</td>
					<td valign="top" class="'.$color.'" style="padding:10px">
						<table width="100%" border="0" cellpadding="0" cellspacing="0" style="padding-bottom:5px">
							<tr>
								<td class="posthaut">Post&eacute; le: ';
								if($data['idsa'] == 0) echo datefct($data['tmpsave'],$gmt);
								else echo datefct($data['tmppost'],$gmt);
								echo' || Sujet du message: '.sit(htmlentities(($data['titre']))).'
								</td>
                  				<td width="200" align="right" class="posthaut">';
								if($rang != -1 && $editlock != -1 || $rang == 1 || $rang == 2) echo' <a href="index.php?page=postadd&amp;ids='.$_GET['ids'].'&amp;cit='.$data['id2'].'"><img src="./img/actions/citer.gif" alt="Citer" /></a>';
								if($data['id'] == $idmembre || $rang == 2 || $rang == 1) echo' <a href="index.php?page=postadd&amp;edit='.$data['id2'].'&amp;pg='.$p2.'&amp;ids='.$_GET['ids'].'"> <img src="./img/actions/editer.gif" alt="Editer" /></a>';
								if($rang == 2 && $cmt > 1 || $rang == 1 && $cmt > 1) echo' <input name="delpost" type="image" src="./img/actions/supprimer.gif" onclick="decision(\'Voulez-vous vraiment supprimer \ndefinitivement ce post?\',\'delpost.php?id2='.$data['id2'].'\')" />';
								echo'</td>
							</tr>
						</table>
						'.bbcode(nl2br(sit(($data['texte']))));
						if(!empty($data['edit']))
						{
							echo'<p class="edit">[Ce message &agrave; &eacute;t&eacute; &eacute;dit&eacute; par son auteur pour la derni&egrave;re fois le '.datefct($data['edit'],$gmt).']</p>';
						}
						if($bbcodesign && !empty($data['sign']) && $data['signaff'] == 1 && $autorisationsign) echo '<br />________________<br />'.bbcode(nl2br(sit(($data['sign']))));
						elseif(!empty($data['sign']) && $data['signaff'] == 1 && $autorisationsign) echo '<br />________________<br />'.nl2br(htmlentities(($data['sign'])));
					echo'
					</td>
				</tr>
				<tr>
					<td class="cadre_clair" style="padding:6px"><a href="#top">Revenir en haut</a></td>
					<td valign="top" class="'.$color.'" style="padding:8px"><a href="index.php?page=affprofil&amp;id='.$data['id'].'"><img src="./img/actions/profil.gif" alt="Voir le Profil" /></a>';
					if($rang != -1 && $data['id'] != $idmembre) echo' <a href="index.php?page=mpsend&amp;id='.$data['id'].'"><img src="./img/actions/mp.gif" alt="Envoyer un Message Privé" /></a>';
					if($data['www'] != "" && $data['www'] != "http://") echo' <a href="'.htmlentities($data['www']).'"><img src="./img/actions/www.gif" alt="Voir le Site Web" /></a>';
					echo'
					</td>
				</tr>
				<tr>
					<td colspan="2" class="espace"><img src="./img/space.gif" alt="" /></td>
				</tr>
				';
				}
			echo'
			</table>';
			echo'
		</td>
	</tr>
</table>
';
echo'
<table class="texte_base_gras" width="100%" border="0" cellpadding="0" cellspacing="0">
';
////
	echo'
  	<tr>
	';
  	// [8.2.2.1] On vérifie si il y a lieu d'afficher, l'espace pour les boutons
	if($rang != -1 && ($vofpost == 3 || $vofpost == 4 || $vofpost == 2))
	{
		echo'
		<td  class="cadre1_bas" style="padding:10px" width="175">
		';
  	
		// [8.2.2.1.1] Le membre peut-il poster?
		if($vofpost == 2 || $vofpost == 4)
		{
			// [8.2.2.1.1.A] On vérifie si il n'y a pas de contre indication
			if($data3['groupe'] != -1 && $data3['groupe'] != -3)
			{
				echo'<a href="index.php?page=postadd&amp;idf='.$idsfa.'"><img src="./img/actions/nouveau.gif" alt"Nouveau Sujet" /></a>';
			}
			// [8.2.2.1.1.B] Cas des admin ou modo, ils sont autorisés à poster
			elseif($rang == 2 || $rang == 1)
			{
				echo ' <a href="index.php?page=postadd&amp;idf='.$idsfa.'"><img src="./img/actions/verrouille.gif" alt="Vérouillé" /></a> ';
			} 
			// [8.2.2.1.1.C] Il y a contre indication
			else
			{
				echo ' <img src="./img/actions/verrouille.gif" alt="Vérouillé" />'; 
			}
		}
		
		// [8.2.2.1.false] Cas contraire, on affiche un bouton du type verouillé
		else echo ' <img src="./img/actions/verrouille.gif" alt="Vérouillé" />';
    
		// [8.2.2.1.2] Le membre peut-il répondre?
		if($vofpost == 3 || $vofpost == 4)
		{
			// [8.2.2.1.2.A] On vérifie si il n'y a pas de contre indication
  			if($editlock != -1 && $editlock != -2 && $data3['groupe'] != -1 && $data3['groupe'] != -1 && $data3['groupe'] != -3)
			{
				echo' <a href="index.php?page=postadd&amp;ids='.$_GET['ids'].'&amp;idfret='.$idsfa.'"><img src="./img/actions/repondre.gif" alt="Répondre" /></a> ';
			} 
			// [8.2.2.1.2.B] Cas des admin ou modo, ils sont autorisés à poster
			elseif($rang == 2 || $rang == 1)
			{
				echo ' <a href="index.php?page=postadd&amp;ids='.$_GET['ids'].'&amp;idfret='.$idsfa.'"><img src="./img/actions/verrouille.gif" alt="Vérouillé" /></a> ';
			} 
			// [8.2.2.1.1.C] Il y a contre indication
			else
			{ 
				echo ' <img src="./img/actions/verrouille.gif" alt="Vérouillé" /> '; 
			}
		}
		
		// [8.2.2.1.false] Cas contraire, on affiche un bouton du type verouillé
	 	else echo ' <img src="./img/actions/verrouille.gif" alt="Vérouillé" /> ';
  
  		echo'
		</td>
		';
	}
////

if($rang == 1 || $rang == 2) 
{
	echo'
		<td class="cadre1_bas" style="padding:10px">
		';
		if($editlock == 0)
		{
			echo'
			<a href="index.php?page=lockforum&amp;ids='.$_GET['ids'].'&amp;stat=-1"><img src="./img/moderation/verrouiller.gif" alt="Vérouiller le Sujet" /></a> <a href="index.php?page=lockforum&amp;ids='.$_GET['ids'].'&amp;stat=-2"><img src="./img/moderation/surverrouiller.gif" alt="Surverouiller" /></a>';
		}
		else
		{
			echo'
			<a href="index.php?page=lockforum&amp;ids='.$_GET['ids'].'&amp;stat=0"><img src="./img/moderation/deverrouiller.gif" alt="Dévérouiller" /></a>';
		}
		echo'
			<a href="index.php?page=type&amp;ids='.$_GET['ids'].'&amp;stat=0"><img src="./img/moderation/post.gif" alt="Simple Post" /></a>  <a href="index.php?page=type&amp;ids='.$_GET['ids'].'&amp;stat=1"><img src="./img/moderation/postit.gif" alt="Post-it" /></a>  <a href="index.php?page=type&amp;ids='.$_GET['ids'].'&amp;stat=2"><img src="./img/moderation/annonce.gif" alt="Mettre en Annonce" /></a>  <a href="" onclick="window.open(\'moveto.php?ids='.$_GET['ids'].'&amp;f='.$idsfa.'\', \'\', \'HEIGHT=350,resizable=yes,scrollbars=yes,WIDTH=400\');return false;"><img src="./img/moderation/deplacer.gif" alt="Déplacer le Sujet" /></a> <input name="delall" type="image" src="./img/moderation/suppr_sujet.gif" border="0" onclick="decision(\'Voulez-vous vraiment supprimer\n definitivement ce sujet dans son entierté ?\',\'delpost.php?id2='.$_GET['ids'].'\')" />';
		echo'
			<a href="resinchr.php?id2='.$_GET['ids'].'"><img src="./img/moderation/synchroniser.gif" alt="Synchroniser" /></a>
			';
		echo'
			<input name="delpost" type="image" src="./img/moderation/suppr_sondage.gif" border="0" onclick="decision(\'Voulez-vous vraiment supprimer definitivement\n le sondage de ce post ?\',\'index.php?page=delsonde&amp;ids='.$_GET['ids'].'\')" />';
		echo '
		</td>
		';
}

	echo'
		<td class="cadre1_bas" style="padding:5px" align="right">';
		echo page_par_page().'
		</td>
	</tr>
</table>
';
}
if($rang != -1 && ($vofpost == 3 || $vofpost == 4 || $vofpost == 2))
{
	if($affreprapide && ($vofpost == 3 || $vofpost == 4 || $rang == 2 || $rang == 1))
	{
		// [8.2.2.1.2.A] On vérifie si il n'y a pas de contre indication
		if($editlock != -1 && $editlock != -2 && $data3['groupe'] != -1 && $data3['groupe'] != -1 && $data3['groupe'] != -3)
		{
			echo '
<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr align="center">
		<td height="29" class="titreforumend">Réponse rapide</td>
	</tr>
	<tr>
		<td class="cadre1_bas" style="padding:10px">
			<form action="index.php?page=postadd&amp;ids='.intval($_GET['ids']).'"  method="post" enctype="multipart/form-data" name="news">
				<input maxlength="64" type="text" class="bouton" name="titre" style="width:400px" />
				<br />
				<textarea name="texte" class="bouton_rep_rapide" id="texte"></textarea>
				<br />
				<input type="submit" name="previsu" class="bouton" value="Prévisualiser" />
				<input type="submit" name="sendage" class="bouton" value="Envoyer" />
			</form>
		</td>
	</tr>
</table>';
		}
	}
}
//

mysql_free_result($req);
?> 