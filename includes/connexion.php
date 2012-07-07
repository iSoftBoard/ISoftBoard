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
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_goToURL() //v3.0
{
	var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
	for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
//-->
</script>
<table  class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr align="center">
		<td height="29" class="titreforum">Connexion au forum</td>
	</tr>
	<tr>
		<td align="center" class="cadre_clair" style="padding:10px">
			<form name="form1" method="post" action="login.php">
				<p>
					<table border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td align="right" style="padding:5px">Nom d'utilisateur :</td>
							<td style="padding:5px"><input name="pseudolog" type="text" id="pseudolog" class="bouton" /></td>
						</tr>
						<tr>
							<td align="right" style="padding:5px">Mot de passe :</td>
							<td style="padding:5px"><input name="mdp" type="password" id="mdp" class="bouton" /></td>
						</tr>
						<tr align="center">
							<td colspan="2"  style="padding:5px">Se connecter automatiquement<input name="souvenir" type="checkbox" id="souvenir" value="auto" /></td>
						</tr>
						<tr>
							<td colspan="2" style="padding-top:10px"><div align="center">
								<input type="submit" name="Submit" value="Connexion" class="bouton" />
								<input name="Submit" type="button" class="bouton" onClick="MM_goToURL('parent','index.php?page=forgot');return document.MM_returnValue" value="J'ai oubli&eacute; mon mdp" />
							</td>
						</tr>
						<?php mysql_close();
						if(isset($_GET['erreur']))
						{
							echo '
						<tr>
							<td style="padding:15px" colspan="2" align="center">ERREUR LORS DE LA CONNEXION, REESSAYEZ A NOUVEAU<br />Il est possible que vous ayez fait une faute de frappe,<br />ou que votre compte ne soit pas valid&eacute;</td>
						</tr>
							';
						}
						?>
					</table>
				</p>
                <br />
			</form>
		</td>
	</tr>
</table>          