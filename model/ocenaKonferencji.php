<?php
include_once("model/model.php");

class ocenaKonferencji_Model extends Model
{
	public function __construct()  
	{
		parent::__construct();
		$this->ocenaKonferencji();
		include_once("view/konferencja.phtml");
	}
	
	public function ocenaKonferencji()
	{
		if(isset($_POST['ocen']))
		{			
			$result = $this->sql_query("SELECT `Ilosc_Ocen`,`Suma_Ocen`  FROM `konferencja` WHERE ID_Konferencja='".addslashes($_GET['id'])."'");
			$ilosc=$result[0][0];
			$suma=$result[0][1];
			
			if($_POST['ocena'] != "")
			{
				$ilosc=$ilosc+1;
				$suma=$suma+$_POST['ocena'];
				
				mysql_query("UPDATE `konferencja` SET `Ilosc_Ocen` ='".$ilosc."',`Suma_Ocen` = '".$suma."' WHERE ID_Konferencja ='".addslashes($_GET['id'])."'");
				mysql_query("UPDATE `recenzent` SET `Oceniono` ='1' WHERE ID_Konferencja ='".addslashes($_GET['id'])."'and ID_Konto = '".$_SESSION['id']."'");
				$this->redirect("index.php?con=konferencja&id=".$_GET['id'], "success", "Dodano ocene.");
				
				
				
				
				
			}	
			else $this->redirect("index.php?con=konferencja&id=".$_GET['id'], "error", "Nie wpisano oceny");						
									
									
		}	

		
		include __DIR__ . "/../view/konferencja.phtml";
	}
	
	
}
?>