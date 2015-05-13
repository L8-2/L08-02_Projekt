<?php
include_once("model/model.php"); 

class rejestracja_Model extends Model 
{
	public function __construct()  
	{
		parent::__construct(); 
		

				$this->rejestruj();
				
		
		include_once("view/rejestracja.phtml"); 
	}
	
	public function rejestracja() 
	{
		if(isset($_POST['rejestracja'])) 
		{
			
			if($_POST['Login'] == "" || $_POST['Hasło'] == "" || $_POST['Imie'] == "" || $_POST['Nazwisko'] == "" 
				$_POST['Email'] == "") 
				$this->redirect("index.php?con=rejestracja", "error", "Nie wprowadzono danych."); 

				
			$result = $this->sql_query("INSERT INTO Konto VALUES login='".addslashes($_POST['login'])."', hasło='".addslashes($_POST['hasło'])."', 
			Imie='".addslashes($_POST['Imie'])."',Nazwisko='".addslashes($_POST['Nazwisko'])."',Email='".addslashes($_POST['Email'])."'
			Nr telefonu='".addslashes($_POST['Nr telefonu'])."'Data_Utworzenia='".addslashes($_POST['Data_Utworzenia'])."'"); 
			
			else
			{
				$_SESSION['rejestracja'] = true; 
				$this->redirect("index.php?con=rejestracja", "success", "Utworzyłeś pomyślnie konto!"); 
			}
		}
	}
	

}
?>