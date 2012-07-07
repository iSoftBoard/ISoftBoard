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

$mtime = explode(" ",microtime());
$starttime = $mtime[1] + $mtime[0];

// [3] Ajout de la page info.php, elle contient les différentes options
include('info.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $titre?></title>
<link href="./img/style.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features)
{ // v2.0
	window.open(theURL,winName,features);
}
function decision(message, url)
{
	if(confirm(message)) location.href = url;
}
//-->
// Pompé sur le site du zéro
function switch_spoiler(div2)
{
	var divs = div2.getElementsByTagName('div');
	var div3 = divs[0];
	if (div3.style.visibility == 'visible')
		div3.style.visibility = 'hidden';
	else
		div3.style.visibility = 'visible';
	return true;
}
</script>
<?php
// [13] Affichage d'un script javascript pour les formulaires de post
if(isset($_GET['page']) && ($_GET['page'] == "postadd" || $_GET['page'] == "mpsend"))echo '<SCRIPT LANGUAGE="JavaScript1.1">
function decision(message, url)
{
	if(confirm(message)) location.href = url;
}

function color(url) 
{
	// var texte  = prompt("Rentrez le texte qui apparaîtera dans la couleur : "+coultexte+".","texte");
	var smiley = url + "[/color]";
	document.news.texte.value += smiley+" ";
	document.news.texte.focus();	
}

function lien()
{
	var url  = prompt("Rentrez l\'url qui servira au lien","http://");
	var texte  = prompt("Rentrez le texte qui apparaîtera comme lien.\n(Laissez vide si vous voulez afficher l\'url pure)","");
	if(url != "" && url != "http://")
	{
		if(texte == "")
		{
			var smiley = "[url]"+url+"[/url]";	
			document.news.texte.value += smiley+" ";
			document.news.texte.focus();
		}
		else
		{
			var smiley = "[url="+url+"]"+texte+"[/url]";	
			document.news.texte.value += smiley+" ";
			document.news.texte.focus();
		}
	}
}
function img()
{
	var texte  = prompt("Rentrez l\'url de l\'image qui apparaîtera.","http://");
	
	if(texte != "" && texte != "http://")
	{
		var smiley = "[img]"+texte+"[/img]";
		document.news.texte.value += smiley+" ";
		document.news.texte.focus();
	}
}
</script>
<script language="Javascript">
var isMozilla = (navigator.userAgent.toLowerCase().indexOf(\'gecko\')!=-1) ? true : false;
var regexp = new RegExp("[\r]","gi");

function storeCaret(selec)
{
	if (isMozilla) 
	{
	// Si on est sur Mozilla

		oField = document.forms[\'news\'].elements[\'texte\'];

		objectValue = oField.value;

		deb = oField.selectionStart;
		fin = oField.selectionEnd;

		objectValueDeb = objectValue.substring( 0 , oField.selectionStart );
		objectValueFin = objectValue.substring( oField.selectionEnd , oField.textLength );
		objectSelected = objectValue.substring( oField.selectionStart ,oField.selectionEnd );

	//	alert("Debut:\'"+objectValueDeb+"\' ("+deb+")\nFin:\'"+objectValueFin+"\' ("+fin+")\n\nSelectionné:\'"+objectSelected+"\'("+(fin-deb)+")");
			
		oField.value = objectValueDeb + "[" + selec + "]" + objectSelected + "[/" + selec + "]" + objectValueFin;
		oField.selectionStart = strlen(objectValueDeb);
		oField.selectionEnd = strlen(objectValueDeb + "[" + selec + "]" + objectSelected + "[/" + selec + "]");
		oField.focus();
		oField.setSelectionRange(
			objectValueDeb.length + selec.length + 2,
			objectValueDeb.length + selec.length + 2);
	}
	else
	{
	// Si on est sur IE
		
		oField = document.forms[\'news\'].elements[\'texte\'];
		var str = document.selection.createRange().text;

		if (str.length>0)
		{
		// Si on a selectionné du texte
			var sel = document.selection.createRange();
			sel.text = "[" + selec + "]" + str + "[/" + selec + "]";
			sel.collapse();
			sel.select();
		}
		else
		{
			oField.focus(oField.caretPos);
		//	alert(oField.caretPos+"\n"+oField.value.length+"\n")
			oField.focus(oField.value.length);
			oField.caretPos = document.selection.createRange().duplicate();
			
			var bidon = "%~%";
			var orig = oField.value;
			oField.caretPos.text = bidon;
			var i = oField.value.search(bidon);
			oField.value = orig.substr(0,i) + "[" + selec + "][/" + selec + "]" + orig.substr(i, oField.value.length);
			var r = 0;
			for(n = 0; n < i; n++)
			{if(regexp.test(oField.value.substr(n,2)) == true){r++;}};
			pos = i + 2 + selec.length - r;
			//placer(document.forms[\'news\'].elements[\'texte\'], pos);
			var r = oField.createTextRange();
			r.moveStart(\'character\', pos);
			r.collapse();
			r.select();

		}
	}
}
function emoticon(text)
{
	var txtarea = document.news.texte;
	text = \' \' + text + \' \';
	if (txtarea.createTextRange && txtarea.caretPos)
	{
		var caretPos = txtarea.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == \' \' ? caretPos.text + text + \' \' : caretPos.text + text;
		txtarea.focus();
	}
	else
	{
		txtarea.value  += text;
		txtarea.focus();
	}
}//Ca vient de l editeur javascript editeurjavascript.com
</script>

';
?>
</head>
<body>
<a name="top"></a>
<!-- Header : Logo, menus -->
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="6" class="topleft"></td>
		<td height="6" colspan="2" class="top"></td>
		<td width="6" class="topright"></td>
	</tr>
	<tr class="fond">
		<td rowspan="3" align="center" valign="top" class="left"><img src="./img/design/borduredegr.gif" alt=""/></td>
		<td align="center" height="150"><a href="index.php?page=indexforum" tabindex="5"><img src="./img/menu/nom.gif" alt="Logo" /></a><br /><span class="heading">isoftsystem</span></td>
		<td align="right" valign="bottom" class="info"><span class="info">
		<?php // [14] Affichage du nombre de mp et tu pseudo
		
		if(isset($mp) && $mp > 0) echo '<img src="./img/menu/mp.gif" alt="mp" style="margin:10px"/><br />';
		
		if($pseudo != "")
		{
			echo '. Vous &ecirc;tes connect&eacute; sous le pseudo de &quot;'.htmlentities($pseudo).'&quot;<br />';
		}		
		else
		{
			echo '. Vous n\'&ecirc;tes pas connect&eacute;<br />';
		}
		if(isset($mp) && $mp > 0)
		{
			echo '.Vous avez '.$mp.' message';
			if($mp>1)
			{
				echo's';
			}
			echo' priv&eacute;';
			if($mp>1)
			{
				echo's';
			}
		}
		else
		{
			echo'. Vous n\'avez pas de message priv&eacute;';
		}
		?></span></td>
		<td rowspan="3" align="center" valign="top" class="right"><img src="./img/design/borderright.gif" alt="" /></td>
	</tr>
	<tr>
		<td id="menu" height="32" colspan="2" align="left"><img src="./img/menu/px.gif" align="absmiddle" width="22" height="22" /><a class="m" href="../index.html">Accueil</a>
		<?php 
		if(empty($pseudo))
		{
			echo '<img src="./img/menu/px.gif" align="absmiddle" width="22" height="22" /><a class="m" href="index.php?page=connexion" tabindex="10">Connexion</a><img src="./img/menu/px.gif" align="absmiddle" width="22" height="22" /><a class="m" href="index.php?page=reg" tabindex="20">S\'enregistrer</a>';
		}
		else
		{
			echo '<img src="./img/menu/px.gif" align="absmiddle" width="22" height="22" /><a class="m" href="index.php?page=lgout" tabindex="10">deconnexion</a><img src="./img/menu/px.gif" align="absmiddle" width="22" height="22" /><a class="m" href="index.php?page=mp" tabindex="20">Messagerie</a>';
		}
		?><img src="./img/menu/px.gif" align="absmiddle" width="22" height="22" /><a class="m" href="<?php if(!empty($pseudo)) { echo'index.php?page=profil'; } else { echo'index.php?page=connexion'; }?>" tabindex="30">Profil</a><img src="./img/menu/px.gif" align="absmiddle" width="22" height="22" /><a class="m" href="index.php?page=membre" tabindex="40">Membres</a><img src="./img/menu/px.gif" align="absmiddle" width="22" height="22" /><a class="m" href="index.php?page=groupe" tabindex="50">Groupes</a><img src="./img/menu/px.gif" align="absmiddle" width="22" height="22" /><a class="m" href="index.php?page=search" tabindex="60">Recherche</a><img src="./img/menu/px.gif" align="absmiddle" width="22" height="22" /><a class="m" href="index.php?page=faq" tabindex="70">faq</a>
		</td>
	</tr>
	<tr>
		<!-- Forums -->
		<td colspan="2" bgcolor="#FFFFFF">
			<?php 
				if(isset($_GET['page']))
				{
					$page = $_GET['page'].'.php';
				}
				else
				{
					$page="indexforum.php";
				}
				if(!in_array($page,$inclauto)) $page="erreur.php"; 
				if($lockforum) $page = "lock.php";
				include_once('includes/'.htmlentities($page));
			?>
			<!-- Qui est en ligne ? + Footer -->
			<table width="100%" class="texte_base_gras" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="29" class="titreforumend" colspan="2" style="padding-left:8px"><img src="img/design/icot_n.gif" align="absmiddle" width="48" height="8" />Qui est en ligne ?</td>
				</tr>
				<tr class="texte_base_gras">
					<td width="60" align="center" class="cadre1_droite"><img src="./img/footer/whois.jpg" alt="Qui est en ligne ?" /></td>
					<td class="cadre1" style="padding-left:10px">
						<p>Il y a en tout <?php echo ($i+$affnon); ?> utilisateur<?php if(($i+$affnon)>1){ echo's'; } ?> en ligne : <? echo $i; ?> visible<?php if($i>1){ echo's'; } ?> et <? echo $affnon; ?> invisible<?php if($affnon>1){ echo's'; } ?>(dans les 5 derni&egrave;res minutes)<br />
						Code de couleur : <span class="admin">[ Administrateur ]</span> <span class="modo">[ Mod&eacute;rateur ]</span> <span class="chef">[ Chef de groupe ]</span><br />
						Utilisateur<?php if($i>1){ echo's'; } ?> connect&eacute;<?php if($i>1){ echo's'; } ?> :
						<?php
						for($j=0; $j<$i;$j++)
						{
				  			if($listerang[$j] == "2")
							{
								echo '<a href="index.php?page=affprofil&amp;id='.$listeid[$j].'"><span class="admin">'.htmlentities($listepersonne[$j]).'</span></a>';
								if($j!=$i-1)
								{
									echo ', ';
								}
							}
							elseif($listerang[$j] == "1")
							{
								echo '<a href="index.php?page=affprofil&amp;id='.$listeid[$j].'"><span class="modo">'.htmlentities($listepersonne[$j]).'</span></a>';
								if($j!=$i-1)
								{
									echo ', ';
								}
							}
							elseif($listerang[$j] == "3")
							{
								echo '<a href="index.php?page=affprofil&amp;id='.$listeid[$j].'"><span class="chef">'.htmlentities($listepersonne[$j]).'</span></a>';
								if($j!=$i-1)
								{
									echo ', ';
								}
							}
							else
							{
								echo '<a href="index.php?page=affprofil&amp;id='.$listeid[$j].'">'.htmlentities($listepersonne[$j]).'</a>';
								if($j!=$i-1)
								{
									echo ', ';
								}
							}
						}
						?>
						</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="bottomleft"></td>
		<td colspan="2" class="bottom"></td>
		<td class="bottomright"></td>
	</tr>
</table>
<!-- /!\ Rappel : dans les conditions d'utilisation GNU, il est interdit de modifier le copyright du forum. /!\ -->
<p align="center" class="footer"><a href="http://www.isoftsystem.com"><span class="footer">[ Copyright iSoftSystem v0.1.1 ]</span></a> , [ Teamworks ] , [ <?php echo $requse; ?> Requ&ecirc;te<?php if($requse > 1) echo 's'; ?> mysql utilisée<?php if($requse > 1) echo 's'; ?> ] , [ GZIP <?php if($gzip) echo 'activé'; else echo 'désactivé'; ?> ] <?php
$mtime = explode(" ",microtime());
$endtime = $mtime[1] + $mtime[0];
echo ' , [ Page générée en ',number_format($endtime-$starttime,4,',',''),'s ]';
if($rang == 2) echo '<a href="admin/"><span class="footer"> , [ Administration ]</span></a>'; ?><br /><br /><a href="http://www.myheberg.com/" target="new"><img src="img/promos/myheberg_80_15.gif" align="absmiddle" width="80" height="15" title="hébergé par MyHeberg" alt="hébergé par MyHeberg" /></a><br /><br /></p>
</body>
</html>
