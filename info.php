<?php

define('IN_SOFTBB',true);

session_start();
if(isset($_SESSION['idlog']) && $_SESSION['ip_anti_vol'] != $_SERVER['REMOTE_ADDR']) $_SESSION = array();

// addslashes et stripslashes pour les magic_quotes
$quotes_gpc = get_magic_quotes_gpc();

ini_set('magic_quotes_runtime', 0);

function add_gpc ($chaine) {

	global $quotes_gpc;
	
	if($quotes_gpc) return $chaine;
	else return addslashes($chaine);

}

function strip_gpc ($chaine) {

	global $quotes_gpc;
	
	if($quotes_gpc) return stripslashes($chaine);
	else return $chaine;

}

// Données utiles
include_once('info_bdd.php');
include_once('info_emote.php');
include_once('info_options.php');
include_once('info_options_rangs.php');


// Fonction BBcode, merci à Skybattle et à Loufoque
$bbcode = array (	'£\[b\](.+)\[/b\]£isU' ,
					'£\[i\](.+)\[/i\]£isU' ,
					'£\[u\](.+)\[/u\]£isU' ,
					'£\[s\](.+)\[/s\]£isU' ,
					'£\[color=(red|darkred|blue|darkblue|green|darkgreen|yellow|gold|black|white|grey|darkgrey|orange|darkorange|brown|olive|cyan|indigo|purple|violet|#[\w\d]{6};)\](.+)\[/color\]£isU' ,
					'£\[color=#([a-zA-Z0-9]{6}|[a-zA-Z0-9]{3})\](.+)\[/color\]£isU' ,
					'£\[size=(xx-small|x-small|small|medium|large|x-large|xx-large)\](.+)\[/size\]£isU' ,
					'/\[size=(1|2|3|4|5|6|7|8|9|10|11|12|13|14|15|16|17|18|19|20|21|22|23|24|25|26|27|28|29|30|)\](.*?)\[\/size\]/si',
					"/\[img\](.+?)\[\/img\]/si",
					'£\[url=(?:http://)?([\w\d_/?&%#=~\.;-]+)\](.+)\[/url\]£iU' ,
					'£\[url=(?:http://)?([\w\d_/?&%#=~\.;-]+:[0-9]+)\](.+)\[/url\]£iU' ,
					'£\[url\](?:http://)?([\w\d_/?&%#=~\.;-]+)\[/url\]£iU' ,
					'£\[url\](?:http://)?([\w\d_/?&%#=~\.;-]+:[0-9]+)\[/url\]£iU' ,
					'£(?<![\w\d_/?&%#=~\.;->"])(?:(http://)|(w{3}\d?\.))([\w\d_/?&%#=~\.;-]+)£i' ,
					'£\[url=(ftp://[\w\d_/?&%#=~\.;-]+)\](.+)\[/url\]£iU' ,
					'£\[url\](ftp://[\w\d_/?&%#=~\.;-]+)\[/url\]£iU' ,
					'£(?<![\w\d_/?&%#=~\.;->"])(ftp://[\w\d_/?&%#=~\.;-]+)£i' ,
					'£\[email\]([\w\d_\.-]+@[\w\d_\.-]+\.[\w\d]{2,5})\[/email\]£iU' ,
					'£\[email=([\w\d_\.-]+@[\w\d_\.-]+\.[\w\d]{2,5})\](.+)\[/email\]£iU',
					'£\[spoil\](.+)\[/spoil\]£isU' ,
					'£\[spoil=(.+)\](.+)\[/spoil\]£isU' ,);
			
$xhtml = array (	'<strong>$1</strong>' ,
					'<em>$1</em>' ,
					'<ins>$1</ins>' ,
					'<s>$1</s>' ,
					'<span style="color: $1">$2</span>' ,
					'<span style="color: #$1">$2</span>' ,
					'<span style="font-size: $1">$2</span>' ,
					'<span style="font-size: $1px">$2</span>',
					'<img src="$1" alt="Image" />' ,
					'<a href="http://$1">$2</a>' ,
					'<a href="http://$1">$2</a>' ,
					'<a href="http://$1">http://$1</a>' ,
					'<a href="http://$1">http://$1</a>' ,
					'<a href="http://$2$3">$1$2$3</a>' ,
					'<a href="$1">$2</a>' ,
					'<a href="$1">$1</a>' ,
					'<a href="$1">$1</a>' ,
					'<a href="mailto:$1">$1</a>' ,
					'<a href="mailto:$1">$2</a>',
					'<span class="spoilertexte">Texte caché : cliquez sur le cadre pour l\'afficher</span><div class="spoiler" onclick="switch_spoiler(this)"><div style="visibility: hidden;" class="spoiler3">$1</div></div>',
					'<h>$1</h><span class="spoilertexte">Texte caché : cliquez sur le cadre pour l\'afficher</span><div class="spoiler" onclick="switch_spoiler(this)"><div style="visibility: hidden;" class="spoiler3">$2</div></div>');

