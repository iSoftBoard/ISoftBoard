<?php
/***************************************************************************
 *
 *   SoftBB - Forum de discussion 
 *   Version : 0.1
 *
 *   copyright            : (C) 2005 J�r�my Dombier [Belgium]
 *   email                : satapi@gmail.com
 *   site-web             : http://softbb.be/
 *
 *   Ce programme est un logiciel libre ; vous pouvez le redistribuer et/ou 
 *   le modifier au titre des clauses de la Licence Publique G�n�rale GNU, 
 *   telle que publi�e par la Free Software Foundation ; soit la version 2 de 
 *   la Licence, ou (� votre discr�tion) une version ult�rieure quelconque. 
 *   Ce programme est distribu� dans l'espoir qu'il sera utile, mais 
 *   SANS AUCUNE GARANTIE ; sans m�me une garantie implicite de COMMERCIABILITE 
 *   ou DE CONFORMITE A UNE UTILISATION PARTICULIERE. Voir la Licence Publique 
 *   G�n�rale GNU pour plus de d�tails. Vous devriez avoir re�u un exemplaire 
 *   de la Licence Publique G�n�rale GNU avec ce programme ; si ce n'est pas le 
 *   cas, �crivez � la Free Software Foundation Inc., 
 *   51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 ***************************************************************************/
 
if(!defined('IN_SOFTBB')) exit('Not in SoftBB');
mysql_close();
if(!isset($_SESSION['pseudo']) || empty($_SESSION['pseudo'])) exit();
if(isset($_GET['bad']))
{ 
	echo'
	<table width="100%" class="texte_base_gras" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td height="29" class="titreforumend" style="padding-left:8px">Recherche d\'un membre pour lui envoyer un message priv&eacute;</td>
		</tr>
			<td class="cadre1_bas" style="padding:10px">Erreur, ce compte n\'existe pas. -> <a href="index.php?page=mpseek">Cliquez ici pour reessayer</a></td>
		</tr>
	</table>
	';
}
else
{
	echo'
	<form name="form1" method="post" action="mpseekvalid.php">
	<table width="100%" class="texte_base_gras" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td height="29" class="titreforumend" style="padding-left:8px">Recherche d\'un membre pour lui envoyer un message priv&eacute;</td>
		</tr>
			<td class="cadre1_bas" style="padding:10px"><label for="utilisateur">Nom d\'utilisateur du membre &agrave; rechercher :</label> <input id="utilisateur" name="pseudo" type="text" class="bouton" size="32" maxlength="64" /><input type="submit" name="Submit" value="Commencer la recherche" class="bouton" /></td>
		</tr>
	</table>
	</form>
';}
?>
