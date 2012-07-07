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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Ajouter un smilies</title>
<link href="img/style.css" rel="stylesheet" type="text/css">
<script>function emoticon(text)
{
	var txtarea = document.news.texte;
	text = ' ' + text + ' ';
	if (txtarea.createTextRange && txtarea.caretPos)
	{
		var caretPos = txtarea.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? caretPos.text + text + ' ' : caretPos.text + text;
		txtarea.focus();
	}
	else
	{
		txtarea.value  += text;
		txtarea.focus();
	}
}
</script>
<script language="javascript" type="text/javascript">
<!--
function emoticon(text)
{
	text = ' ' + text + ' ';
	if (opener.document.news.texte.createTextRange && opener.document.news.texte.caretPos)
	{
		var caretPos = opener.document.news.texte.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
		opener.document.formsnews.texte.message.focus();
	}
	else
	{
		opener.document.news.texte.value  += text;
		opener.document.news.texte.focus();
	}
}
//-->
</script>
</head>
<body style="margin:0">
<table class="texte_base_gras" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height="29" class="titreforum" colspan="2" style="padding-left:5px">Ajouter un smilies </td>
	</tr>
	<tr>
		<td width="50%" align="center" class="cadre_clair">Code</td>
		<td width="50%" height="30" align="center" class="cadre_clair">Image</td>
	</tr> 
	<?php
	include('info_bdd.php');
	include('info_emote.php');
	
	for($i=0;$i<$emoticonnb;$i++)
	{
	echo'
	<tr>
		<td align="center" class="cadre_fonce"><a href="javascript:emoticon(\''.$emoticonc[$i].'\')">'.htmlentities($emoticonc[$i]).'</a></td>
		<td height="30" align="center" class="cadre_clair" style="padding:3px"><a href="javascript:emoticon(\''.$emoticonc[$i].'\')"><img src="'.$emoticonv[$i].'" alt="Emoticone" /></a></td>
	</tr>
	';
	}
?>
</table>
</body>
</html>