function strbbcode1 ($matches) {
	
	global $bbcode , $xhtml , $emoticonc , $emoticonv ,$emoticonnb;
	for($em=0;$em<$emoticonnb;$em++)	$matches[1] = str_replace($emoticonc[$em],'<img src="'.$emoticonv[$em].'" border="0" alt="" />',$matches[1]);
	return preg_replace ($bbcode , $xhtml , $matches[1]).'<h6>Code :</h6><code>';

}
function strbbcode2 ($matches) {	

	global $bbcode , $xhtml , $emoticonc , $emoticonv ,$emoticonnb;
	for($em=0;$em<$emoticonnb;$em++)	$matches[1] = str_replace($emoticonc[$em],'<img src="'.$emoticonv[$em].'" border="0" alt="" />',$matches[1]);
	return '</code>'.preg_replace ($bbcode , $xhtml , $matches[1]).'<h6>Code :</h6><code>';

}
function strbbcode3 ($matches) {
	
	global $bbcode , $xhtml , $emoticonc , $emoticonv ,$emoticonnb;
	for($em=0;$em<$emoticonnb;$em++)	$matches[1] = str_replace($emoticonc[$em],'<img src="'.$emoticonv[$em].'" border="0" alt="" />',$matches[1]);
	return '</code>'.preg_replace ($bbcode , $xhtml , $matches[1]);
	
}
function decodequote ($matches) {

	$matches[1] = str_replace('[/quote','[@/quote',$matches[1]);
	return '[code]'.str_replace('[quote','[@quote',$matches[1]).'[/code]';
	
}
function recodequote ($matches) {

	$matches[1] = str_replace('[@/quote','[/quote',$matches[1]);
	return '[code]'.str_replace('[@quote','[quote',$matches[1]).'[/code]';
	
}

function quote($matches) {

	if( preg_match('£\[quote(?:="(?:[^"]+)")?\](.+)\[/quote\]£is', $matches[2])) return '<blockquote><h6>'. ((!empty($matches[1])) ? $matches[1].' a dit :' : 'Citation :').'</h6>'.preg_replace_callback('£\[quote(?:="([^"]+)")?\]((?:(?:(?!\[/quote]).)*?(?R).*?)+|.+?)\[/quote\]£is', 'quote', $matches[2]).'</blockquote>';
    else return '<blockquote><h6>'. ((!empty($matches[1])) ? $matches[1].' a dit :' : 'Citation :') . '</h6>'.$matches[2].'</blockquote>';
	
}

function bbcode ($chaine) {	

	if( (substr_count($chaine,'[code]') > 0 &&  substr_count($chaine,'[/code]')) > 0) {
		$chaine = preg_replace_callback ('£\[code\](.*)\[/code\]£isU' , 'decodequote' , $chaine);		
		//Fonction chasseuse de QUOTE imbriquées par LOUFOQUE															
		$chaine = preg_replace_callback('£\[quote(?:="([^"]+)")?\]((?:(?:(?!\[/quote]).)*?(?R).*?)+|.+?)\[/quote\]£is', 'quote', $chaine);
		$chaine = preg_replace_callback ('£\[code\](.*)\[/code\]£isU' , 'recodequote' , $chaine);										
		$chaine = preg_replace_callback ('£^(.*)\[code\]£isU' , 'strbbcode1' , $chaine);																	
		$chaine = preg_replace_callback ('£\[/code\](.*)\[code\]£isU' , 'strbbcode2' , $chaine);																	
		$chaine = preg_replace_callback ('£\[/code\](.*)$£isU' , 'strbbcode3' , $chaine);					
	}
	
	else {
	
		global $bbcode , $xhtml , $emoticonc , $emoticonv ,$emoticonnb;
		$chaine = preg_replace_callback('£\[quote(?:="([^"]+)")?\]((?:(?:(?!\[/quote]).)*?(?R).*?)+|.+?)\[/quote\]£is', 'quote', $chaine);
		for($em=0;$em<$emoticonnb;$em++) $chaine = str_replace($emoticonc[$em],'<img src="'.$emoticonv[$em].'" border="0" alt="" />',$chaine);
		$chaine = preg_replace ($bbcode , $xhtml , $chaine);
	
	}		

return ($chaine);

} 

