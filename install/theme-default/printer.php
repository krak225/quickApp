<?php 
if(isset($_GET['table'])){
$table = htmlspecialchars($_GET['table']);
$file = 'modules/mod-'.$table.'/'.$table.'-impression.php';
if(file_exists($file)){
	require_once($file);
}else{
	print 'Liste introuvable';
}

//PAGE D'IMPRESSION
}
?>