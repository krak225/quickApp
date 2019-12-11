<?php
session_start();
require_once('fonctions/fonctions.php');
connexionDB();

$info="Accéder à l'administration";$login=null;$pass=null;
if(isset($_POST['login']))
{
	$login=kraksecurise($_POST['login']);
	$pass=kraksecurise(krakCript($_POST['pass']));
	
	if(EstAdministrateur('kw_administrateur','kw_administrateur_login',$login,'kw_administrateur_pass',$pass))
	{	
		//print '<a href="index.php">Connexion réussie cliquer ici pour administrer</a>';
		$_SESSION['admin']=strtolower($login);
		$_SESSION['administrateur']=strtolower($login);
		$_SESSION['droits']=array();
		$_SESSION['rang']=null;
		//on enregistre les droits de ce administrateur dans des variables de session.
		$sql='select * from kw_administrateur where `kw_administrateur_login`="'.$_SESSION['administrateur'].'"';
		//print $sql;
		$req=mysql_query($sql);// or die (mysql_error());
		$d=mysql_fetch_object($req);
		$_SESSION['rang']=$d->kw_administrateur_rang;
		$_SESSION['adminInfos']=$d;
		
	}else{$info='<div class="echec">Login ou Password erronné</div>';}
	
}

if(isset($_SESSION['administrateur'])){
	header('location:index.php');
	//foreach(($_SESSION['adminInfos']) as $var=>$val){print $var.':'.$val.'<br/>';}
}
else{
	header('location:AccesForm.php');
}
?>