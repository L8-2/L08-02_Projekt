<?php
include_once("model/model.php"); 
class artykuly_Model extends Model 
{
	public function __construct()  
	{
		parent::__construct(); 
		
			$this -> artykuly();
			$this -> ocenianie();
			$this -> dodanieoceny();
			$this -> grupujoceny();
			$this -> grupujtematy();
		
	}
	public function grupujtematy()
	{
		if(isset($_POST['grupuj']))
		{	
			if(isset($_POST['grupujtem']) == 'wszystkie')
			{
				$artykuly = $this->sql_query("SELECT a.Tytul,r.Tresc,o.Ocena,t.Opis
				FROM artykul a 
				INNER JOIN tematy t ON a.ID_Artykul = t.Artykul_ID_Artykul
				LEFT OUTER JOIN recenzja r ON a.ID_Artykul = r.Artykul_ID_Artykul
				LEFT OUTER JOIN ocena o ON r.Ocena_ID_Oceny = o.ID_Oceny");
				
			}
			include __DIR__ . "/../view/artykuly.phtml";
		}
	}
	public function grupujoceny()
	{
		if(isset($_POST['grupuj2']))
		{
			if($_POST['grupujoceny'] == "")
			{
				$artykuly = $this->sql_query("SELECT a.Tytul,r.Tresc,o.Ocena,t.Opis
				FROM artykul a 
				INNER JOIN tematy t ON a.ID_Artykul = t.Artykul_ID_Artykul
				LEFT OUTER JOIN recenzja r ON a.ID_Artykul = r.Artykul_ID_Artykul
				LEFT OUTER JOIN ocena o ON r.Ocena_ID_Oceny = o.ID_Oceny
				WHERE o.Ocena is NULL");
				include __DIR__ . "/../view/artykuly.phtml";
			}
			else if($_POST['grupujoceny'] < "-5" || $_POST['grupujoceny'] > "5")
			{
				$artykuly = $this->sql_query("SELECT a.Tytul,r.Tresc,o.Ocena,t.Opis
				FROM artykul a 
				INNER JOIN tematy t ON a.ID_Artykul = t.Artykul_ID_Artykul
				LEFT OUTER JOIN recenzja r ON a.ID_Artykul = r.Artykul_ID_Artykul
				LEFT OUTER JOIN ocena o ON r.Ocena_ID_Oceny = o.ID_Oceny");
				$this->redirect("index.php?con=artykuly", "error", "Podaj ocene pomiędzy -5 a 5");
				include __DIR__ . "/../view/artykuly.phtml";
			}
			else 
			{
				$artykuly = $this->sql_query("SELECT a.Tytul,r.Tresc,o.Ocena,t.Opis
				FROM artykul a 
				INNER JOIN tematy t ON a.ID_Artykul = t.Artykul_ID_Artykul
				LEFT OUTER JOIN recenzja r ON a.ID_Artykul = r.Artykul_ID_Artykul
				LEFT OUTER JOIN ocena o ON r.Ocena_ID_Oceny = o.ID_Oceny
				WHERE o.Ocena =".$_POST['grupujoceny']."");
				include __DIR__ . "/../view/artykuly.phtml";
			}
		}
	}
	public function artykuly() 
	{		
		$artykuly = $this->sql_query("SELECT a.Tytul,r.Tresc,o.Ocena,t.Opis,r.ID_Recenzja
		FROM artykul a 
		INNER JOIN tematy t ON a.ID_Artykul = t.Artykul_ID_Artykul
		LEFT OUTER JOIN recenzja r ON a.ID_Artykul = r.Artykul_ID_Artykul
		LEFT OUTER JOIN ocena o ON r.Ocena_ID_Oceny = o.ID_Oceny");	
		
		
		//$idrecenzja = $this -> sql_query("SELECT ID_Recenzja FROM recenzja");
		if(isset($_POST['artykuly']))
		{	
			
			include __DIR__ . "/../view/artykuly.phtml";
		}
		if(isset($_POST['recenzja']))	
		{
			$recenzja = $this -> sql_query("SELECT Tresc FROM recenzja
			WHERE ID_Recenzja = ".$_POST['recenzj']."");
			//$this->redirect("index.php?con=artykuly", "error", "Wpisz interesujące");
			include __DIR__ . "/../view/wyswietlrecenzje.phtml";
			//include __DIR__ . "/../view/artykuly.phtml";
			
		}
		if(isset($_POST['wroc']))
		{
			include __DIR__ . "/../view/artykuly.phtml";
		}
		if(isset($_POST['ocena']))
		{		
			//if($this->isLogged())
			//{
				
			//}
			//else
			//$this->redirect("index.php?con=main", "error", "Nie jesteś zalogowany");	
		
			include __DIR__ . "/../view/ocenianie.phtml";
			if(isset($_POST['zapiszocene']))
			{
				if($_POST['ocena'] == "")
				{	
					$this->redirect("index.php?con=artykuly", "error", "Brak oceny");
				}
				else if($_POST['ocena'] < "-5" || $_POST['ocena'] > "5")
				{
					$this->redirect("index.php?con=artykuly", "error", "Podaj ocene pomiędzy -5 a 5");
				}
			
				else if($_POST['recenzja'] == "")
				{
				$this->redirect("index.php?con=artykuly", "error", "Brak recenzji");
				}
				else
				{
					mysql_query("INSERT INTO recenzja Values Tresc=".$_POST['recenzja']."");
					mysql_query("INSERT INTO ocena Values Ocena=".$_POST['ocena']."");
					$this->redirect("", "success", "Zapisano recenzje");
				}		
			}
		}		
	}	
	public function ocenianie() 
	{
		/*if(isset($_POST['ocena']))
		{		
			//if($this->isLogged())
			//{
				
			//}
			//else
			//$this->redirect("index.php?con=main", "error", "Nie jesteś zalogowany");	
		
			include __DIR__ . "/../view/ocenianie.phtml";
		}*/
	}
	public function dodanieoceny() 
	{
		
			
	}
	
	
}
	
?>