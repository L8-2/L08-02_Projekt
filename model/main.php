<?php
include_once("model/model.php");

class main_Model extends Model
{

	public function __construct()  
	{
		parent::__construct();
		
		$result = $this->sql_query("SELECT * FROM `konferencja` ORDER BY `Data_Utworzenia` DESC LIMIT 8");

		include_once("view/main.phtml");
	}

}
?>