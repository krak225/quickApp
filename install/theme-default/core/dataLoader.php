<?php

	require_once('fonctions/fnDB.php');
	require_once('core/Model.php');

	//UNE INSTANCE DU Model Pour l'interaction avec la Base de données
	$db = new Model();
	$pdo = $db->getPdo();

	$administrateurs = $db->getAdministrateurs();

	//print_r($administrateurs);	

?>