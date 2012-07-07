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
		<td height="29" class="titreforumend">Faq</td>
	</tr>
	<tr>
		<td class="cadre1_bas" style="padding:30px">
			<h2>Connexion &amp; Inscription :</h2>
			<ul>
				<li class="q">Pourquoi je ne peux pas poster ?</li>
				<li>Vous devez vous inscrire pour poster.</li>
				<li class="q">J'ai oubli&eacute; mon mot de passe ! Comment faire ?</li>
				<li>Cliquez sur le lien en dessous du formulaire de connexion pour r&eacute;cup&eacute;rer votre mot de passe.</li>
			</ul>
			
			<br />
			
			<h2>Pr&eacute;f&eacute;rences et profils</h2>
			<ul>
				<li class="q">Mon heure n'est pas exacte !</li>
				<li>Allez dans votre profil et choisissez votre r&eacute;seau horaire (France = GMT +1 )</li>
				<li class="q">Comment faire pour avoir une image associ&eacute;e à mon pseudo ?</li>
				<li>Allez dans votre profil et dans le bas choisissez un avatar.</li>
				<li class="q">Comment avoir une signature ?</li>
				<li>Dans votre profil vous pouvez en cr&eacute;er une. N'oubliez pas de s&eacute;lectionner toujours afficher ma signature.</li>
			</ul>
			
			<br />
			
			<h2>Publication</h2>
			<ul>
				<li class="q">Comment r&eacute;pondre ou poster un nouveau sujet ?</li>
				<li>Cliquez sur r&eacute;pondre ou nouveau &agrave; l'endroit o&ugrave; vous le voulez. Ce sont deux boutons.</li>
				<li class="q">Comment faire un sondage?</li>
				<li>En dessous de votre message dans un nouveau sujet, il y a toutes les options pour faire un sondage.</li>
				<li class="q">Comment &eacute;diter une r&eacute;ponse ou un sujet ?</li>
				<li>Il faut que vous cliquiez en haut &agrave; droite de votre message sur &eacute;diter (Vous ne pouvez &eacute;diter que vos messages)</li>
			</ul>
			
			<br />
			
			<h2>Groupes et Mod&eacute;ration</h2>
			<ul>
				<li class="q">Qui sont les Administrateurs ?</li>
				<li>Les Administrateurs sont des personnes qui poss&egrave;dent le plus haut niveau de contr&ocirc;le sur tout le forum. Ces personnes peuvent contr&ocirc;ler toutes les facettes du forum; ceci inclut le r&eacute;glage des permissions, le bannissement d'utilisateurs, la cr&eacute;ation de groupes d'utilisateurs ou de mod&eacute;rateurs, etc. Ils ont &eacute;galement tous les pouvoirs de mod&eacute;ration sur tous les forums.</li>
				<li class="q">Qui sont les Mod&eacute;rateurs?</li>
				<li>Les Mod&eacute;rateurs sont des personnes (ou groupes de personnes) dont le but est de veiller au respect du r&egrave;glement et au bon fonctionnement du forum tous les jours. Ils ont le pouvoir d'&eacute;diter ou de supprimer les messages et de verrouiller, d&eacute;verrouiller, supprimer  les sujets de discussions dans le forum o&ugrave; ils mod&egrave;rent. G&eacute;n&eacute;ralement, les mod&eacute;rateurs sont l&agrave; pour &eacute;viter aux gens de faire du hors-sujet ou de poster des messages ne respectant pas le r&egrave;glement.</li>
			</ul>

			<br />

			<h2>Messagerie</h2>
			<ul>
				<li class="q">Je re&ccedil;ois plein de messages non-d&eacute;sir&eacute;s</li>
				<li>Signalez le par MP a un mod&eacute;rateur.</li>
			</ul>
		</td>
	</tr>
</table>          