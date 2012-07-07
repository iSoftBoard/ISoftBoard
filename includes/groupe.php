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
?>
<table class="texte_base_gras" width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="cadre1_bas" style="padding:10px"><?php echo '<a href="index.php">Index : '.htmlentities($nomduforum).'</a>'; ?></td>
	</tr>
</table>

<table class="texte_base_gras" width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr align="center">
		<td height="29" class="titreforumend">Groupe</td>
	</tr>
	<tr>
		<td align="center" class="cadre1_bas" style="padding:20px">
<?php
					$sql = 'SELECT * FROM '.$prefixtable.'groupe ORDER BY id DESC';
					$req = mysql_query($sql);
					$requse++;
					mysql_close();
					
					if(mysql_num_rows($req) != 0) {
					
							echo '<form name="form1" method="post" action="index.php?page=affgroupe">
						<select name="groupe" class="sbouton">';
							
							while($data = mysql_fetch_assoc($req))
							{
								echo '<option value="'.$data['id'].'">'.htmlentities($data['nom']).'</option>';
							}
							
							echo '</select>
						<input type="submit" name="Submit" class="bouton" value="Afficher les informations relatives &agrave; ce groupe" />
						</form>';
						}
					else echo '<p>Il n\'y a actuellement pas de groupe dans ce forum</p>';
				
				?>
		</td>
	</tr>
</table>          