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
 
if(!isset($pseudo)) header('Location: index.php?page=erreur'); 

?> 
<table class="texte_base_gras" width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="cadre1_bas" style="padding:10px">Le <?php echo datefct(time(),$gmt).', <br /><a href="index.php">Index : '.htmlentities($nomduforum).'</a>'; ?></td>
		<td align="right" valign="bottom" class="cadre1_bas" style="padding:10px"><a href="cooktmp.php">Marquer tous les messages comme lus</a></td>
	</tr>
</table>
<?php
	echo '
<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
	';
	
	if($cache_forum) $sql = 'SELECT f.*,m.pseudo AS pseudoposteur FROM '.$prefixtable.'forum AS f LEFT JOIN '.$prefixtable.'membres AS m ON f.adernier=m.id '.$where.' ORDER BY position';
	else $sql = 'SELECT f.*,m.pseudo AS pseudoposteur FROM '.$prefixtable.'forum AS f LEFT JOIN '.$prefixtable.'membres AS m ON f.adernier=m.id ORDER BY position';
	$req = mysql_query($sql);
	$requse++;
	mysql_close();
	while($data = mysql_fetch_assoc($req))
	{
		if(empty($data['fatt']))
		{
		
			$aff_forum = false;
			if(empty($_SESSION['idlog']) && $cache_forum ) {
			
				
			
			}
		
		echo '
		<tr>
			<td colspan="2" class="titreforum" style="padding-left:8px"><img src="img/design/icot_n.gif" align="absmiddle" width="48" height="8" />'.bbcode(htmlentities($data['nom'])).'</td>
			<td width="52" class="titreforum"><div align="center">Sujets</div></td>
			<td width="75" class="titreforum"><div align="center">R&eacute;ponses</div></td>
			<td width="155" class="titreforumend"><div align="center">Dernier message </div></td>
		</tr>
		';
		}
		else
		{
		echo'
		<tr>
			<td width="1" height="1" class="cadre_clair">
		';
		if($data['groupe'] == -1 ||$data['groupe'] == -3) echo '<img src="./img/statut/forum_verrouille.jpg" alt="Forum Vérouillé" />';
		elseif(isset($_SESSION['forumtime'.$data['id']]) && !empty($pseudo))
		{
			if($_SESSION['lastvisit'] < $_SESSION['forumtime'.$data['id']])
			{
			if($data['temps'] <= $_SESSION['forumtime'.$data['id']] && $_SESSION['kk'.$data['id']] == 0) echo '<img src="./img/statut/forum.jpg" alt="Pas de nouveaux messages" />';
			else echo '<img src="./img/statut/n_forum.jpg" alt="Nouveau(x) message(s) !" />';
		}
		else
		{
			if($data['temps'] > $_SESSION['lastvisit']) echo '<img src="./img/statut/n_forum.jpg" alt="Nouveau(x) message(s) !" />';
			else echo '<img src="./img/statut/forum.jpg" alt="Pas de nouveaux messages" />';
		}
		// echo $_SESSION['rrr'.$data['id']].' '.$_SESSION['kk'.$data['id']];
	}
	elseif(isset($_SESSION['lastvisit'])  && !empty($pseudo))
	{
  		if($data['temps'] > $_SESSION['lastvisit']) echo '<img src="./img/statut/n_forum.jpg" alt="Nouveau(x) message(s) !" />';
  		else echo '<img src="./img/statut/forum.jpg" alt="Pas de nouveaux messages" />';
  	}
	else
	{
		echo '<img src="./img/statut/forum.jpg" alt="Pas de nouveaux messages" />';
	}
		echo '</td>
	        <td class="cadre_clair" style="padding:10px">';
			echo '<a href="index.php?page=forum&amp;idf='.$data['id'].'">';
			echo bbcode(htmlentities($data['nom'])).'</a><br />
			<span class="texte_base_fin">'.bbcode(htmlentities($data['description'])).'</span></td>
			<td width="52" class="cadre_fonce" align="center">'.$data['nbsujet'].'</td>
			<td width="75" class="cadre_clair" align="center">'.$data['nbmessage'].'</td>
			<td class="cadre_fonce_end" align="center">';
			if($data['adernier'] != "-")
			{
				echo datefct($data['temps'],$gmt).'<br />par <a href="index.php?page=affprofil&amp;id='.$data['adernier'].'">'.htmlentities($data['pseudoposteur']).'</a><a href="redir_last_post.php?forum='.$data['id'].'"><img src="./img/statut/icon_latest_reply.gif" alt="Dernier message par :" /></a>';}
			else echo '-';
				echo'
			</td>
		</tr>
		';
	}
}

if(mysql_num_rows($req) == 0)echo '<tr>
		<td class="cadre1_bas" style="padding:30px"><h2>Bonjour et bienvenue sur votre forum SoftBB</h2>
<p class="texte_base_fin">Vous allez pouvoir configurer votre forum dés que vous vous serez connectez au forum avec le compte administrateur. Si ce n\'est pas déjà fait, connectez vous</p><p class="texte_base_fin">Rendez-vous ensuite dans l\'administration de votre forum : <a href="admin/">Atteindre l\'administration</a></p><h2>Support</h2><p class="texte_base_fin">Si vous avez le moindre problème, n\'hésitez pas à vous rendre sur le site officiel du forum : <a href="http://www.softbb.be">Softbb.be</a></p></td>
	</tr></table>';

else echo '</table>
<p align="center"><img src="./img/footer/legende.jpg" alt="Nouveaux Messages - Pas de Nouveaux Messages - Forum Vérouillé" /></p>
';
mysql_free_result($req);
?>