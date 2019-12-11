<?php
class Model{
	
	
	public $pdo;
	
	public function __construct(){
		$this->pdo = getPDO();
		// debug($this->pdo);
	}
	
	public function getPdo(){
		return $this->pdo;
	}
	
	
	//
	public function getAdministrateurs(){

		$sql = 'SELECT *  FROM kw_administrateur ';

		$stm = $this->pdo->prepare($sql);

		$stm->execute();

		$data = $stm->fetchAll(PDO::FETCH_OBJ);
		
		return $data;
		
	}
	
	
}



?>