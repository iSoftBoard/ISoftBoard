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
 
$sql = 'SELECT id,pseudo,rang,nbpost,localisation,www,valid FROM '.$prefixtable.'membres';
$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
$requse++;
$nbentree = mysql_num_rows($req);
mysql_free_result($req);
if(isset($_GET['pg']))
{
	$de = intval($_GET['pg'])*$membreparpage; $p2=intval($_GET['pg']);
}
else
{
	$de = 0; $p2=0;
}
?>
<table class="texte_base_gras" width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="cadre1_bas" width="500" style="padding:5px">
			<form name="form1" method="post" action="index.php?page=affprofil">
				<label for="search">Rechercher un membre :</label> <input id="search" name="pseudo" type="text" size="20" maxlength="128" class="bouton" /><input type="submit" name="Submit" value="Rechercher ce membre" class="bouton" />
			</form>
			</td>
		<td align="right" class="cadre1_bas" style="padding:5px">
		Page : 
		<?php 
		if($p2 > 1)
		{
			echo'... ';
		}
		$nbpage = ceil($nbentree/$membreparpage); 
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
			echo '<a href="index.php?page=membre&amp;pg='.$p.'">';
			if($p2 == $p)
			{
				echo'<span class="admin">';
			}
			echo $p+1; 
			if($p2 == $p)
			{
				echo'</span>';
			}
			echo '</a>';if($p != $nbpage-1)
			{
				echo',';
			}
		}
		if($p2 < $nbpage-2-$pc)
		{
			echo'... ';
		}
		?>
		</td>
	</tr>
</table>
<?php
$sql = 'SELECT id,pseudo,rang,nbpost,localisation,www,valid FROM '.$prefixtable.'membres ORDER BY pseudo LIMIT '.intval($de).','.intval($membreparpage);
$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
$requse++;
mysql_close();
echo '
<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height="29" class="titreforum" style="padding-left:8px">Nom d\'utilisateur </td>
		<td width="20%" class="titreforum"><div align="center">Site web </div></td>
		<td width="25%" class="titreforum"><div align="center">Localisation</div></td>
		<td width="10%" class="titreforumend"><div align="center">Posts</div></td>
	</tr>
';
while ($data = mysql_fetch_assoc($req)) 
{
	if($rang == 2)
	{
	echo '
	<tr>
		<td class="cadre_clair" style="padding:5px">
			<a href="index.php?page=affprofil&amp;id='.$data['id'].'">';
			if($data['valid'] == "0")
			{
				echo '<span class="red">'.htmlentities($data['pseudo']).'</span>';
			}
	  		elseif($data['rang'] == "2")
			{
				echo '<span class="admin">'.htmlentities($data['pseudo']).'</span>';
			}
			elseif($data['rang'] == "1" || $data['rang'] == "3")
			{
				echo '<span class="modo">'.htmlentities($data['pseudo']).'</span>';
			}
			elseif($data['valid'] == "0")
			{
				echo '<span class="red">'.htmlentities($data['pseudo']).'</span>';
			}
			else
			{
				echo htmlentities($data['pseudo']);
			}
			echo'
			</a>
		</td>
		<td class="cadre_fonce" align="center" style="padding:5px">';
		if(!empty($data['www']) && $data['www'] != "http://")
		{
			echo'<a href="'.htmlentities($data['www']).'">[ Voir le site web ]</a>';
		}
		else
		{
			echo '-';
		}
		echo '</td>
		<td class="cadre_clair" align="center" style="padding:5px">'.htmlentities($data['localisation']).'</td>
		<td class="cadre_fonce_end" align="center" style="padding:5px">'.$data['nbpost'].'</td>
	</tr>';
	}
	else
	{
	echo '
	<tr>
		<td class="cadre_clair" style="padding:5px">
			<a href="index.php?page=affprofil&amp;id='.$data['id'].'">';
			if($data['valid'] == "0")
			{
				echo '<span class="red">'.htmlentities($data['pseudo']).'</span>';
			}
			elseif($data['rang'] == "2")
			{
				echo '<span class="admin">'.htmlentities($data['pseudo']).'</span>';
			}
			elseif($data['rang'] == "1" || $data['rang'] == "3")
			{
				echo '<span class="modo">'.htmlentities($data['pseudo']).'</span>';
			}
			elseif($data['valid'] == "0")
			{
				echo '<span class="red">'.htmlentities($data['pseudo']).'</span>';
			}
			else
			{
				echo htmlentities($data['pseudo']);
			}
			echo'
			</a>
		</td>
		<td class="cadre_fonce" align="center"  style="padding:5px">';
		if(!empty($data['www']) && $data['www'] != "http://")
		{
			echo'<a href="'.htmlentities($data['www']).'">[ Voir le site web ]</a>';
		}
		else
		{
			echo '-';
		}
		echo '
		</td>
		<td class="cadre_clair" align="center" style="padding:5px">'.htmlentities($data['localisation']).'</td>
		<td class="cadre_fonce_end" align="center" style="padding:5px">'.$data['nbpost'].'</td>
	</tr>';
	}
} 
echo '
</table>
';
mysql_free_result($req);
?>