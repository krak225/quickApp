<?php

function securisedData($data){
	//return $data = mysql_real_escape_string(htmlspecialchars(addslashes($data)));
	$data = htmlspecialchars(addslashes($data));
	return $data;
}

function getTotalLigne($table){
	$n = 0;
	$sql='select * from '.$table;
	
	$req=mysql_query($sql) or die(mysql_error());
	$n=mysql_num_rows($req);
	
	return $n;
}
		

function getMonthName($date){
	$mois = substr($date,5,7);
	$n = intval($mois) - 1;
	$tab_mois=array("Janvier","Février","Mars","Avril","Mai","juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
	return $tab_mois[$n];
	// return $n;
}

function debug($chaine){
	print '<pre>';
	print_r($chaine);
	print '</pre>';
}

function afficher($chaine){
	print(utf8_decode($chaine));
}

function kraksecurise($chaine){
	$chaine=htmlspecialchars(trim($chaine));
	return $chaine;
}

function ecrit($chaine){
	print stripslashes($chaine);
}


function AfficherRight(){
	return true;
}
////////////////////////////////////////////////////

function EstAdministrateur($tableAdmin,$loginField,$login,$passField,$pass)
{
	$sql='SELECT * FROM '.$tableAdmin.' WHERE '.$loginField.'="'.$login.'" AND '.$passField.'="'.$pass.'"';
	$req=mysql_query($sql);$n=mysql_num_rows($req);
	
	if($n==1){	return true; }else{ return false; }
}////////////////////////////////////////////////////

function estMembre($tableAdmin,$loginField,$login,$passField,$pass)
{
	$sql='SELECT * FROM '.$tableAdmin.' WHERE '.$loginField.'="'.$login.'" AND '.$passField.'="'.$pass.'"';
	$req=mysql_query($sql);$n=mysql_num_rows($req);
	
	if($n==1){	return true; }else{ return false; }
}
/////////////////////////////////////////////////
function getData($table,$field,$refField,$refVal){
	$sql='select * from '.$table.' where `'.$refField.'`="'.$refVal.'"';
	$req=mysql_query($sql) or die(mysql_error());$d=mysql_fetch_object($req);
	$valueoffield=$d->$field;
	return $valueoffield;
}

/////////////////////////////////////////////////
function krakDBGet($table,$field,$refField,$refVal){
	$sql='select * from '.$table.' where `'.$refField.'`="'.$refVal.'"';
	$req=mysql_query($sql) or die(mysql_error());$d=mysql_fetch_object($req);
	$valueoffield=$d->code;
	return $valueoffield;
}

/////////////////////////////////// CRIPTAGE DE DONNEES ///////////////////////////////////////////
	function krakcript($chaine)
	{
		$alphabet=Array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
		for($i=0;$i<26;$i++)
		{
			$caractere=$alphabet[$i];
			$numero=$i+1;
			$krak="scriptlanguage=javascript je suis armand kouassi, electronicien et informaticien programmeur, c'est mon ami backary qui m'aide souvent, dieu n'aime pas le hacking";
			$krak=md5(sha1(md5($krak)));
			$chaine=str_replace($caractere,$numero.$krak,$chaine);
			$chaine=sha1(md5(sha1($chaine)));
		}
		return $chaine;
	}
///////////////////////////////////// affiche la liste des pays ////////////////////////////////////////


////////////quelques fonctions/////////////////
	function krakDate(){
		$semaine=array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","samedi");
		$mois=array("Janvier","Février","Mars","Avril","Mai","juin","Juillet","Ao?t","Septembre","Octobre","Novembre","Décembre");
		$krakDate=getdate();
		$numero_mois =date('m')-1;
		$datejour =date('j');
		$numero_semaine=date('w');
		$annee =date('Y');	
		$heure=date("H:i:s");
		$strjour=$semaine["$numero_semaine"];
		$strmois=$mois["$numero_mois"];
		$Aujourdhui="$strjour le $datejour $strmois $annee";
		return $Aujourdhui;
	}
///////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////

function krakOrdonnerDate($date)
{
	sscanf($date, "%4s-%2s-%2s %2s:%2s:%2s", $annee, $mois, $jour, $heure, $minute, $seconde);
	$krakdate=$jour.'-'.$mois.'-'.$annee;//.':'.$seconde;
	if($krakdate=="00-00-0000"){$krakdate=null;}
	return $krakdate;
}
	
///////////////////////////////////////////////////////////////////////////////

function krakOrdonnerDate2($date)
{
	sscanf($date, "%4s-%2s-%2s %2s:%2s:%2s", $annee, $mois, $jour, $heure, $minute, $seconde);
	$krakdate=$jour.'-'.$mois.'-'.$annee.' à '.$heure.':'.$minute;//.':'.$seconde;
	if($krakdate=="00-00-0000 à 00:00"){$krakdate=null;}
	return $krakdate;
}
	
///////////////////////////////////////////////////////////////////////////////

function krakOrdonnerDate3($date)
{
	sscanf($date, "%4s-%2s-%2s %2s:%2s:%2s", $annee, $mois, $jour, $heure, $minute, $seconde);
	$krakdate=$jour.'-'.$mois.'-'.$annee;
	if($krakdate=="00-00-0000"){$krakdate=null;}
	return $krakdate;
}
	
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////

function krakTimeFromDB($heure)
{
	sscanf($heure, "%2s:%2s:%2s",$h, $mn, $s);
	$krakHeure=$h.':'.$s;
	return $krakHeure;
}

///////////////////////////////////////////////////////////////////////////////

function krakDateToDB($date){
	sscanf($date, "%2s-%2s-%4s",$j, $m, $a);
	$newDate=$a.'-'.$m.'-'.$j;
	return $newDate;
}
///////////////////////////////////////////////////////////////////////////////

function krakDateFromDB($date){
	sscanf($date, "%4s-%2s-%2s",$a, $m, $j);
	$newDate=$j.'-'.$m.'-'.$a;
	return $newDate;
}
	
///////////////////////////////////////////////////////////////////////////////
function isDate($date)
{	
	$date=str_replace('-','/',$date);
	
	/*
	$format_date="#^[0-9]{2,2}+/[0-9]{2,2}+/[0-9]{4,4}$#";
	if(preg_match($format_date,$date))
	{
		return true;		
	}else{
		return false;
	}	
	*/
	
	sscanf($date,'%2s/%2s/%4s',$d,$m,$y);
	
	if(checkdate($m,$d,$y)){
		return true;		
	}else{
		return false;
	}	
}
///////////////////////////////////////////////////////////////////////////////

/////////////////////: créer un captcha //////////////////////////////////////////
function ChaineAleatoire($n)
{
    $numero=array();$mot=null;
	$caracteres=array(0,1,2,3,4,5,6,7,8,9,"a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z",
	"A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
	
	for($i=0;$i<$n;$i++)
	{
		$numero[$i]=mt_rand(0,61);
	}
	for($i=0;$i<$n;$i++)
	{
		$mot.=$caracteres[$numero[$i]];
	}	
	return $mot;
}

function CreerCaptcha($texte)
{
	//les copuleurs
	$blanc = imagecolorallocate($img, 255, 255, 255); 
	$noir = imagecolorallocate($img, 0, 0, 0);
	$bleu=	imagecolorallocate($img, 0x00, 0x00, 0xff);
	$vert=	imagecolorallocate($img, 0x00, 0xff, 0x00);
	$rouge=	imagecolorallocate($img, 0xff, 0x00, 0x00);
	$rose=	imagecolorallocate($img, 0xff, 0x00, 0xff);
	$orange=imagecolorallocate($img, 0xff, 0xf0, 0x01);
	
	$couleurs=array($bleu,$vert,$rouge,$rose,$orange);
	$x=mt_rand(0,2);
	$textcolor=$couleurs[$x];
	header("Content-Type: image/jpeg");
	$image=imagecreate(100,500);
	imagestring($image,3,2,2,$texte,$bleu);
	imagejpeg($image);
	imagedestroy($img);
}


//fonctions écrites par krak et permettant d'afficher ou pas des bloc sur le site
/*bloc de droite et ses éléments*/

function AfficherMenuRight(){
	return true;
}

/////////////////////redimmensionne une image/////////////////////
function krakResizeImage($imagePath,$x=100,$y=100)
{
	Header("Content-type: image/jpeg");
	$newImg = imagecreatefromjpeg($imagePath);
	$size = getimagesize($imagePath);
	$miniImg = imagecreatetruecolor ($x, $y);
	imagecopyresampled ($miniImg,$newImg,0,0,0,0,$x,$y,$size[0],$size[1]);
	imagejpeg($miniImg);
	// affiche
	echo 'La photo a été redimensionnée automatiquement.
		  <br /> 
		  <img src="'.$imagePath.'" alt="" />
		  ';
}

/////////////////////////////////////////   AFFICHAGE DE BANNIERE PUBLICITAIRE     ////////////////////////////////////////////////////////////////

function krakDisplayPub($bloc,$width='auto',$height='auto'){
	$ourPub='<div style="margin:20px;height:60px;">Pour afficher votre publicité ici, contactez-nous au<b> 23 51 12 70</b></div>';
	$sql='select * from publicites where publier=1 and bloc="'.$bloc.'" order by id desc';
	$req=mysql_query($sql);$n=mysql_num_rows($req);
	if($n>0){
		$d=mysql_fetch_object($req);$pub=$d->pub;
		$swf='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="'.$width.'" height="'.$height.'">
			<param name="movie" value="swf/pub/'.$pub.'" />
			<param name="quality" value="high" />
			<param name="wmode" value="transparent" />
			<embed src="swf/pub/'.$pub.'" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash"  width="'.$width.'" height="'.$height.'"></embed>		
		</object>';
		$gif='<img src="swf/pub/'.$pub.'" alt="" style="max-width:'.$width.'px;max-height:'.$height.'px;"/>';
		
		$currentPub=($d->type=='swf')? $swf : $gif ;
		print '<div class="krakPub">';
			print $currentPub;
		print '</div>';
	}else{
		print $ourPub;
	} 
}



function krakDeniedAccess($url="../index.php",$img="../images/loader.gif"){
print '
<html>
<head>
<title>ECOTAMBOUR: : : ESPACE RESERVE</title>
<meta http-equiv="refresh" content="5; url='.$url.'">
<script type="text/javascript">
<!--
	var cpt=6;var i=0; 
	var x=setInterval(\'krak()\',1000);
	function krak(){
		cpt --;
		document.getElementById(\'loader\').innerHTML=cpt;
	}
-->
</script>
<style type="text/css">
<!--
html{width:100%;height:100%;}
body{margin:0px;padding:0px;background:white;}
#krakDenied{width:500px;height:300px;border:10px solid #0b94f7;margin:auto;margin-top:15%;}
h1{color:darkblue;text-align:center;border-bottom:1px solid orangered;margin:5px 10px;}
#text{color:red;font-weight:bold;text-align:center;margin:70px 0px 30px 0px;}

#loader{
	font-size:20px;
	color:red;
	background-image:url(\''. $img .'\');
	margin:auto;
	padding-top:20px;
	height:46px;
	width:66px;
	text-align:center;
-->
</style>
</head>
<body>
<div id="krakDenied">
<h1>krakCMS Version1.0</h1>
<div id="text" >ACCES NON AUTORISE</div>

			<div id="loader"></div>
</div>
</body>
</html>';
}


// Coupe un texte à $longueur caractères, sur les espaces, et ajoute des points de suspension...
function tronque($chaine, $longueur = 120) 
{
 
	if (empty ($chaine)) 
	{ 
		return ""; 
	}
	elseif (strlen ($chaine) < $longueur) 
	{ 
		return $chaine; 
	}
	elseif (preg_match ("/(.{1,$longueur})\s./ms", $chaine, $match)) 
	{ 
		return $match [1] . "..."; 
	}
	else 
	{ 
		return substr ($chaine, 0, $longueur) . "..."; 
	}
}


//fonction écrite le * septembre 2011

function canAdd($table,$user){
	$sql='select * from recharges where code="'.$user.'"';
	$req=mysql_query($sql);
	$n=mysql_num_rows($req);
	if($n>0){
		$d=mysql_fetch_object($req);
		if($d->$table > 0){
			print '<div class="notification"><b><u>Notification:</u></b> <i> Il vous reste '.($d->$table - 1).' enregistrements après celui-ci</i></div>';return true;
		}
		else{
			return false;
		}
	}
}

//fonction écrite le 13 septembre 2011 par Armand Kouassi
function displayPhoneNumbersOf($espace,$user){
	$sql='select * from quelcontactafficher where espace="'.$espace.'"';$req=mysql_query($sql);
	$d=mysql_fetch_object($req);
	if($d->$user==1)
		return true;
	else 
		return false;

}


//fonctions écrites le 16 septembre 2011 par Armand Kouassi

function krakStatistiques(){
	// récupération de l'heure courante
	$date_courante = date("Y-m-d H:i:s");  
	 
	// récupération de l'adresse IP du client (on cherche d'abord à savoir si il est derrière un proxy)
	if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
	}  
	elseif(isset($_SERVER['HTTP_CLIENT_IP'])) {  
	$ip = $_SERVER['HTTP_CLIENT_IP'];  
	}  
	else {  
	$ip = $_SERVER['REMOTE_ADDR'];  
	}  
	// récupération du domaine du client
	$host = gethostbyaddr($ip);  
	 
	// récupération du navigateur et de l'OS du client
	$navigateur = $_SERVER['HTTP_USER_AGENT'];  
	 
	// récupération du REFERER
	if (isset($_SERVER['HTTP_REFERER'])) { 
	   // if (eregi($_SERVER['HTTP_HOST'], $_SERVER['HTTP_REFERER'])) { 
		  // $referer =''; 
	   // } 
	   // else { 
		  $referer = $_SERVER['HTTP_REFERER']; 
	   // }  
	}  
	else {  
	$referer ='';  
	}  
	 
	// récupération du nom de la page courante ainsi que ses arguments
	if ($_SERVER['QUERY_STRING'] == "") {  
	$page_courante = $_SERVER['PHP_SELF'];  
	}  
	else {  
	$page_courante = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];  
	}  
	 
	// insertion des éléments dans la base de données
	$sql = 'INSERT INTO statistiques VALUES("", "'.$date_courante.'", "'.$page_courante.'", "'.$ip.'", "'.$host.'", "'.$navigateur.'", "'.$referer.'")';  
	mysql_query($sql) or die('Erreur : '.$sql.'<br />'.mysql_error());  
}

/**/
/////////
function videoPlayer($player,$video,$autostart='true',$width=200,$height=200){
	$cover=$video;
	$cover=str_replace('.flv','',$cover);
	$cover=str_replace('.avi','',$cover);
	//print $cover;
	print '<div id="videoPlayer">
	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="'.$width.'" height="'.$height.'">
		<param name="movie" value="'.$player.'"/>
		<param name="quality" value="high" />
		<param name="wmode" value="transparent" />
		<embed src="'.$player.'?controlbar=bottom&image=modules/video/'.$cover.'.gif&file='.$video.'&autostart='.$autostart.'&volume=50&repeat=all" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="'.$width.'" height="'.$height.'"></embed>		
	</object>
	</div>';
}

function imageDisplay($imgSrc,$desc='Une image de la gallérie photo'){
	print '<div id="imagedisplayer" style="width:502px;margin:auto;"><img style="width:496px;padding:2px;" src="'.$imgSrc.'"/><div class="imgDesc" style="padding:5px;text-align:center;border:1px solid #eee;-webkit-border-radius:3px;-moz-border-radius:3px;-border-radius:3px;"/>'.$desc.'</div></div>';
}


/*14-12-2011*/
function canSubmit1($table,$interval=30){
	$ip=$_SERVER['REMOTE_ADDR'];
	$sql='select * from cansubmit where (`table`="'.$table.'" and `ip`="'.$ip.'") order by id desc';
	$req=mysql_query($sql) or die (mysql_error());$n=mysql_num_rows($req);
	$dt=$interval+1;
	if($n>0){
		$d=mysql_fetch_object($req);
		$t0=$d->timestamp;
		$t1=time();
		$dt=$t1-$t0;
	}
	if($dt>$interval){
		return true;
	}else{
		return false;
	}
	
}

function canSubmit2($table,$interval=30){
	$ip=$_SERVER['REMOTE_ADDR'];
	$date=date('Y-m-d');
	$id_session=$_SESSION['submitId'];
	$sql='select * from cansubmit2 where (`table`="'.$table.'" and `id_session`="'.$id_session.'")  order by id desc';
	$req=mysql_query($sql) or die (mysql_error());$n=mysql_num_rows($req);
	$dt=$interval+1;
	if($n>0){
		$d=mysql_fetch_object($req);
		$t0=$d->timestamp;
		$t1=time();
		$dt=$t1-$t0;
	}
	if($dt>$interval){
		return true;
	}else{
		return false;
	}
	
}

function updateSubmitTable($table){
	$time=time();
	$date=date('Y-m-d H:i:s');
	$ip=$_SERVER['REMOTE_ADDR'];
	$id_session=$_SESSION['submitId'];
	$sql01='INSERT INTO `cansubmit2` (`id`, `table`, `ip`, `id_session`, `timestamp`, `date`, `nbreupdate`) VALUES (NULL, "'.$table.'", "'.$ip.'", "'.$id_session.'", "'.$time.'", "'.$date.'", "1")';
	$sql02='update cansubmit2 set timestamp="'.$time.'" where (table="'.$table.'" and ip="'.$ip.'" and id_session="'.$id_session.'")';
	// $sql02='update cansubmit2 set timestamp="'.$time.'", nbreupdate = nbreupdate+ 1 where (table="'.$table.'" and ip="'.$ip.'" and id_session="'.$id_session.'")';
	
	if(!mysql_query($sql02)){
		mysql_query($sql01) or die(mysql_error());
	}
}

// le 23-01-2012 à 04:12 ////////////////////////////////////////////////////
	//vérifie si une donnée est dans une table
function isInDB($data,$table,$field)
{
	$sql='select * from '.$table.' where '.$field.'="'.$data.'"';
	$req=mysql_query($sql);$n=mysql_num_rows($req);
	
	if($n==1){	return true; }else{ return false; }
}


//fn de manipulation d'images
function creerMiniature2($chemin,$ext,$W,$H,$color=array(0,0,0)){
	$chemin2=substr($chemin,0,strrpos($chemin,'.')).'_min.'.$ext;
	$img_src_resource=null;
	
	if($ext=='jpg'){
		$img_src_resource=imagecreatefromjpeg($chemin);
	}elseif($ext=='png'){
		$img_src_resource=imagecreatefrompng($chemin);
	}elseif($ext=='gif'){
		$img_src_resource=imagecreatefromgif($chemin);
	}
	
	$img_dst_resource = imagecreatetruecolor($W, $H);
	$color=imagecolorallocate($img_dst_resource,$color[0],$color[1],$color[2]);
	imagefill($img_dst_resource,0,0,$color);

	list( $w, $h ) = getimagesize($chemin);

	//L’image est plus petite en hauteur et en largeur :
	//On recopie l'image telle quelle, sans la redimensionner, on la centre en hauteur et en largeur
	if(($w<=$W) and ($h<=$H))
	{
		imagecopyresampled($img_dst_resource,$img_src_resource,((int)(($W-$w)/2)),((int)(($H-$h)/2)),0,0,$w,$h,$w,$h);
		
		if($ext=='jpg'){
			imagejpeg( $img_dst_resource, $chemin2,200);
		}elseif($ext=='png'){
			imagepng( $img_dst_resource, $chemin2,9);
		}elseif($ext=='gif'){
			imagegif( $img_dst_resource, $chemin2,9);
		}
		
	}

	//Cas n°2 : L’image sort du cadre et est proportionnement trop grande en largeur
	//On redimentionne pour avoir la bonne largeur et la centre en hauteur
	if((($w>$W) or ($h>$H)) and (($w/$h)>($W/$H)))
	{
		$new_w=$W;
		$new_h=($h*$new_w)/$w;
		imagecopyresampled($img_dst_resource,$img_src_resource,0,((int)(($H-$new_h)/2)),0,0,$new_w,$new_h,$w,$h);
		
		if($ext=='jpg'){
			imagejpeg( $img_dst_resource, $chemin2,200);
		}elseif($ext=='png'){
			imagepng( $img_dst_resource, $chemin2,9);
		}elseif($ext=='gif'){
			imagegif( $img_dst_resource, $chemin2,9);
		}
		
	}

	//Cas n°3 : L’image sort du cadre et est proportionnement trop grande en hauteur :
	//On redimentionne pour avoir la bonne hauteur et on centre en largeur
	if((($w>$W) or ($h>$H)) and (($w/$h)<=($W/$H)))
	{
		$new_h=$H;
		$new_w=($w*$new_h)/$h;
		imagecopyresampled($img_dst_resource,$img_src_resource,((int)(($W-$new_w)/2)),0,0,0,$new_w,$new_h,$w,$h);
		
		if($ext=='jpg'){
			imagejpeg( $img_dst_resource, $chemin2,200);
		}elseif($ext=='png'){
			imagepng( $img_dst_resource, $chemin2,9);
		}elseif($ext=='gif'){
			imagegif( $img_dst_resource, $chemin2,9);
		}
		
	}

}

function creerMiniature1($chemin,$W,$H,$color=array(0,0,0)){
	$chemin2=substr($chemin,0,strrpos($chemin,'.')).'_min.jpg';

	$img_src_resource=imagecreatefromjpeg($chemin);
	$img_dst_resource = imagecreatetruecolor($W, $H);
	$color=imagecolorallocate($img_dst_resource,$color[0],$color[1],$color[2]);
	imagefill($img_dst_resource,0,0,$color);

	list( $w, $h ) = getimagesize($chemin);

	//L’image est plus petite en hauteur et en largeur :
	//On recopie l'image telle quelle, sans la redimensionner, on la centre en hauteur et en largeur
	if(($w<=$W) and ($h<=$H))
	{
	imagecopyresampled($img_dst_resource,$img_src_resource,((int)(($W-$w)/2)),((int)(($H-$h)/2)),0,0,$w,$h,$w,$h);
	imagejpeg( $img_dst_resource, $chemin2,200);
	}

	//Cas n°2 : L’image sort du cadre et est proportionnement trop grande en largeur
	//On redimentionne pour avoir la bonne largeur et la centre en hauteur
	if((($w>$W) or ($h>$H)) and (($w/$h)>($W/$H)))
	{
	$new_w=$W;
	$new_h=($h*$new_w)/$w;
	imagecopyresampled($img_dst_resource,$img_src_resource,0,((int)(($H-$new_h)/2)),0,0,$new_w,$new_h,$w,$h);
	imagejpeg( $img_dst_resource, $chemin2,200);
	}

	//Cas n°3 : L’image sort du cadre et est proportionnement trop grande en hauteur :
	//On redimentionne pour avoir la bonne hauteur et on centre en largeur
	if((($w>$W) or ($h>$H)) and (($w/$h)<=($W/$H)))
	{
	$new_h=$H;
	$new_w=($w*$new_h)/$h;
	imagecopyresampled($img_dst_resource,$img_src_resource,((int)(($W-$new_w)/2)),0,0,0,$new_w,$new_h,$w,$h);
	imagejpeg( $img_dst_resource, $chemin2,200);
	}

}

function creerMiniature($chemin,$H,$W){

	$chemin2=substr($chemin,0,strrpos($chemin,'.')).'_min.jpg';

	$img_src_resource=imagecreatefromjpeg($chemin);

	list( $w, $h ) = getimagesize($chemin);

	//Cas n°1 : L'image est plus petite en hauteur et en largeur : on la recopie telle quelle

	if(($w<=$W) and ($h<=$H))

	{

	$img_dst_resource = imagecreatetruecolor($w, $h);

	imagecopyresampled($img_dst_resource,$img_src_resource,0,0,0,0,$w,$h,$w,$h);

	imagejpeg( $img_dst_resource, $chemin2,200);

	}

	//Cas n°2 : L'image dépasse en hauteur mais est trop petite en largeur :

	// On coupe ce qui dépasse en hauteur

	if(($w<=$W) and ($h>$H))

	{

	$img_dst_resource = imagecreatetruecolor($w, $H);

	imagecopyresampled($img_dst_resource,$img_src_resource,0,0,0,0,$w,$H,$w,$H);

	imagejpeg( $img_dst_resource, $chemin2,200);

	}

	//Cas n°3 : L'image dépasse en largeur mais est trop petite en hauteur :

	// On coupe ce qui dépasse en largeur

	if(($w>$W) and ($h<=$H))

	{

	$img_dst_resource = imagecreatetruecolor($W, $h);

	imagecopyresampled($img_dst_resource,$img_src_resource,0,0,0,0,$W,$h,$W,$h);

	imagejpeg( $img_dst_resource, $chemin2,200);

	}

	//Cas n°4 : L'image dépasse en largeur et en hauteur et est proportionnement trop grande en largeur

	// On redimentionne pour avoir la bonne hauteur et on coupe ce qui dépasse en largeur

	if(($w>$W) and ($h>$H) and (($w/$h)>($W/$H)))

	{

	$img_dst_resource = imagecreatetruecolor($W, $H);

	imagecopyresampled($img_dst_resource,$img_src_resource,0,0,0,0,$W,$H,$W*$h/$H,$h);

	imagejpeg( $img_dst_resource, $chemin2,200);

	}

	//Cas n°5 : L'image dépasse en largeur et en hauteur et est proportionnement trop grande en hauteur

	// On redimentionne pour avoir la bonne largeur et on coupe ce qui dépasse en hauteur

	if(($w>$W) and ($h>$H) and (($w/$h)<=($W/$H))){
		$img_dst_resource = imagecreatetruecolor($W, $H);

		imagecopyresampled($img_dst_resource,$img_src_resource,0,0,0,0,$W,$H,$w,$w*$H/$W);

		imagejpeg( $img_dst_resource, $chemin2,200);
	}

}



	
//fn bdd
//renvoi la valeur d'un champ respectant une condition donnée
function getBy($table,$wantedfield,$refField,$refValue,$defaultValue=null){
	$wantedvalue=$defaultValue;
	$sql='select '.$wantedfield.' from '.$table.' where '.$refField.'="'.$refValue.'"';
	$req=mysql_query($sql) or die(mysql_error());$n=mysql_num_rows($req);
	if($n>0){
		$d=mysql_fetch_object($req);
		$wantedvalue=$d->$wantedfield;
	}
	
	return $wantedvalue;
}
//renvoi la valeur d'un champ respectant une condition donnée
function getByC($table,$wantedfield,$condition,$defaultValue=null){
	$wantedvalue=$defaultValue;
	$sql='select '.$wantedfield.' from '.$table.' where '.$condition;//debug($sql);
	$req=mysql_query($sql) or die(mysql_error());$n=mysql_num_rows($req);
	if($n>0){
		$d=mysql_fetch_object($req);
		$wantedvalue=$d->$wantedfield;
	}
	
	return $wantedvalue;
}
///renvoi la valeur d'un champ
function getFV($table,$wantedfield){
	$wantedvalue=null;
	$sql='select '.$wantedfield.' from '.$table;
	$req=mysql_query($sql) or die(mysql_error());$n=mysql_num_rows($req);
	if($n>0){
		$d=mysql_fetch_object($req);
		$wantedvalue=$d->$wantedfield;
	}
	
	return $wantedvalue;
}

//renvoi les valeurs d'un champ sous forme de array
function getColRows($table,$wantedfield,$refField,$refValue){
	$FieldRows=array();
	$sql='select '.$wantedfield.' from '.$table.' where '.$refField.'="'.$refValue.'"';
	$req=mysql_query($sql) or die(mysql_error());
	
	while($d=mysql_fetch_object($req)){
		$FieldRows[]=$d->$wantedfield;
	}
	
	return $FieldRows;
}

// connexionDB();
// $pass= getBy('administrateurs','email','login','micropro7');
// print $pass;
function cptD($table,$wantedfield,$refField,$refValue,$refField2=null,$refValue2=null,$date){
	$wantedvalue=0;
	$sql='select '.$wantedfield.' from '.$table.' where date >= "'.$date.'" and '.$refField.'="'.$refValue.'"';
	$and=!empty($refField2)? ' and  '.$refField2.'="'.$refValue2.'"' : null ;
	$sql.= $and;
	// print $sql;	
	$req=mysql_query($sql) or die(mysql_error());$n=mysql_num_rows($req);
	if($n>0){
		while($d=mysql_fetch_object($req)){
			$wantedvalue = $wantedvalue + $d->$wantedfield;
		}
	}
	
	return $wantedvalue;
}

//return l'id du dernier enregistrement
function getLastLineId($table,$pkey){
	$sql='select '.$pkey.' from '.$table.' order by '.$pkey.' desc';
	
	$req=mysql_query($sql) or die(mysql_error());$n=mysql_num_rows($req);
	$d=mysql_fetch_object($req);
	return $d->$pkey;
}

//fn pr retourner à la page précédente
function retour(){
	print '<a  hover="Page précédente" href="'.$_SESSION['prev'].'" style="float:right;"><img src="images/prev.gif"></a>';
}

//fn pr extraire des numéros de téléphones contenus dans une chaine


//24 JUILLT 2014
	//vérifie si des donnée est dans une table
function findRow($sql)
{
	$req=mysql_query($sql) or die(mysql_error());$n=mysql_num_rows($req);
	if($n>0){	return true; }else{ return false; }
}

function countRow($sql)
{
	$req=mysql_query($sql);$n=mysql_num_rows($req);
	
	return $n;
}

//fn added by quickApp accoding to database params




