<?php
include_once("model/model.php"); 

class dziennikZdarzen_Model extends Model 
{
	public function __construct()  
	{
		parent::__construct(); 
		
		
				$this->dziennikZdarzen();
			
	 include_once("view/dziennikZdarzen.phtml");
				
		
	}
	

 
	public function dziennikZdarzen() 
	{
		if(isset($_POST['Dodaj'])) 
		{
			
			//if ( $this->isLogged())
			//{
				
				
			
				//if (($_POST['data_od'] ==""  || $_POST['data_do']=="") &($_POST['limit_miejsc']==""  ))
				//{
					//$this->redirect("index.php?con=dziennikZdarzen", "error", "Wypelnij pola");
				//}
				
				//else{
			$wynik=mysql_query("SELECT * FROM `konferencja` WHERE Data >= '".addslashes($_POST['data_od'])."' AND 
				Data <='".addslashes($_POST['data_do'])."' OR Limit_Miejsc >='".addslashes($_POST['limit_miejsc'])."'")or die(mysql_error()) ;
				
					//$this->redirect("index.php?con=dziennikZdarzen", "success", "Konferencja została dodana do bazy");
				include __DIR__ . "/../view/wyniki_dziennika.phtml";
			//	}
			
			// echo "<td bgcolor=\"ffff99\">" . $result[3][3] . "</td>";
			 
		
 
		//$this->redirect("index.php?con=dziennikZdarzen", "success", "Konferencja została dodana do bazy");
			//$wynik= $this->sql_query("SELECT * FROM konto"); 
			//$a=$wynik[0][0];
		
			
			//}
				
		
		
		}
	

		
	}
}
?>