function sit ($chaine)
{

	$chaine = str_replace('<','&lt;',$chaine);							
	$chaine = str_replace('>','&gt;',$chaine);			
	$chaine = str_replace("&amp;",'&',$chaine);
	return $chaine;
	
}

function datefct ($temps,$gmt) 
{		
	$temps += ($gmt*3600)-date("Z");
	$date = date("d",$temps);
	
	$tablebbcoder = array(
	'01' => 'Jan',
	'02' => 'Fév',
	'03' => 'Mars',
	'04' => 'Avril',
	'05' => 'Mai',
	'06' => 'Juin',
	'07' => 'Juil',
	'08' => 'Ao&ucirc;t',
	'09' => 'Sept',
	'10' => 'Oct',
	'11' => 'Nov',
	'12' => 'Déc');
	
	$date .=  ' '.$tablebbcoder[date("m",$temps)].' ';

	$date .= date("Y",$temps); 
	$date .= ' '.date("H:i",$temps);
	return ($date); 
} 

// Ne surtout pas toucher, sauf si vous etes vraiment sure de ce que vous faites, ici, on verifie qu'on peut includer la page
// Ca n'a l'air de rien, mais ces pages ne s'auto connecte pas a la bdd, risque de fouteurs de merde faisant des requetes
// Et puis on pourrait afficher les valeurs des mdp et user du compte de info.php, encore plus de risque de fouteurs de merde

$inclauto = array('delsonde.php','voteadd.php','resynchok.php','lgout.php','indexforum.php','delvalid2.php','type.php','mp.php','profil.php','membre.php','affprofil.php','affgroupe.php','connexion.php','erreur.php','erreurgroup.php','faq.php','forgot.php','forum.php','groupe.php','mpread.php','mpseek.php','mpsend.php','post.php','postadd.php','profil.php','profsave.php','reg.php','regok2.php','lockforum.php','search.php','delvalid.php');

function professordekodor($ip) {

	$ipe = explode('$',$ip);$j = count($ipe);$ip = "";for($i=0;$i<$j;$i++){ $ip .= hexdec($ipe[$i]);if($i != $j-1) $ip .='.';}return ' '.$ip;

}

if(!isset($host)) header('Location: install/');
if($gzip) ob_start("ob_gzhandler");
$requse = 0;

 

$pseudo = "";
$rang = -1;
$idmembre = -1;
$gmt = 0;
$he = 0;

if(!isset($_SESSION['lastvisit'])) $_SESSION['lastvisit']=time();

$tmp = time();
$tmpmoins = $tmp-300;
$listepersonne = array();
$listerang = array();
$listeid= array();
$i = 0;
$affnon = 0;
$fait = false;	

$db = mysql_connect($host,$user,$mdpbdd)  or die('<p>/!\ Impossible de se connecter au serveur mysql, vérifiez les options de connexion à la base de donnée</p>');
mysql_select_db($bdd,$db)  or die('<p>/!\ Impossible de se connecter à la base de donnée, vérifiez qu\'elle existe</p>');

if(isset($_COOKIE) && !empty($_COOKIE['idlog']) && !isset($_SESSION['idlog']) && empty($_SESSION['idlog']))
{ 

$sql = 'SELECT id,mdp,temps,pseudo FROM '.$prefixtable.'membres WHERE id = "'.intval($_COOKIE['idlog']).'" AND mdp="'.add_gpc($_COOKIE['mdp']).'" AND valid = "1"';

$req = mysql_query($sql); $requse++;

	if(mysql_num_rows($req) == 1) 
	{
		$data = mysql_fetch_assoc($req);
		$_SESSION['pseudo'] = $data['pseudo'];
		$_SESSION['idlog'] = $data['id'];
		$_SESSION['lastvisit'] = $data['temps'];
		$_SESSION['ip_anti_vol'] = $_SERVER['REMOTE_ADDR'];
	}
	else
	{
		setcookie("idlog","",time()-(365*24*3600));
		setcookie("mdp","",time()-(365*24*3600));
	}

}

