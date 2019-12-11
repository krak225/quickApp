<?php
function connexionDB(){		
	// @mysql_connect('127.0.0.1','ecotambo','dkconseildma2010') or die ('krakCMS-Error: Connexion failled---');
	// @mysql_select_db('ecotambo_fredetpoppee_kw-admin') or die ('krakCMS-Error: Database not found');
	
	@mysql_connect('127.0.0.1','root','') or die ('krakCMS-Error: Connexion failled---');
	@mysql_select_db('kw-ecole') or die ('krakCMS-Error: Database not found');
}


function kraksecurise($chaine){
	$chaine=mysql_real_escape_string(htmlspecialchars(trim($chaine)));
	return $chaine;
}


/* Fonctions créee par Armand Kouassi le 09/08/2010 */

function resumerTexte($chaine,$n=100) 
{
	$out=null;$i=0;$j=0;
	$tab=str_split($chaine);

	$size=count($tab);
	if($size>$n)
	{
		for($i=0;$i<$n;$i++)
		{
			$char=$tab[$i];
			$out.=$char;
		}
			
		for($j=$n;$j<$size;$j++)
		{   
			$char=$tab[$j];
			if($char==' ')
			{
				$char='...';
				$j=$size+10;
			}
			
			$out.=$char;
		}
	}	
	else{$out=$chaine;}

    return $out;
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
function isInDB($data,$table,$field){
	$sql='select * from '.$table.' where '.$field.'="'.$data.'"';
	$req=mysql_query($sql);$n=mysql_num_rows($req);
	
	if($n==1){	return true; }else{ return false; }
}

	
////////////////////////// fn pour gérer les pages //////////////////////////
function getPage(){
	//récupérer la page en cours
	// $root='/kw-ecole/';
	$root='/web/kw-ecole/';
	$page=str_replace($root,null,$_SERVER['PHP_SELF']);
	$page=str_replace('/',null,$page);
	$page=str_replace('.php',null,$page);
	// print $root;
	// print $page;
	$_SESSION['page']=$page;
	$currentPage=$_SESSION['page'];
	
	return $page;
}
	

function debug($var){
	print '<pre>';
	print_r($var);
	print '</pre>';
}



function copyFiles($dir2copy,$dir_paste){
  // On vérifie si $dir2copy est un dossier
  if (is_dir($dir2copy)) {
 
    // Si oui, on l'ouvre
    if ($dh = opendir($dir2copy)) {     
      // On liste les dossiers et fichiers de $dir2copy
      while (($file = readdir($dh)) !== false) {
        // Si le dossier dans lequel on veut coller n'existe pas, on le créé
        if (!is_dir($dir_paste)) mkdir ($dir_paste, 0777);
 
          // S'il s'agit d'un dossier, on relance la fonction rÃ©cursive
          if(is_dir($dir2copy.$file) && $file != '..'  && $file != '.') copyFiles( $dir2copy.$file.'/' , $dir_paste.$file.'/' );     
            // S'il sagit d'un fichier, on le copue simplement
            elseif($file != '..'  && $file != '.') copy ( $dir2copy.$file , $dir_paste.$file );                                       
         }
 
      // On ferme $dir2copy
      closedir($dh);
 
    }
 
  }
}


//supprimer un dossier
function supprimerDossier($dir2copy){
  // On vérifie si $dir2copy est un dossier
  if (is_dir($dir2copy)) {
 
    // Si oui, on l'ouvre
    if ($dh = opendir($dir2copy)) {
      // On liste les dossiers et fichiers de $dir2copy
      while (($file = readdir($dh)) !== false){
 
          // S'il s'agit d'un dossier, on relance la fonction rÃ©cursive
          if(is_dir($dir2copy.$file) && $file != '..'  && $file != '.') supprimerDossier($dir2copy.$file.'/');     
            // S'il sagit d'un fichier, on le supprime
            elseif($file != '..'  && $file != '.'){ @unlink ($dir2copy.$file); }                                      
         }
 
      // On ferme $dir2copy
      closedir($dh);
 
    }
	//supprimer le dossier de base
	@unlink($dir2copy);
	
	
  }
}



function zipDir($nom_archive, $adr_dossier, $dossier_destination = '', $zip=null, $dossier_base = '') {
	if($zip===null) {
		// Si l'archive n'existe toujours pas (1er passage dans la fonction, on la crée)
		$zip = new ZipArchive();
		if($zip->open($nom_archive, ZipArchive::CREATE) !== TRUE) {
			// La création de l'archive a échouée
			return false;
		}
	}
	
	if(substr($adr_dossier, -1)!='/') {
		// Si l'adresse du dossier ne se termine pas par '/', on le rajoute
		$adr_dossier .= '/';
	}
	
	if($dossier_base=="") {
		// Si $dossier_base est vide ça veut dire que l'on rentre
		// dans la fonction pour la première fois. Donc on retient 
		// le tout premier dossier (le dossier racine) dans $dossier_base
		$dossier_base=$adr_dossier;
	}
	
	if(file_exists($adr_dossier)) {
		if(@$dossier = opendir($adr_dossier)) {
			while(false !== ($fichier = readdir($dossier))) {
				if($fichier != '.' && $fichier != '..') {
					if(is_dir($adr_dossier.$fichier)) {
						$zip->addEmptyDir($adr_dossier.$fichier);
						zipDir($nom_archive, $adr_dossier.$fichier, $dossier_destination, $zip, $dossier_base);
					}
					else {
						$zip->addFile($adr_dossier.$fichier);
					}
				}
			}
		}
	}
	
	if($dossier_base==$adr_dossier) {
		// On ferme la zip
		$zip->close();
		
		if($dossier_destination!='') {
			if(substr($dossier_destination, -1)!='/') {
				// Si l'adresse du dossier ne se termine pas par '/', on le rajoute
				$dossier_destination .= '/';
			}
			
			// On déplace l'archive dans le dossier voulu
			if(rename($nom_archive, $dossier_destination.$nom_archive)) {
				return true;
			}
			else {
				return false;
			}
		}
		else {
			return true;
		}
	}
}


?>