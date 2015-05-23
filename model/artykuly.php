<?php
include_once("model/model.php"); 

class artykuly_Model extends Model 
{
	public $wynik = false;
	
	public function __construct()  
	{
		parent::__construct(); 
		
		
		$this->artykuly();
		
		
	}
	public function artykuly() 
	{		
		if($this->isAdmin() || $this->isOrganizator() || $this->isRecenzent())
		{

	
			$artykuly = $this -> sql_query("SELECT Tytul,ID_Artykul
			FROM artykul 
			WHERE Opublikowany!='0'
			AND ID_Konferencja =".addslashes($_SESSION['id_konf'])."");
			
		}
		else
		{
			
			$artykuly = $this -> sql_query("SELECT Tytul,ID_Artykul
			FROM artykul
			WHERE Opublikowany!='0'
			AND ID_Konferencja =".addslashes($_SESSION['id_konf'])."
			AND ID_Konto = ".addslashes($_SESSION['id'])."");	
			
		}
		if(isset($_POST['wroc']))
		{
			
			include __DIR__ . "/../view/artykuly.phtml";
			
		}
		else if(!isset($_POST['wroc']))
		{
			
			include __DIR__ . "/../view/artykuly.phtml";
			
		}
		
	}
	
}
	
?>