<?php
include_once("model/model.php"); 

class rejestracja_Model extends Model 
{
	public function __construct()  
	{
		parent::__construct(); 
		

				$this->rejestracja();


				
		
		include_once("view/rejestracja.phtml"); 
	}
	
	public function rejestracja() 
	{
		if(isset($_POST['rejestruj'])) 
		{
			$result = $this->sql_query("INSERT INTO Konto INSERT INTO `konto`(`Login`, `Haslo`, `Imie`, `Nazwisko`, `Email`, `Nr_Telefonu`)VALUES ('".addslashes($_POST['login'])."', '".addslashes($_POST['haslo'])."', 
			'".addslashes($_POST['imie'])."','".addslashes($_POST['nazwisko'])."','".addslashes($_POST['email'])."',
			'".addslashes($_POST['nr_telefonu'])."'"); 
			
		
			if($_POST['login'] == "" || $_POST['haslo'] == "" || $_POST['imie'] == "" || $_POST['nazwisko'] == "" ||
				$_POST['email'] == "" || $_POST['nr_telefonu'] == "")
				{
					$this->redirect("index.php?con=rejestracja", "error", "Nie wprowadzono danych."); 
				  }
			
			 else 
			 {
				$_SESSION['rejestracja'] = true; 
				$this->redirect("index.php", "success", "Utworzyłeś pomyślnie konto!"); 
			}
			
		}
	}
	

}
?>