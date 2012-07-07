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
<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr align="center">
		<td height="29" class="titreforum">Groupe - Erreur</td>
	</tr>
	<tr>
		<td align="center" class="cadre_clair" style="padding:30px"> 
		<?php mysql_close();
		if(isset($_GET['type']))
		{
			if($_GET['type'] == "membreban")
			{
				echo'<p>Ce membre n\'a pas validé son compte, ou il a été banni. (ou il n\'existe simplement pas)</p>';
			}
			if($_GET['type'] == "deja")
			{
				echo'<p>Ce membre fait déjà partie de ce groupe</p>';
			}
		}
		echo '<p><a href="index.php?page=affgroupe&amp;groupe='.$_GET['retour'].'">Retour &agrave; la page d\'affichage du groupe</a></p>';
		?>
		</td>
	</tr>
</table>          