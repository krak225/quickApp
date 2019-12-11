<?php
 class krakFormCreator{
	public $db;
	public $table;
	public $outpath;
	public $dbhost;
	public $dbh;
	public $dbu;
	public $dbp;
	
	public function __construct(){
		$this->auteur='krak +225 04 78 36 89';
		$this->outpath='modules/kfc-forms/'.$this->db.'/';
		// $this->outpath=$this->db.'/';
		$this->dbhost='localhost';
		$this->dbu='root';
		$this->dbp='';
	}
 	public function connectDB($db,$h='localhost', $u='root',$p=''){
		//print $this->dbhost.'-'.$this->dbu;
		$this->db=$db;
		$this->dbhost=$h;
		$this->dbu=$u;
		$this->dbp=$p;
		$this->dbh=mysql_connect($this->dbhost,$this->dbu,$this->dbp)or die('<div class="erreur">'.mysql_error().'</div>');
		$hl=mysql_select_db($this->db) or die('<div class="echec">Base de données introuvable</div>').mysql_error();
	}

 	public function setDB($db){
		// $this->db=$db;
		$this->connectDB();
		// $hl=mysql_select_db($this->db) or die('<div class="echec">Base de données introuvable</div>');
	}

	public function setPath($outpath){
		$this->outpath=$outpath;
	}
	
	public function createAllForms(){
		$db=$this->db;$cpt=0;$err=0;
		$kMenu=null;
		$kMenu='
		<?php
		class kMenu{
			
		';
		
		$sqlCreationTableAdministrateur="CREATE TABLE IF NOT EXISTS `kw_administrateur` (
			  `kw_administrateur_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `kw_administrateur_login` varchar(255) NOT NULL,
			  `kw_administrateur_pass` varchar(255) NOT NULL,
			  `kw_administrateur_email` varchar(50) NOT NULL,
			  `kw_administrateur_rang` int(11) NOT NULL,
			  `kw_administrateur_statut` enum('ACTIVE','DESACTIVE') NOT NULL DEFAULT 'ACTIVE',
			  PRIMARY KEY (`kw_administrateur_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='enregistre les administrateurs du site' AUTO_INCREMENT=1 ;
			";
		
		$sqlInsertionAdministrateur="INSERT INTO `kw_administrateur` (`kw_administrateur_login`, `kw_administrateur_pass`, `kw_administrateur_email`, `kw_administrateur_rang`, `kw_administrateur_statut`) 
		VALUES ('admin', 'fece6adde0ec8c975e2b5ec91fce57ab1852fca4', 'krak225@gmail.com', 1,'ACTIVE')
		";
		if(mysql_query($sqlCreationTableAdministrateur)){
			mysql_query($sqlInsertionAdministrateur) or die(mysql_error());
		}else{
			print mysql_error();
		}
		
		$dbreq=mysql_query('SHOW TABLES') or die('<div class="echec">'.mysql_error().'</div>');
		while($d=mysql_fetch_object($dbreq)){
			$table='Tables_in_'.$db;
			$tablename=$d->$table;
			$outpath=$this->outpath;
			if($this->createFormulaire($tablename,$this->outpath)){
				$cpt++;
			}else{$err++;}
		}
		
		//
		$kMenu.='
			public $menu=array(
			"index"=>"Accueil",';
		$dbreq1=mysql_query('SHOW TABLES') or die('<div class="echec">'.mysql_error().'</div>');
		while($d=mysql_fetch_object($dbreq1)){
			$table='Tables_in_'.$db;
			$tablename=$d->$table;
			$kMenu.='
			"'.$tablename.'"=>"'.ucfirst($tablename).'",';
		}
		
		$kMenu.=');';
		
		$kMenu.='
			public $sousmenu=array(
			"index"=>"",';
			
		$dbreq2=mysql_query('SHOW TABLES') or die('<div class="echec">'.mysql_error().'</div>');
		while($d=mysql_fetch_object($dbreq2)){
			$table='Tables_in_'.$db;
			$tablename=$d->$table;
			$kMenu.='
				"'.$tablename.'"=>array("Enregistrer un '.$tablename.'"=>array("editer"),
						"Afficher les '.$tablename.'s"=>array("gerer"),
						),';
		}
		
		$kMenu.=');';
		
		$kMenu.='
			//les pages sans label dans le menu
			public $nolabel=array("motdelafondatrice");
			//menu sans sous menu
			// public $nosoumenu=array("contact","motdelafondatrice");
			
			function hasSousmenu($menu){
				
				if(sizeof($this->sousmenu[$menu]) > 1){
					return true;
				}else{
					return false;
				}
			}
			
			function getMenuLabel($menu){
				return $this->menu[$menu];
			}
			
			function insertMenu(){
				foreach($this->menu as $url=>$label){
					if(!in_array($url,$this->nolabel)){
						$active = (getPage()==$url)? \' class="active" \' : null;
						print \'<li><a href="\'.$url.\'.php" \'.$active.\'>\'.$label.\'</a></li>\';
					}
				}
			}
			
			function insertSousMenu($menu){
				foreach($this->sousmenu[$menu] as $label=>$url){
					print \'<li><a href="\'.$menu.\'.php?page=\'.$url[0].\'">\'.$label.\'</a></li>\';
				}
			}
			
			function getTitle(){
				return null;
			}
		
		}
	?>
		';
		
		//créer le fichier kMenu
		$file=$outpath.'/classes/kMenu.php';
		$fp=fopen($file,'a+');
		fputs($fp,$kMenu);
		fclose($fp);
			
		//créer le fichier fonctions.php
		$fonctions='
		function connexionDB(){		
			$host="'.$this->dbhost.'";$user="'.$this->dbu.'";$pass="'.$this->dbp.'";$dbname="'.$db.'";
			mysql_connect($host,$user,$pass) or die("Impossible de se connecter au serveur mySQL");
			mysql_select_db($dbname); 
		}
		
		function getPage(){
			//récupérer la page en cours
			$root="/'.$this->db.'/";
			$page=str_replace($root,null,$_SERVER["PHP_SELF"]);
			$page=str_replace("/",null,$page);
			$page=str_replace(".php",null,$page);
			$_SESSION["page"]=$page;
			$currentPage=$_SESSION["page"];
			
			return $page;
		}

		';
		$file=$outpath.'/fonctions/fonctions.php';
		$fp=fopen($file,'a+');
		fputs($fp,$fonctions);
		fclose($fp);
		

		//Créer le fichier fnDB.php
		$fnDB='
		<?php
		//Copyright: Armand Kouassi (@krak225 , krak225@gmail.com, +225 08779408)
		//Fonction de connexion à la Base de données
		function getPDO(){		
			
			$pdo = new PDO("mysql:host='. $this->dbhost .';dbname='.$db.'","'.$this->dbu.'","'.$this->dbp.'");
			$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
			
			return $pdo;
		}

		?>

		';
		$file=$outpath.'/fonctions/fnDB.php';
		$fp=fopen($file,'a+');
		fputs($fp,$fnDB);
		fclose($fp);


		// print 'wcwc'.$cpt;
		if($err==0){
			return true;
		}else{
			return false;
		}
	}
	
	public function createForm($tablename){
		if($this->createFormulaire($tablename,$this->outpath)){
			return true;
		}else{
			return false;
		}
	}

	
	private function createFiles($table,$base_url){
	
		if(!is_dir($base_url.'/'.'images/')){
			mkdir($base_url.'/'.'images/');
		}
		if(!is_dir($base_url.'/'.'images/upload-'.$table.'/')){
			mkdir($base_url.'/'.'images/upload-'.$table.'/');
		}
		//créer la page qui appel les modules (insertion et affichage)
		$file=$base_url.'/'.$table.'.php';
		copy("krak.txt",$file);//alrtnative echec de création ds fichier - copier un fichier existant puis le modifier
		// print $file;
		// if(file_exists($file)){unlink($file);}
		$fp=fopen($file,'a+');
		
		$content='
<?php require_once("inc/head_std.php");?>

<!-- debut du contenu de la page -->
	<div id="pageContent">
	
		<!-- debut de la partie modifiable -->
		<div class="r-box" id="r-box-3">
			<!--<div class="box-title"><div class="vert"><?php //print "le titre de la page";?></div></div>-->
			<div class="box-content">
				<?php
					$sm=array("editer","modifier","gerer","detail","corbeille");
					$html=(isset($_GET["page"]) and in_array($_GET["page"],$sm)) ?  $_GET["page"] : "gerer";
					
					require_once("modules/mod-'.$table.'/'.$table.'-".$html.".php");
					
				?>
			</div>
		</div>
		<!-- fin de la partie modifiable -->
		
	</div>
	
	<!-- fin du contenu de la page -->
<?php require_once("inc/foot_std.php"); ?>
		';
		
		fputs($fp,$content);
		fclose($fp);
		
		
	}
	
	private function createFormulaire($table,$outpath){
		$this->createFiles($table,$outpath);
		//extract($_POST);
		$sql='select * from '.$table;
		//pr insertion
		$req=mysql_query($sql) or die ('<div class="echec">'.mysql_error().'</div>');
		$req1=mysql_query($sql) or die ('<div class="echec">'.mysql_error().'</div>');
		$req2=mysql_query($sql) or die ('<div class="echec">'.mysql_error().'</div>');
		$req3=mysql_query($sql) or die ('<div class="echec">'.mysql_error().'</div>');
		//pr Affichage
		$req4=mysql_query($sql) or die ('<div class="echec">'.mysql_error().'</div>');
		$req5=mysql_query($sql) or die ('<div class="echec">'.mysql_error().'</div>');
		$req51=mysql_query($sql) or die ('<div class="echec">'.mysql_error().'</div>');
		$req52=mysql_query($sql) or die ('<div class="echec">'.mysql_error().'</div>');
		// pr modification
		$req6=mysql_query($sql) or die ('<div class="echec">'.mysql_error().'</div>');
		$req7=mysql_query($sql) or die ('<div class="echec">'.mysql_error().'</div>');
		// pr impression
		$req8=mysql_query($sql) or die ('<div class="echec">'.mysql_error().'</div>');
		$req9=mysql_query($sql) or die ('<div class="echec">'.mysql_error().'</div>');
		
		$i=0;
		$SECUREDATA=array();
		$tabinit=array();
		$tablibelles=array();
		$tabinsert=array();
		$tabfields=array();$unlibelle=null;$i=0;
		while($d1=mysql_fetch_field($req1)){
			if($i>0 and(strtolower($d1->name)!=$table."_statut")){
				$lib=str_replace($table.'_',null,$d1->name);
				$lib=str_replace('_id',null,$lib);
				$unlibelle='"'.ucfirst($lib).'"';
				$tablibelles[]=str_replace('_',' ',$unlibelle);
				$tabfields[]=$d1->name;
				$tabinsert[]='"\'.$'.$d1->name.'.\'"';
				$tabinit[]='$'.$d1->name.'=null';
				$SECUREDATA[]='$'.$d1->name.'= securisedData($'.$d1->name.')';
			}
			$i++;
		}
		$libelles_array=$tablibelles;
		$tablibelles=implode(',',$tablibelles);
		
		$fields_array=$tabfields;
		$tabfields=implode(',',$tabfields);
		
		$insert_array=$tabinsert;
		$tabinsert=implode(',',$tabinsert);
		
		$init_array=$tabinit;
		$tabinit=implode(';',$tabinit);
		$SECUREDATA=implode(";\n\t\t\t",$SECUREDATA);

//*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*//
	//la page d'insertion de données
	$form='<div class="leftbox" id="" style="padding:0px;">
			<div class="blocTitle">ENREGISTRER UN '.strtoupper($table).'</div>
			<div class="blocContent" style="margin-top:5px;padding:0px;">
				<div class="krakModule">

		<div id="article-editer" class="module editeMsg" style="padding:0px;">
		<script type="text/javascript">
			<!--
			$("document").ready(function(){
				$(".wysiwyg").wysiwyg();	
				$(".wysiwyg").css({width:"100%"});
				//
			});
			-->
		</script>
		'."\n";

	$form.="<?php\n";
	$form.='//Page générée automatiquement par quickApp V 2.0 :(Copyright: Armand Kouassi, @krak225, krak225@gmail.com, +225 08779408), le '. date("d-m-Y à H:i:s").'  
	$info=\'<div class="default-info">Renseignez tous les champs puis cliquez sur <b>Valider</b> </div>\';
		'.$tabinit.';
		
		$libelles=array('.$tablibelles.',"Code de sécurité");
		$extensionsvalides=array("jpg","gif","png","bmp");
		$imageDirectory="../images/upload-'.$table.'/";
		$x=new krakVerification();
		$x->_initialiser();
		$x->InitLibelles($libelles);
		
		if(isset($_POST["add"])){ 
			extract($_POST);
			
			'.$SECUREDATA.';
			
			';
			if($this->haveImage($table)){
				$imagefield=$this->getImageField($table);
				$form.='$'.$imagefield.'=$_FILES["'.$table.'_'.$imagefield.'"];'."\n\t";
			}
			
			// $form.='$x->verifierCaptcha($captcha,$_SESSION["captcha"],"Code de sécurité",0);'."\n\t";
			$k=0;
			while($d2=mysql_fetch_field($req2)){//print $d2->type;
				if($k>0 and (strtolower($d2->name)!=$table."_statut")){ 
					$lib=str_replace($table.'_',null,$d2->name);
					$lib=str_replace('_id',null,$lib);
					$lib=str_replace('_',' ',$lib);
					$lib=ucfirst($lib);
					$lib_pj=str_replace($table.'_',null,$d2->name);
					//si image
					
					if($lib=='Image' or $lib=='Logo' or $lib=='Photo' or $lib=='Picture'){
						$form.= "\t".'$x->verifierPieceJointe($'.$lib_pj.',"'.$lib.'",10000000,1000000,1000000,$extensionsvalides,1);'."\n\t";
					}elseif($lib=='Email'){
							$form.= "\t".'$x->verifierEmail($'.$d2->name.',"'.$lib.'",1);'."\n\t";
					}else{//si pas image
						if($d2->type=="string" or $d2->type=="blob"){
							$form.= "\t".'$x->verifierChaine($'.$d2->name.',"'.$lib.'",3,1);'."\n\t";
						}elseif($d2->type=="int" or $d2->type=="float"){
							$form.= "\t".'$x->verifierNombre($'.$d2->name.',"'.$lib.'",0,100000000000000000000000,1);'."\n\t\t\t";
						}
					}
				}
				$k++;
			}
		$form.='
			if($x->ToutEstCorrecte()){  
				';
			if($this->haveImage($table)){
				$imagefield=$this->getImageField($table);
				$form.='$'.$table.'_'.$imagefield.'=$x->nomFichier($'.$imagefield.');
				$ext=$x->extensionfichier($'.$imagefield.');
				$'.$table.'_'.$imagefield.'= \''.$table.'_'.$imagefield.'_\'.time().\'_\'.mt_rand(1000,1000000000).\'.\'.$ext;'."\n\t\t\t\t";
			}
			$form.='$sql=\'INSERT INTO `'.$table.'` ('.$tabfields.') 
				VALUES ('.$tabinsert.')\';

				//if(mysql_query($sql)){

				$stm = $db->pdo->prepare($sql);

				if($stm->execute()){

					$info=\'<div class="succes">Enregistrement effectué avec succès</div>\'; 
					';
					
				if($this->haveImage($table)){
					$imagefield=$this->getImageField($table);
					$form.='
					//déplacer le fichier
					if(!empty($ext)){
						$x->DeplacerFichier($'.$imagefield.',$'.$table.'_'.$imagefield.',$imageDirectory);'."\n".'
						// creerMiniature($imageDirectory.\'/\'.$article_image,640,480,array(200,200,200));
						// creerMiniature($imageDirectory.\'/\'.$article_image,480,200,array(200,200,200));
						// creerMiniature2($imageDirectory.\'/\'.$article_image,$ext,480,200,array(200,200,200));
					}';
				}
					
					$form.='
					//initialiser les variables
					'.$tabinit.';
				}
				else{
					$info=\'<div class="echec">Désolé!! enregistrement non effectué .</div>\'. mysql_error();
				}		
			}
			else
			{  
				$info=\'<div class="echec">Attemtion!! erreurs dans le formulaire</div>\'; 
			}
		}
	';
				
	$form.= "?>\n";
	$form.= '
	<div class="info"><?php print $info;?></div>
	<form id="form-'.$table.'" enctype="multipart/form-data" method="post" data-creator="kw-Builder">'."\n".
	'<fieldset>
	';
	$i=0;
	while($d=mysql_fetch_field($req)){

		if($i>0 and (strtolower($d->name)!=$table."_statut")){//PERMET DE NE PAS PRENDRE EN COMPTE L'ID DE LA TABLE
			$lib=str_replace($table.'_',null,$d->name);
			$lib=str_replace('_id',null,$lib);
			$lib=str_replace('_',' ',$lib);
			$lib=ucfirst($lib);
			$form.= "\t".'<label>'.$lib.'</label>'."\n";
				//test s'il s'agit du clé parent qui a migré alors utilisation d'un select
				$tab=explode('_',$d->name);
				$x=sizeof($tab);
				/*print $tab[$x-1];
				print '<pre>';
				print_r($tab);
				print '</pre>';*/
			//si c'est un table parent qui a migré
			if($tab[$x-1]=='id'){
				$table_parent=str_replace('_id',null,$d->name);
				$form.= "\t".'<select class="champ" name="'.$d->name.'" id="'.$d->name.'">
					<option value=""></option>
					<?php 
					$sql=\'select * from '.$table_parent.'\';
					//$req=mysql_query($sql);
					//while($d=mysql_fetch_object($req)){
					$stm = $db->pdo->prepare($sql);
					$stm->execute();
					$data = $stm->fetchAll(PDO::FETCH_OBJ);
					foreach($data as $d){
						$selected = ($d->'.$table_parent.'_id==$'.$table_parent.'_id)? \' selected \' : null;
						print \'<option \'.$selected.\' value="\'.$d->'.$table_parent.'_id.\'">\'.$d->'.$table_parent.'_id.\'</option>\';
					}
					?>'."\n";
				$form.='</select>'."\n";
				
			}else{//si pas clé parente
				$name=str_replace($table.'_',null,$d->name);
				if($d->type=='blob'){
					$form.= "\t".'<textarea class="wysiwyg" name="'.$d->name.'" id="'.$d->name.'"><?php print $'.$d->name.';?></textarea>'."\n";
				}elseif($name=='image' or $name=='logo' or $name=='photo'){
					$form.= "\t".'<input class="champ" type="file" name="'.$d->name.'" id="'.$d->name.'" value="<?php print $'.$d->name.';?>"/>'."\n";
				}else{
					$form.= "\t".'<input class="champ" type="text" name="'.$d->name.'" id="'.$d->name.'" value="<?php print $'.$d->name.';?>"/>'."\n";
				}
			}
			$errlib=str_replace($table.'_',null,$d->name);
			$errlib=str_replace('_id',null,$errlib);
			$errlib=str_replace('_',' ',$errlib);
			$errlib=ucfirst($errlib);
			$form.= "\t".'<span class="erreur"><?php print $x->erreurs[\''.$errlib.'\'];?></span><br/>'."\n";
		}
		$i++;
	}
	$captchaform= '
	<table style="width:100%;border:none;">
	<tr><td><label>Code de sécurité</label></td><td>
	<img src="modules/captcha/captcha.php" /></td></tr>
	<tr><td colspan="2">
	<label>Recopier le code</label>
	<input class="champ" type="text" name="captcha"/>
	<span class="erreur"><?php print $x->erreurs["Code de sécurité"];?></span><br/>
	</td>
	</tr>
	</table>';
	
	// $form.=$captchaform;//si on veut afficher le champ captcha
	$form.='</fieldset>
	<fieldset>
		<input type="submit" name="add" class="btn btn_valider" value="Valider"/>
	</fieldset>
	</form>'."\n";

	$form.='
	</div>
	</div>
	</div>'."\n";
	//fin de la page d'insertion de données
	
//*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*//
	//la page de modification de données
	$page_modification='<div class="leftbox" id="" style="padding:0px;">
			<div class="blocTitle">MODIFIER UN '.strtoupper($table).'</div>
			<div class="blocContent" style="margin-top:5px;padding:0px;">
				<div class="krakModule">

		<div id="article-editer" class="module editeMsg" style="padding:0px;">
		<script type="text/javascript">
			<!--
			$("document").ready(function(){
				$(".wysiwyg").wysiwyg();	
				$(".wysiwyg").css({width:"720"});
				//
			});
			-->
		</script>
		'."\n";

	$page_modification.="<?php\n";
	$page_modification.='if(isset($_GET["id"]) and is_numeric($_GET["id"])){
	$id=$_GET["id"];
	
	$sql=\'select * from '.$table.'
	where '.$table.'_id="\'.$id.\'"\';
	//$req=mysql_query($sql);
	//$d=mysql_fetch_object($req);
	$stm = $db->pdo->prepare($sql);
	$stm->execute();
	$d = $stm->fetch(PDO::FETCH_OBJ);
	
	';
	$jj=0;
	$inini=array();
	foreach($fields_array as $field){
		// $val=$insert_array[$ii];
		$inini[]='$'.$field.'=stripcslashes($d->'.$field.')';
		$jj++;
	}
			
	$page_modification.='//Page générée automatiquement par krak Web Page Generator 1.0 le '. date("d-m-Y à H:i:s").'  
	$info=\'<div class="default-info">Renseignez tous les champs puis cliquez sur <b>Modifier</b> </div>\';
		'.implode(';',$inini).';
		
			
		$libelles=array('.$tablibelles.',"Code de sécurité");
		$extensionsvalides=array("jpg","gif","png","bmp");
		$imageDirectory="../images/upload-'.$table.'/";
		$x=new krakVerification();
		$x->_initialiser();
		$x->InitLibelles($libelles);
		
		if(isset($_POST["add"])){ 	
			
			extract($_POST);
			
			'.$SECUREDATA.';
			
			';
			
			if($this->haveImage($table)){
				$imagefield=$this->getImageField($table);
				$page_modification.='$'.$imagefield.'=$_FILES["'.$table.'_'.$imagefield.'"];'."\n\t";
			}
			
			// $form.='$x->verifierCaptcha($captcha,$_SESSION["captcha"],"Code de sécurité",0);'."\n\t";
			$k=0;
			while($d2=mysql_fetch_field($req2)){//print $d2->type;
				if($k>0 and (strtolower($d2->name)!=$table."_statut")){ 
					$lib=str_replace($table.'_',null,$d2->name);
					$lib=str_replace('_id',null,$lib);
					$lib=str_replace('_',' ',$lib);
					$lib=ucfirst($lib);
					$lib_pj=str_replace($table.'_',null,$d2->name);
					//si image
					
					if($lib=='Image' or $lib=='Logo' or $lib=='Photo'){
						$page_modification.= "\t".'$x->verifierPieceJointe($'.$lib_pj.',"'.$lib.'",10000000,1000000,1000000,$extensionsvalides,1);'."\n\t";
					}elseif($lib=='Email'){
							$page_modification.= "\t".'$x->verifierEmail($'.$d2->name.',"'.$lib.'",1);'."\n\t";
					}else{//si pas image
						if($d2->type=="string" or $d2->type=="blob"){
							$page_modification.= "\t".'$x->verifierChaine($'.$d2->name.',"'.$lib.'",3,1);'."\n\t";
						}elseif($d2->type=="int" or $d2->type=="float"){
							$page_modification.= "\t".'$x->verifierNombre($'.$d2->name.',"'.$lib.'",0,100000000000000000000000,1);'."\n\t\t\t";
						}
					}
				}
				$k++;
			}
		$page_modification.='
			if($x->ToutEstCorrecte()){  
				';
			if($this->haveImage($table)){
				$imagefield=$this->getImageField($table);
				$page_modification.='$'.$table.'_'.$imagefield.'=$x->nomFichier($'.$imagefield.');
				$ext=$x->extensionfichier($'.$imagefield.');
				$'.$table.'_'.$imagefield.'= \''.$table.'_'.$imagefield.'_\'.time().\'_\'.mt_rand(1000,1000000000).\'.\'.$ext;'."\n\t\t\t\t";
			}
			$ii=0;
			$upd=array();$updimagefield=array();
			foreach($fields_array as $field){
				$val=$insert_array[$ii];
				if($this->haveImage($table)){
					if($field!=$table.'_'.$imagefield){
						$upd[]=$field.' = '.$val;
					}else{
						$updimagefield[]=$field.' = '.$val;
					}
				}else{
					$upd[]=$field.' = '.$val;
				}
				$ii++;
			}
			
			// $imagefield = $this->getImageField($table);
			$page_modification.='$sql=\'UPDATE `'.$table.'` SET '.implode(',',$upd)."'; \n\t\t\t\t"; 
			$page_modification.=' 
				if(!empty($ext)){
					$sql.=\', '.implode(',',$updimagefield).' \';
				}
				$sql.=\' WHERE '.$table.'_id="\'.$_GET["id"].\'"\';
				
				
				//if(mysql_query($sql)){

				$stm = $db->pdo->prepare($sql);

				if($stm->execute()){

					$info=\'<div class="succes">Modification effectué avec succès</div>\'; 
					';
					
				if($this->haveImage($table)){
					$imagefield=$this->getImageField($table);
					$page_modification.='
					//déplacer le fichier
					//déplacer le fichier
					if(!empty($ext)){
						$x->DeplacerFichier($'.$imagefield.',$'.$table.'_'.$imagefield.',$imageDirectory);'."\n".'
						//creerMiniature($imageDirectory.\'/\'.$article_image,640,480,array(200,200,200));
						//creerMiniature($imageDirectory.\'/\'.$article_image,480,200,array(200,200,200));
						//creerMiniature2($imageDirectory.\'/\'.$article_image,$ext,480,200,array(200,200,200));
					}
					';
				}
					
					$page_modification.='
					//initialiser les variables
					'.$tabinit.';
				}
				else{
					$info=\'<div class="echec">Désolé!! enregistrement non effectué .</div>\'. mysql_error();
				}		
			}
			else
			{  
				$info=\'<div class="echec">Attemtion!! erreurs dans le formulaire</div>\'; 
			}
		}
	';
				
	$page_modification.= "?>\n";
	$page_modification.= '
	<div class="info"><?php print $info;?></div>
	<form id="form-'.$table.'" enctype="multipart/form-data" method="post" data-creator="kw-Builder">'."\n".
	'<fieldset>
	';
	$i=0;
	while($d=mysql_fetch_field($req6)){

		if($i>0 and (strtolower($d->name)!=$table."_statut")){//PERMET DE NE PAS PRENDRE EN COMPTE L'ID DE LA TABLE
			$lib=str_replace($table.'_',null,$d->name);
			$lib=str_replace('_id',null,$lib);
			$lib=str_replace('_',' ',$lib);
			$lib=ucfirst($lib);
			$page_modification.= "\t".'<label>'.$lib.'</label>'."\n";
				//test s'il s'agit du clé parent qui a migré alors utilisation d'un select
				$tab=explode('_',$d->name);
				$x=sizeof($tab);
				/*print $tab[$x-1];
				print '<pre>';
				print_r($tab);
				print '</pre>';*/
			//si c'est un table parent qui a migré
			if($tab[$x-1]=='id'){
				$table_parent=str_replace('_id',null,$d->name);
				$page_modification.= "\t".'<select class="champ" name="'.$d->name.'" id="'.$d->name.'">
					<option value=""></option>
					<?php 
					$sql=\'select * from '.$table_parent.'\';

					//$req=mysql_query($sql);
					//while($d=mysql_fetch_object($req)){

					$stm = $db->pdo->prepare($_SESSION["SQL"]);
					$stm->execute();
					$data = $stm->fetchAll(PDO::FETCH_OBJ);
					foreach($data as $d){	
						$selected = ($d->'.$table_parent.'_id==$'.$table_parent.'_id)? \' selected \' : null;
						print \'<option \'. $selected. \' value="\'.$d->'.$table_parent.'_id.\'">\'.$d->'.$table_parent.'_id.\'</option>\';
					}
					?>'."\n";
				$page_modification.='</select>'."\n";
				
			}else{//si pas clé parente
				$name=str_replace($table.'_',null,$d->name);
				if($d->type=='blob'){
					$page_modification.= "\t".'<textarea class="wysiwyg" name="'.$d->name.'" id="'.$d->name.'"><?php print $'.$d->name.';?></textarea>'."\n";
				}elseif($name=='image' or $name=='logo' or $name=='photo'){
					$page_modification.= "\t".'<input class="champ" type="file" name="'.$d->name.'" id="'.$d->name.'"/><img style="width:50px;border:none;margin-left:5px;" src="../images/upload-'.$table.'/<?php print $'.$d->name.';?>">'."\n";
				}else{
					$page_modification.= "\t".'<input class="champ" type="text" name="'.$d->name.'" id="'.$d->name.'" value="<?php print $'.$d->name.';?>"/>'."\n";
				}
			}
			$errlib=str_replace($table.'_',null,$d->name);
			$errlib=str_replace('_id',null,$errlib);
			$errlib=str_replace('_',' ',$errlib);
			$errlib=ucfirst($errlib);
			$page_modification.= "\t".'<span class="erreur"><?php print $x->erreurs[\''.$errlib.'\'];?></span><br/>'."\n";
		}
		$i++;
	}
	$captchaform= '
	<table style="width:100%;border:none;">
	<tr><td><label>Code de sécurité</label></td><td>
	<img src="modules/captcha/captcha.php" /></td></tr>
	<tr><td colspan="2">
	<label>Recopier le code</label>
	<input class="champ" type="text" name="captcha"/>
	<span class="erreur"><?php print $x->erreurs["Code de sécurité"];?></span><br/>
	</td>
	</tr>
	</table>';
	
	// $form.=$captchaform;//si on veut afficher le champ captcha
	$page_modification.='</fieldset>
	<fieldset>
		<input type="submit" name="add" class="btn btn_valider" value="Valider"/>
	</fieldset>
	</form>'."\n";

	$page_modification.='
	</div>
	</div>
	</div>'."\n";
	
	$page_modification.='<?php
	}else{
		print "Données non trouvées";
	} 
	?>';
	//fin de la page de modification de données
	
//*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*//
	//la page de detail de données
	$page_detail='<div class="leftbox" id="" style="padding:0px;">
			<div class="blocTitle">DÉTAILS D\'UN '.strtoupper($table).'</div>
			<div class="blocContent" style="margin-top:5px;padding:0px;">
				<div class="krakModule">

		<div id="article-editer" class="module editeMsg" style="padding:0px;">
		<script type="text/javascript">
			<!--
			$("document").ready(function(){
				$(".wysiwyg").wysiwyg();	
				$(".wysiwyg").css({width:"720"});
				//
			});
			-->
		</script>
		'."\n";

	$page_detail.="<?php\n";
	$page_detail.='if(isset($_GET["id"]) and is_numeric($_GET["id"])){
	$id=$_GET["id"];
	
	$sql=\'select * from '.$table.'
	where '.$table.'_id="\'.$id.\'"\';
	//$req=mysql_query($sql);
	//$d=mysql_fetch_object($req);

	$stm = $db->pdo->prepare($sql);
	$stm->execute();
	$d = $stm->fetch(PDO::FETCH_OBJ);
	';
	
	$page_detail.= "?>\n";
	$i=0;
	while($d=mysql_fetch_field($req7)){

		if($i>0 and (strtolower($d->name)!=$table."_statut")){//PERMET DE NE PAS PRENDRE EN COMPTE L'ID DE LA TABLE
			$lib=str_replace($table.'_',null,$d->name);
			$lib=str_replace('_id',null,$lib);
			$lib=str_replace('_',' ',$lib);
			$lib=ucfirst($lib);
			$page_detail.= "\t".'<label>'.$lib.'</label>'."\n";
				//test s'il s'agit du clé parent qui a migré alors utilisation d'un select
				$tab=explode('_',$d->name);
				$x=sizeof($tab);
				/*print $tab[$x-1];
				print '<pre>';
				print_r($tab);
				print '</pre>';*/
			//si c'est un table parent qui a migré
			if($tab[$x-1]=='id'){
				$table_parent=str_replace('_id',null,$d->name);
				$page_detail.= "\t".'<div>
					<?php 
					$sql=\'select * from '.$table_parent.' where '.$table_parent.'_id="\'.$d->'.$table_parent.'_id.\'"\';
					//$req=mysql_query($sql);
					//$d1=mysql_fetch_object($req);

					$stm = $db->pdo->prepare($sql);
					$stm->execute();
					$d1 = $stm->fetchAll(PDO::FETCH_OBJ);

						print \'<div>\'.$d1->'.$table_parent.'_id.\'</div>\';
					
					?>'."\n";
				$page_detail.='</div>'."\n";
				
			}else{//si pas clé parente
				$name=str_replace($table.'_',null,$d->name);
				if($name=='image' or $name=='logo' or $name=='photo'){
					$page_detail.= "\t".'<div><img src="../images/upload-'.$table.'/<?php print $d->'.$d->name.';?>" style="width:148px;"/></div>'."\n";
				}else{
					$page_detail.= "\t".'<div><?php print $d->'.$d->name.';?></div>'."\n";
				}
			}
			$errlib=str_replace($table.'_',null,$d->name);
			$errlib=str_replace('_id',null,$errlib);
			$errlib=str_replace('_',' ',$errlib);
			$errlib=ucfirst($errlib);
		}
		$i++;
	}
	
	$page_detail.="\n";

	$page_detail.='
	</div>
	</div>
	</div>'."\n";
	
	$page_detail.='<?php
	}else{
		print "Données non trouvées";
	} 
	?>';
	//fin de la page de detail de données
	
	
//*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*//
	//LA PAGE D'AFFICHAGE DE DONNÉES
	$page_affichage=null;
	$page_affichage='<div class="leftbox" id="" style="padding:0px;">
		<div class="blocTitle">LISTE DES '.strtoupper($table).'S
		<a href="printer.php?table='.$table.'" target="_blank"><img class="btn_modifier" src="images/ic/b_print.png" style="width:20px;cursor:pointer;position:relative;top:0px;float:right;margin:-5px 25px 5px 0px;color:black;font-weight:normal;" title="Imprimer"/></a>
		| <span id="btn_open_search_box" class="prettyPhoto" style="cursor:pointer;position:relative;top:0px;float:right;margin:-5px 25px 5px 0px;color:black;font-weight:normal;"><img src="images/loupe.png" style="width:15px;" title="Rechercher"/> Rechercher</span></div>
		<div class="blocContent" style="margin-top:5px;padding:0px;">
			<div class="krakModule">
	'."\n";

	$page_affichage.='<div>
	<link rel="stylesheet" href="inc/listes/css/screen.css" type="text/css" media="screen" title="default" />
	<script type="text/javascript">
	<!--
	//Cette fonction permet de cocher toutes les lignes
	function cocherTout(nbre)
	{
		$("#checkallbox").html(\'<span onClick="decocherTout(100)"><img src="images/checkbox-checked.png" style="height:30px;"/></span>\');	
		$("#checkallbox-h").html(\'<span onClick="decocherTout(100)"><img src="images/checkbox-checked.png" style="height:30px;"/></span>\');	
		for (i=1;i <=nbre;i++)
		{
			document.getElementById("cfgM_"+i).checked=true;
		}
	}
	//Cette fonction permet de décocher tout les messages		
	function decocherTout(nbre)
	{
		$("#checkallbox").html(\'<span onClick="cocherTout(100)"><img src="images/checkbox-unchecked.png" style="height:30px;"/></span>\');
		$("#checkallbox-h").html(\'<span onClick="cocherTout(100)"><img src="images/checkbox-unchecked.png" style="height:30px;"/></span>\');
		for (i=1;i <=nbre;i++)
		{
			document.getElementById("cfgM_"+i).checked=false;
		}			
	}
	-->
	</script>
	<?php
	$info=null;
	if(isset($_POST["execAction"]))
	{
		extract($_POST);
		//$req=mysql_query($_SESSION["SQL"]);//print $_SESSION["SQL"];
		//récupère les id des membres dans un tableau
		$table_id=Array();$x=-1;
		//while($data=mysql_fetch_array($req)){

		$stm = $db->pdo->prepare($_SESSION["SQL"]);
		$stm->execute();
		$datas = $stm->fetchAll(PDO::FETCH_OBJ);
		foreach($datas as $data){
			$x++;$table_id["id$x"]=$data->'.$table.'_id;}
		//pour chaque membre
		foreach($table_id as $id)
		{
			//définir la requète en fonction de l\'action sélectionnée
			switch($action){
				case "activer":$sql=\'update '.$table.' set `'.$table.'_statut`="ACTIVE" where '.$table.'_id="\'.$id.\'"\';break;
				case "desactiver":$sql=\'update '.$table.' set `'.$table.'_statut`="DESACTIVE" where '.$table.'_id="\'.$id.\'"\';break;
				case "supprimer":$sql=\'delete from '.$table.' where '.$table.'_id="\'.$id.\'"\';break;
			}
			//obtenir l\'état de la case à cocher (checkbox)
			$checkBoxValue=isset($_POST["cfgM".$id])? 1 : 0;//print $checkBoxValue;
			//si la case est cochée on exécute la requète
			if($checkBoxValue==1){

				//if(mysql_query($sql)){

				$stm = $db->pdo->prepare($sql);
				if($stm->execute()){
					$info=\'<div class="succes">Action exécutée</div>\'; 
				}
				else{
					$info=\'<div class="echec">Désolé!! Action exécutée</div>\'.mysql_error();
				}
			}
		}
	}

	print $info;
	?>
	
	
	<!-- debut form recherche -->
	<form id="form-search" method="post" data-creator="kw-Builder" style="display:none;">
		<fieldset>
			';
			
			//boucle pour lister les colonnes de la table
			while($d51=mysql_fetch_field($req51)){
				// print_r($d51);
				if($d51->name!=$table.'_id' and $d51->name!=$table.'_statut'){
					$lib=str_replace($table.'_',null,$d51->name);
					$lib=str_replace('_id',null,$lib);
					// $lib='"'.ucfirst($lib).'"';
					$lib=ucfirst($lib);
					
					//test s'il s'agit du clé parent qui a migré alors utilisation d'un select
					$tab=explode('_',$d51->name);
					$x=sizeof($tab);//debug($tab);
					
					//si c'est un table parent qui a migré
					if($tab[$x-1]=='id'){
						$table_parent=str_replace('_id',null,$d51->name);
						$page_affichage.= "\t".'<select class="champ" name="'.$d51->name.'" id="'.$d51->name.'" title="'.ucfirst($table_parent).'">
							<option value="">'.ucfirst($table_parent).'</option>
							<?php 
							$sql=\'select * from '.$table_parent.'\';
							//$req=mysql_query($sql);
							//while($d51=mysql_fetch_object($req)){
							$stm = $db->pdo->prepare($sql);
							$stm->execute();
							$data = $stm->fetchAll(PDO::FETCH_OBJ);
							foreach($data as $d51){
								$selected = ($d51->'.$table_parent.'_id==$'.$table_parent.'_id)? \' selected \' : null;
								print \'<option \'.$selected.\' value="\'.$d51->'.$table_parent.'_id.\'">\'.$d51->'.$table_parent.'_id.\'</option>\';
							}
							?>'."\n";
						$page_affichage.='</select>'."\n";
						
					}else{
						$page_affichage.='<input style="width:80px;" class="champ" type="text" name="'.$d51->name.'" id="'.$d51->name.'" placeholder="'.$lib.'" title="'.$lib.'"/>'."\n";
					}
				}
			}
			
	$page_affichage.='
			<select name="'.$table.'_statut" class="champ">
				<option value="ACTIVE">ACTIVE</option>
				<option value="DESACTIVE">DESACTIVE</option>
			</select>
			<input type="submit" name="add" class="btn btn_valider" value="Rechercher"/>
		</fieldset>
	</form>

	<!-- fin form recherche-->
	
	<!--  start product-table ..................................................................................... -->
	<form id="mainform" action="" method="post">
	<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
		<tr>
			<th class="table-header-check cbox" id="checkallbox-h" style="border:1px solid #d2d2d2;">
				<span onClick="cocherTout(100)"><img src="images/checkbox-unchecked.png" style="height:30px;"/></span>
			</th>
			<th class="table-header-options line-left"><a href="">ID</a></th>
			';
			
			//boucle pour lister les colonnes de la table
			while($d4=mysql_fetch_field($req4)){
				// print_r($d4);
				if($d4->name!=$table.'_id'){
					$lib=str_replace($table.'_',null,$d4->name);
					$lib=str_replace('_id',null,$lib);
					// $lib='"'.ucfirst($lib).'"';
					$lib=ucfirst($lib);
					
					$page_affichage.='<th class="table-header-options line-left"><a href="">'.$lib.'</a></th>'."\n";
				}
			}
			
			$page_affichage.='<th class="table-header-options line-left minwidth-4"><a href="">Action</a></th>
		</tr>
		<?php
		$sql=\'select * from '.$table.' where 1 \';
		
		$where = null; 
		';
		
		while($d52=mysql_fetch_field($req52)){
			//gestion des champ necessitant = ou LIKE %
			$page_affichage.=' if(isset($_POST[\''.$d52->name.'\']) and !empty($_POST[\''.$d52->name.'\'])){$where.=\' and '.$d52->name.'="\'.$_POST[\''.$d52->name.'\'].\'"\';}'."\n\t\t\t";
		}
		
		$page_affichage.='
		$sql.=$where;
		
		$sql.=\' order by '.$table.'_id DESC \'; 
		
		
		$nlpp=10;
		$url=\''.$table.'.php?page=gerer\';
		$x=new krakNewPaginer();
		$x->GenererSql($sql,$url,$nlpp);
		// $sql=$x->RenvoiSQL();
		$_SESSION["SQL"]=$x->RenvoiSQL();//print $_SESSION["SQL"];
		//$req=mysql_query($_SESSION["SQL"]) or die (mysql_error());$nbreligne=mysql_num_rows($req);
		$i=0;$y=0;			
		
		// $req=mysql_query($sql);$i=0;
		$stm = $db->pdo->prepare($_SESSION["SQL"]);
		$stm->execute();
		$data = $stm->fetchAll(PDO::FETCH_OBJ);
		foreach($data as $d){
		//while($d=mysql_fetch_object($req)){
		$i++;$trclass=($i%2==0)? null : \' class="alternate-row" \';
		print \'<tr id="tr-\'.$d->'.$table.'_id.\'" \'.$trclass.\'>
			<td><input type="checkbox" class="chk" name="cfgM\'.$d->'.$table.'_id .\'" id="cfgM_\'.$i .\'"/></td>
			<td><a href="\'.getPage().\'.php?page=detail&id=\'.$d->'.$table.'_id.\'">\'.$d->'.$table.'_id .\'</a></td>
			'."\n";
			//boucle pour lister les colonnes de la table
			$nbre_colonne=0;
			while($d5=mysql_fetch_field($req5)){
				if($d5->name!=$table.'_id'){
					if($d5->name==$table.'_logo' or $d5->name==$table.'_photo' or $d5->name==$table.'_image'){
						$page_affichage.="\t\t\t".'<td><img style="height:50px;" src="../images/upload-'.$table.'/\'.$d->'.$d5->name.'.\'"/></td>'."\n";
					}else{
						$page_affichage.="\t\t\t".'<td>\'.stripslashes($d->'.$d5->name.').\'</td>'."\n";
					}
					
				}
				$nbre_colonne ++;
			}
			$colspan = $nbre_colonne - 1;
			$page_affichage.="\t\t\t".'<td><a href="\'.getPage().\'.php?page=modifier&id=\'.$d->'.$table.'_id.\'"><img class="btn_modifier" src="images/btn_modifier.png" style="width:30px;" title="Modifier"/></a>
				<span style="cursor:pointer;" class="btn_suppr"><img class="btn_supprimer" id="\'.$d->'.$table.'_id.\'" data-table="'.$table.'" data-primarykey="'.$table.'_id"  data-value="\'.$d->'.$table.'_id.\'" src="images/btn_supprimer.png" style="width:;margin:3px;" title="Supprimer"/></span>	
			</td>
		</tr>\';
		}
		?>
		<tr>
			<th id="checkallbox" style="border:1px solid #d2d2d2;width:;">
				<span onClick="cocherTout(100)"><img src="images/checkbox-unchecked.png" style="height:30px;"/></span>
			</th>
			<th class="th" colspan="'.$colspan.'" style="border:1px solid #d2d2d2;text-align:center;letter-spacing:5px;">
				ACTION
			</th>
			<th class="th" colspan="2" style="border:1px solid #d2d2d2;padding:0 5px;">
				<select name="action" style="width:100px;">
					<option value="activer">ACTIVER</option>
					<option value="desactiver">DESACTIVER</option>
					<option value="supprimer">SUPPRIMER</option>
				</select>
				
				<input type="submit" class="btn btn_valider" style="margin:2px;" name="execAction" value="Appliquer à la sélection"/>
			
			</th>
		</tr>
	</table>
	</form>
	<!--  end content-table  -->
	
	<!--  start paging..................................................... -->
	<?php $x->afficherNumeros();?>
	<!--  end paging..................................................... -->
</div>';
	
	$page_affichage.='
	</div>
	</div>
	</div>'."\n";
	//fin de la page d'affichage de données
	
//*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/	

//*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*//
//LA PAGE D'IMPRESSION DES DONNÉES
$page_impression=null;
$page_impression.='
<?php
include("modules/printer/fpdf17/fpdf.php");
require_once("fonctions/fonctions.php");
connexionDB();

class PDF extends FPDF
{
	// En-tête
	function Header()
	{
		// Logo
		$this->Image("images/logo_footer.png",10,6,15);
		// Police Arial gras 15
		$this->SetFont("Arial","B",15);
		// Décalage à droite
		$this->Cell(80);
		// Titre
		$this->Cell(30,10,"LISTE DES '.strtoupper($table).'S",2,0,"C");
		// Saut de ligne
		$this->Ln(20);
	}

	// Pied de page
	function Footer()
	{
		// Positionnement à 1,5 cm du bas
		$this->SetY(-15);
		// Police Arial italique 8
		$this->SetFont("Arial","I",8);
		// Numéro de page
		$this->Cell(0,10,"Page ".$this->PageNo()."/{nb}",0,0,"R");
	}
}

// Instanciation de la classe dérivée
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont("Arial","B",12);

//LA REQUETE
$sql="select * from '.$table.'";
$req = mysql_query($sql) or die(mysql_error());$n = mysql_num_rows($req);

//l\'entete
$pdf->Cell(0,8,"",0,1);
';

$nbre_col = mysql_num_fields($req8) - 1;
$colwidth = floatval(190 / $nbre_col);

while($d8=mysql_fetch_field($req8)){
	if($d8->name!=$table.'_id'){
		$lib=str_replace($table.'_',null,$d8->name);
		$lib=str_replace('_id',null,$lib);
		$lib=ucfirst(utf8_decode($lib));
		$page_impression = $page_impression . '$pdf->Cell('.$colwidth.',8 ,"'.$lib.'", 1, 0, "C",0, "");'."\n";
	}
}

$page_impression.='
//le contenu
$pdf->SetFont("Arial","",12);
while($d=mysql_fetch_object($req)){
	// debug($d);
	$pdf->Cell(0,8,"",0,1);
	';
	
	while($d9=mysql_fetch_field($req9)){
		if($d9->name!=$table.'_id'){
			if($d9->name==$table.'_logo' or $d9->name==$table.'_photo' or $d9->name==$table.'_image'){
				$page_impression.='$pdf->Cell('.$colwidth.',8 ,"", 1, 0, "C",0, "");'."\n\t";
				// $page_impression.='$pdf->Image("../images/upload-'.$table.'/".$d->'.$d9->name.','.$colwidth.',8,15);'."\n\t";
			}else{
				$page_impression.='$pdf->Cell('.$colwidth.',8 ,utf8_decode($d->'.$d9->name.'), 1, 0, "C",0, "");'."\n\t";
			}
		}
	}

	$page_impression.='
}

//
$pdf->Output();

?>';
//fin de la page d'impression de données



//*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/	
	
	//création des fichers d'insertion de données
		$base_url=$outpath;
		if(!is_dir($base_url)){mkdir($base_url);}
		if(!is_dir($base_url.'/modules/')){mkdir($base_url.'/modules/');}
		if(!is_dir($base_url.'/modules/mod-'.$table.'/')){mkdir($base_url.'/modules/mod-'.$table.'/');}
		$file_insertion=$base_url.'/modules/mod-'.$table.'/'.$table.'-editer.php';
		$file_affichage=$base_url.'/modules/mod-'.$table.'/'.$table.'-gerer.php';
		$file_modification=$base_url.'/modules/mod-'.$table.'/'.$table.'-modifier.php';
		$file_detail=$base_url.'/modules/mod-'.$table.'/'.$table.'-detail.php';
		$file_impression=$base_url.'/modules/mod-'.$table.'/'.$table.'-impression.php';
		
		if(file_exists($file_insertion)){unlink($file_insertion);}
		$fp_i=fopen($file_insertion,'a+');
		
		if(file_exists($file_affichage)){unlink($file_affichage);}
		$fp_a=fopen($file_affichage,'a+');
		
		if(file_exists($file_modification)){unlink($file_modification);}
		$fp_m=fopen($file_modification,'a+');
		
		if(file_exists($file_detail)){unlink($file_detail);}
		$fp_d=fopen($file_detail,'a+');
		
		if(file_exists($file_impression)){unlink($file_impression);}
		$fp_imp=fopen($file_impression,'a+');
		
		$content=$form;
		if(fputs($fp_i,$content) and fputs($fp_a,$page_affichage) and fputs($fp_m,$page_modification) and fputs($fp_d,$page_detail) and fputs($fp_imp,$page_impression)){
			return true;
		}else{
			return false;
		}
		fclose($fp_i);
		fclose($fp_a);
		fclose($fp_m);
		fclose($fp_d);
		fclose($fp_imp);
		
	}
	
	function haveImage($table){
		$tab=array();
		$req=mysql_query('select * from '.$table);
		while($d=mysql_fetch_field($req)){
			$lib=strtolower(str_replace($table.'_',null,$d->name));
			$tab[]=$lib;
		}
		if(in_array('image',$tab)){
			return true;
		}elseif(in_array('logo',$tab)){
			return true;
		}elseif(in_array('photo',$tab)){
			return true;
		}elseif(in_array('picture',$tab)){
			return true;
		}else{
			return false;
		}
	}
	
	function getImageField($table){
		$tab=array();
		$req=mysql_query('select * from '.$table);
		while($d=mysql_fetch_field($req)){
			$lib=strtolower(str_replace($table.'_',null,$d->name));
			$tab[]=$lib;
		}
		if(in_array('image',$tab)){
			return 'image';
		}elseif(in_array('logo',$tab)){
			return 'logo';
		}elseif(in_array('photo',$tab)){
			return 'photo';
		}elseif(in_array('picture',$tab)){
			return 'picture';
		}else{
			return '';
		}
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
				// S'il sagit d'un fichier, on le copie simplement
				elseif($file != '..'  && $file != '.') copy ( $dir2copy.$file , $dir_paste.$file );                                       
			 }
	 
		  // On ferme $dir2copy
		  closedir($dh);
			return true;
		}
	 
	  }
	}

	public function creerFichier($filename,$content){
		$fp=fopen($filename,'a+');
		fputs($fp,$content);
		fclose($fp);
	}
	
 }
 
?>