// [8] Choix de la requete, longue ou courte si connecté ou pas
if(isset($_SESSION['idlog']) && !empty($_SESSION['idlog'])) $sql = 'SELECT  a.afflist,a.pseudo,a.rang,b.mp,a.id,b.valid,b.tempspost,b.gmt,b.he FROM '.$prefixtable.'membres AS a LEFT JOIN '.$prefixtable.'membres AS b ON b.id = "'.intval($_SESSION['idlog']).'"  WHERE a.temps > "'.$tmpmoins.'" AND a.co = "1" OR a.id = "'.intval($_SESSION['idlog']).'" ORDER BY pseudo';
else $sql = 'SELECT afflist,pseudo,rang,id FROM '.$prefixtable.'membres WHERE temps > "'.$tmpmoins.'" AND co = "1" ORDER BY pseudo ';

$req52 = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); $requse++;

// [9] Scanage des entrées,
while ($data52 = mysql_fetch_assoc($req52)) 
{
	// [9.1] Recherche des entrées pour le membre
	if (isset($_SESSION['idlog']) && $data52['id'] == $_SESSION['idlog'])
	{
		$rang = $data52['rang'];
		$mp = $data52['mp'];
		$idmembre = $data52['id'];
		$tempspostlast = $data52['tempspost'];
		$gmt = $data52['gmt'];
		$he = $data52['he'];
		$pseudo = $data52['pseudo']; 
	} 
	// [9.2] Mise en cache des membres logués
	if($data52['afflist'] == 1 && $afflistdelauto) $affnon++;
	else
	{
		$listepersonne [$i] = $data52['pseudo'];
		$listerang[$i] = $data52['rang'];
		$listeid[$i] = $data52['id'];
		$i++;
		}
	}
	

// [11] Update du temps de dernière visite (en table)
if($pseudo != "" )
{
	$sql2 = 'UPDATE '.$prefixtable.'membres SET temps = "'.$tmp.'" WHERE id = "'.intval($_SESSION['idlog']).'"';
	$req2 = mysql_query($sql2);
	$requse++;  
}

// [12] Ajout de 1, si on passe à l'heure d'été et si c'est activé
if($he == 1) $gmt += date("I");

$arr_page_titre = array(
		'indexforum' => 'Index',
		'affprofil' => 'Profil d\'un membre',
		'delsonde' => 'Suppression d\'un sondage',
		'voteadd' => 'Vote',
		'resynchok' => 'resynchronisation effectuée',
		'lgout' => 'Déconnexion',
		'delvalid2' => 'Réponse supprimée',
		'type' => 'Changement du type de message',
		'mp' => 'Messagerie privée',
		'affgroupe' => 'Groupes',
		'connexion' => 'Connexion',
		'erreur' => 'Erreur',
		'erreurgroup' => 'Erreur',
		'faq' => 'Faq (questions/réponses)',
		'forgot' => 'Obtention d\'un nouveau mot de passe',
		'groupe' => 'Groupes',
		'mpread' => 'Lecture d\'un message privé',
		'mpseek' => 'Recherche d\'un membre',
		'mpsend' => 'Envoi d\'un message privé',
		'postadd' => 'ajout d\'un sujet/réponse',
		'profil' => 'Profil',
		'profsave' => 'Profil enregistré',
		'reg' => 'Enregistrement',
		'regok2' => 'Enregistrement validé',
		'lockforum' => 'Verrouillage/Déverouillage',
		'search' => 'Recherche',
		'delvalid' => 'Suppression',
		'membre' => 'Liste des membres',

		);
		
		if(!isset($_GET['page']) || !isset($arr_page_titre[$_GET['page']]) &&  $_GET['page'] != 'forum' && $_GET['page'] != 'post') $titre =  $nomduforum;
		elseif($_GET['page'] == 'forum') {
		
			$sql = 'SELECT groupe,nom,m,mg,v,nbsujet,temps FROM '.$prefixtable.'forum WHERE id = '.intval($_GET['idf']).' AND fatt != 0';
			$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
			$requse++;
			$data3 = mysql_fetch_assoc($req);
			
			$titre = stripslashes(htmlentities($data3['nom'])).' - '.$nomduforum;
		
		}
		elseif($_GET['page'] == 'post') {
		
			$sql = 'SELECT p.idsa,p.tmpdernierpost,p.idsfa,p.sondage,p.titre, p.`lock`,p.tmppost,p.nbr,f.groupe,f.nom,f.m,f.v,f.mg FROM '.$prefixtable.'post AS p LEFT JOIN  '.$prefixtable.'forum AS f ON f.id = p.idsfa WHERE id2 = '.intval($_GET['ids']);
			$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error()); $requse++;
			
			// [3] Expulsé si pas de post pour GET['ids']
			if(mysql_num_rows($req) == 0)
			{
				include('./includes/erreur.php');
				exit('3');
			}
			
			// [4] Mise en cache de quelques données relatives à GET['ids'] 
			$data3 = mysql_fetch_assoc($req);
			
			$titre = stripslashes(htmlentities($data3['titre'])).' - '.$nomduforum;

		
		}
		else $titre = $arr_page_titre[$_GET['page']].' - '.$nomduforum;
		
		(isset($_GET['page'])) ? $page = $_GET['page'] : $page = 'indexforum';
		
