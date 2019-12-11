<?php
	require_once('fonctions/fonctions.php');
	require_once('classes/krakFormCreator.php');

if(isset($_POST['db']) and !empty($_POST['db'])){
	extract($_POST);
	// $outpath=(isset($_POST['outpath']) and !empty($outpath))? $outpath.'/'.$db.'/' : 'modules/kfc-forms/'.$db.'/';
	$outpath=(isset($_POST['outpath']) and !empty($outpath))? $outpath.'/'.$db.'/' : $db.'/';
	
	$kfc=new krakFormCreator();
	// $kfc->setDB($db);
	$kfc->connectDB($db,$host,$user,$pass);
	$kfc->setPath($outpath);
	//tout
	// $table=null;
	if(!empty($table)){
		//création d'une seule table
		if($kfc->createForm($table)){
			print '<div class="succes">Le formulaire a été crée avec succès</div>';
		}else{
			print '<div class="echec">Erreur lors de la création du formulaire</div>';
		}
	}else{
		//création de toutes les tables
		copyFiles('install/theme-default/',$outpath);
		if($kfc->createAllForms()){
			//envoyer le dossier compressé à l'internaute
			// zipDir($db.'.zip', 'modules/kfc-forms/'.$db,'zip/')
			if(zipDir($db.'.zip', $db, 'zip/'))
			{
				print '<div class="succes">
				Votre application a été générée avec succès. 
				<a href="zip/'.$db.'.zip">Télécharger</a>
				</div>';
				//supprimer le dossier original et laisser le compressé
				supprimerDossier($db.'/');
			}
			else
			{
				print '<div class="succes">
				Votre application a été générée avec succès. 
				<a href="'.$db.'/">Ouvrez le dossier ici</a>
				</div>';
			}
		}else{
			print '<div class="echec">Erreur lors de la création des formulaires</div>';
		}
	}
	// unset($_POST['db']);
}else{
	print '<div class="echec">Veillez entrer une base de données</div>';
}


?>