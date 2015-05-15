<?php
include_once("model/model.php"); 
class zmienHaslo_Model extends Model 
{
	public function __construct()  
	{
		parent::__construct(); 
		

				$this->zmienHaslo();
		
		include_once("view/zmienHaslo.phtml"); 
	}
	
	public function zmienHaslo() 
	{

		if(isset($_POST['akceptuj'])) 
		{
		$result = $this->sql_query("SELECT * FROM konto WHERE Id_konta='".$_SESSION['id']."'")or die(mysql_error()); 
			
			
			if($_POST['haslo'] == "" || $_POST['nowehaslo'] == "" || $_POST['nowehaslo1'] == "" ) 
			{
					$this->redirect("index.php?con=zmienHaslo", "error", "Nie wprowadzono danych."); 
			}
			else if(md5($_POST['haslo']) != $result[0][2])
			{
				$this->redirect("index.php?con=zmienHaslo", "error", "Podano zle haslo."); 
			}
			
			else if(($_POST['nowehaslo']) != ($_POST['nowehaslo1'])) 
			{
				$this->redirect("index.php?con=zmienHaslo", "error", "Podane nowe hasla nie są takie same."); 
			}
			
			else 
			 {

					 
				 mysql_query("UPDATE konto SET Haslo='".addslashes(md5($_POST['nowehaslo']))."' WHERE Id_konta='".$_SESSION['id']."'")or die(mysql_error()) ;

				$this->redirect("index.php", "success", "Twoje dane zostały pomyślnie zmienione"); 
			}
			
		}
	}
	

}
?>