if($cache_forum && $page == 'indexforum') {

	if($rang == 2 || $rang == 1) $where = '';
	elseif(!empty($_SESSION['idlog'])) {
	
		
		$sql = 'SELECT f.position,f.nbsf,f.fatt,g.stat FROM '.$prefixtable.'forum AS f LEFT JOIN '.$prefixtable.'groupemembre AS g ON f.groupe = g.idg AND g.idm = '.intval($_SESSION['idlog']).'  WHERE fatt = 0 OR (f.groupe = "-4" AND f.m = 0) OR (f.groupe > 0 AND f.m = 0 AND g.stat = "") OR  (f.groupe > 0 AND f.mg = 0 AND g.stat < 1) OR groupe = "-3" ORDER BY position';
		$req = mysql_query($sql); $requse++;
		
		$where = ' WHERE position != ';
		$pos_max = 0;
		
			while($data = mysql_fetch_assoc($req)) { 
			
				$forum_ou_cat[$data['position']] = $data['nbsf'];
				$forum_ou_fatt[$data['position']] = $data['fatt']; 
				if($data['position'] > $pos_max) $pos_max = $data['position'];
			
			}
		
		for($forum_ou_cat_i=1;$forum_ou_cat_i<=$pos_max;$forum_ou_cat_i++) {
		
			if(isset($forum_ou_cat[$forum_ou_cat_i]) && $forum_ou_fatt[$forum_ou_cat_i] == 0) {
			
				$future_aff = false;
				for($forum_ou_cat_j=0;$forum_ou_cat_j<$forum_ou_cat[$forum_ou_cat_i];$forum_ou_cat_j++) {
				
					if(isset($forum_ou_cat[$forum_ou_cat_i+$forum_ou_cat_j+1])) $where .= ($forum_ou_cat_i+$forum_ou_cat_j+1).' AND position != ';
					else $future_aff = true; 
				
				} 
				if(!$future_aff) $where .= ($forum_ou_cat_i).' AND position != ';
				$forum_ou_cat_i += $forum_ou_cat[$forum_ou_cat_i];
				
			}
		
		}
		
		$where .= ' 0 ';

	
	
	}
	else {
		
		$sql = 'SELECT position,nbsf,fatt FROM '.$prefixtable.'forum WHERE fatt = 0 OR (groupe = "-4" AND v = 0) OR (groupe > 0 AND v = 0) OR groupe = "-3" OR groupe = "-2" ORDER BY position';
		$req = mysql_query($sql); $requse++;
		
		$where = ' WHERE position != ';
		$pos_max = 0;
		
			while($data = mysql_fetch_assoc($req)) { 
			
				$forum_ou_cat[$data['position']] = $data['nbsf'];
				$forum_ou_fatt[$data['position']] = $data['fatt']; 
				if($data['position'] > $pos_max) $pos_max = $data['position'];
			
			}
		
		for($forum_ou_cat_i=1;$forum_ou_cat_i<=$pos_max;$forum_ou_cat_i++) {
		
			if(isset($forum_ou_cat[$forum_ou_cat_i]) && $forum_ou_fatt[$forum_ou_cat_i] == 0) {
			
				$future_aff = false;
				for($forum_ou_cat_j=0;$forum_ou_cat_j<$forum_ou_cat[$forum_ou_cat_i];$forum_ou_cat_j++) {
				
					if(isset($forum_ou_cat[$forum_ou_cat_i+$forum_ou_cat_j+1])) $where .= ($forum_ou_cat_i+$forum_ou_cat_j+1).' AND position != ';
					else $future_aff = true; 
				
				} 
				if(!$future_aff) $where .= ($forum_ou_cat_i).' AND position != ';
				$forum_ou_cat_i += $forum_ou_cat[$forum_ou_cat_i];
				
			}
		
		}
		
		$where .= ' 0 ';

	
	}
}


?>