<?php
include_once("model/model.php");

class main_Model extends Model
{
	public function __construct()  
	{
		parent::__construct();
		$this->main();
		include_once("view/main.phtml");
	}
	
	public function main()
	{
		$result = $this->sql_query("SELECT * FROM `konferencja` ORDER BY `Data_Utworzenia` DESC");
		return $result;
	}
	
	
}
?>