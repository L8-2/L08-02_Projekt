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
		
		$result = $this->sql_query("SELECT k.ID_Konferencja, k.Nazwa, kon.Imie, kon.Nazwisko, k.Miejsce, k.Data, k.Koszt, k.Data_Utworzenia
									FROM konferencja k 
									join organizator o on o.ID_Organizator=k.ID_Organizator
									join konto kon on kon.ID_Konto=o.ID_Konto ORDER BY k.Data_Utworzenia DESC");

		include __DIR__ . "/../view/main.phtml";
	}
	
	
}
?>