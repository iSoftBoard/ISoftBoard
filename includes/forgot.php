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
mysql_close(); ?>
<table class="texte_base_gras" width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="cadre1_bas" style="padding:10px"><?php echo '<a href="index.php">Index : '.htmlentities($nomduforum).'</a>'; ?></td>
	</tr>
</table>
<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr align="center">
		<td height="29" class="titreforum">Remplacement de l'ancien mot de passe </td>
	</tr>
	<tr align="center">
		<form name="form1" method="post" action="">
			<td class="cadre_clair" style="padding:30px">
				<table border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td colspan="2" style="padding:10px">
							<div align="center">
								Pour remplacer votre mot de passe actuel par un autre mot de passe, <br />entrez simplement les informations demand&eacute;es dans ce formulaire.<br />Vous recevrez un E-Mail vous indiquant votre nouveau mot de passe.
							</div>
						</td>
					</tr>
					<tr>
						<td align="right" style="padding:5px">Nom d'utilisateur :</td>
						<td style="padding:5px"><input name="pseudolog" type="text" id="pseudolog" class="bouton" /></td>
					</tr>
					<tr>
						<td align="right" style="padding:5px">Adresse E-Mail :</td>
						<td style="padding:5px"><input name="mdp" type="password" id="mdp" class="bouton" /></td>
					</tr>
					<tr>
						<td colspan="2" style="padding-top:10px">
							<div align="center"><input type="submit" name="Submit" value="Connexion" class="bouton" /></div></td>
					</tr>
				</table>
			</td>
		</form>
	</tr>
</table>