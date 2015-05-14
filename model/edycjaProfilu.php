<?php
include_once("model/model.php"); 
class edycjaProfilu_Model extends Model 
{
	public function __construct()  
	{
		parent::__construct(); 
		

				$this->edycjaProfilu();
		
	
				
		include_once("view/edycjaProfilu.phtml"); 
	}
	
	public function edycjaProfilu() 
	{

		if(isset($_POST['akceptuj'])) 
		{
		//	$result = $this->sql_query("SELECT * FROM `konto` WHERE `login`='Id_konta'")or die(mysql_error()); 
			
			
			if($_POST['imie'] == "" || $_POST['nazwisko'] == "" || $_POST['email'] == "" || $_POST['nr_telefonu'] == "") 
					$this->redirect("index.php?con=edycjaProfilu", "error", "Nie wprowadzono danych."); 
			
			 else 
			 {
				 mysql_query("UPDATE `konto` SET `Imie`='".addslashes($_POST['imie'])."',Nazwisko='".addslashes($_POST['nazwisko'])."',
				 Email='".addslashes($_POST['email'])."',Nr_telefonu='".addslashes($_POST['nr_telefonu'])."' WHERE Id_konta='".$_SESSION['id']."'")or die(mysql_error()) ;;

				$this->redirect("index.php", "success", "Twoje dane zostały pomyślnie zmienione"); 
			}
			
		}
	}
	

}
?>