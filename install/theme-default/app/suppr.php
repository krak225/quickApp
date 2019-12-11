<?php
//Author: Armand Kouassi (krak225@gmail.com)
//Sécurité: s'assurer qu'un administrateur est connecté
session_start();
if(isset($_SESSION['admin'])){

	require_once('../fonctions/fonctions.php');
	require_once('../fonctions/fnDB.php');
	require_once('../core/Model.php');

	//UNE INSTANCE DU Model Pour l'interaction avec la Base de données
	$db = new Model();
	$pdo = $db->getPdo();

	if(isset($_POST['suppr'])){
		extract($_POST);
		$table=$_POST['table'];
		$primarykey=$_POST['primarykey'];
		$value=$_POST['value'];
		$sql='delete from '.$table.' where '.$primarykey.'="'.$value.'"';
		//print $sql;
		$stm = $db->pdo->prepare($sql);
		
		if($stm->execute()){
			print 1;
		}else{
			print 0;
		}
	}

}

?>