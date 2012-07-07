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
?><table class="texte_base_gras" width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="cadre1_bas" style="padding:10px"><?php echo '<a href="index.php">Index : '.htmlentities($nomduforum).'</a>'; ?></td>
	</tr>
</table>

<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr align="center">
		<td height="29" colspan="3" class="titreforumend">
		Groupe - 
		<?php
		if(isset($_POST['groupe']))
		{
			$groupe = $_POST['groupe'];
		}
		else
		{
			$groupe = $_GET['groupe'];
		}
		$sql = 'SELECT nom FROM '.$prefixtable.'groupe WHERE id = '.intval($groupe);
		$req = mysql_query($sql);
		$requse++;
						
		if(mysql_num_rows($req) == 0)
		{
			include('./includes/erreur.php'); exit();
		}
		$data2 = mysql_fetch_assoc($req);
		echo htmlentities($data2['nom']);
		?>
		</td>
	</tr>
	<tr>
		<?php
		$sql = 'SELECT g.id,g.idm,g.idg,g.stat,m.pseudo FROM '.$prefixtable.'groupemembre AS g LEFT JOIN '.$prefixtable.'membres AS m ON g.idm=m.id  WHERE g.idg = "'.intval($groupe).'" ORDER BY m.pseudo';
		$req = mysql_query($sql);
		$requse++;
		mysql_close();
		if(mysql_num_rows($req) == 0)
		echo'
			<tr>
				<td colspan="3" class="cadre_clair" style="padding:20px" align="center">Il n\'y a pas de membre dans ce groupe</td>
			</tr>
		';
		while($data = mysql_fetch_assoc($req))
		{
			echo '
			<tr>
				<td class="cadre_clair" style="padding:4px">
			';
				if($data['stat'] == 1)
				echo'<span class="admin">';
				echo htmlentities($data['pseudo']);
				if($data['stat'] == 1)
				echo'</span>';
				echo'
				</td>
				<td width="260" align="center" class="cadre_clair" style="padding:4px">
				';
				if($rang == 2 || $rang == 1)
				{
					if($data['stat'] == 0)
					{
						echo'
						<a href="statgroupe.php?idm='.$data['idm'].'&amp;idg='.$groupe.'&amp;act=up">Membre du groupe -> Passer à Chef';
					}
					else
					{
						echo'
						<a href="statgroupe.php?idm='.$data['idm'].'&amp;idg='.$groupe.'&amp;act=dw">Chef du groupe -> Passer à Membre</a>';
					}
				}
				else
				{
					if($data['stat'] == 0)
					{
						echo'Membre du groupe';
					}
					else
					{
						echo'Chef du groupe';
					}
				}
				echo'
				</td>
				<td width="200" align="center" class="cadre1_bas" style="padding:4px">
				';
				if($rang == 2 || $rang == 1)
				{
					echo'<a href="delfgroupe.php?idm='.$data['idm'].'&amp;idg='.$groupe.'">supprimer du groupe</a>';
				}
				else
				{
					echo' - ';
				}
				echo'
				</td>
			</tr>
			';
		}
		if($rang == 1 || $rang == 2)
		{
			echo'
				<tr align="center">
					<td height="29" colspan="3" class="titreforumend">Ajouter un membre</td>
				</tr>
				<tr>
					<form name="form1" method="post" action="addmembre.php?groupe='.$groupe.'">
						<td colspan="3" class="cadre1_bas" style="padding:20px" align="center">
							<input name="pseudo" type="text" size="30" maxlength="64" class="bouton" />
							<input type="submit" name="Submit" value="Ajouter ce membre &agrave; ce groupe" class="bouton" />
						</td>
					</form>
				</tr>
			';
		}
		
		?>
	</tr>
</table>  