<?php
include_once("model/model.php"); 

class szukaj_Model extends Model 
{
	public function __construct()  
	{
		parent::__construct(); 
		

				$this->szukaj();


	}
		
	public function szukaj() 
	{
		 if(isset($_POST['szukaj'])) 
			 

			
			
			if($_POST['szukane'] == "")
			{
				
				$this->redirect("index.php?con=szukaj", "error", "Wpisz interesujące Cię słowa i kliknij Szukaj");
			}
				else 
				{
					$_POST['szukane']=trim($_POST['szukane']);
					if($_POST['szukane'] == "")
					{
						
						$this->redirect("index.php?con=szukaj", "error", "Wpisz interesujące Cię słowa i kliknij Szukaj");
					}
				
					else	
					{	
						$szukana=addslashes($_POST['szukane']);
						$result = $this->sql_query("SELECT k.ID_Konferencja, k.Nazwa, kon.Imie, kon.Nazwisko, k.Miejsce, k.Data 
													FROM konferencja k 
													join organizator o on o.ID_Organizator=k.ID_Organizator
													join konto kon on kon.ID_Konto=o.ID_Konto WHERE k.Nazwa LIKE '%$szukana%' AND (k.Data>NOW()) ORDER BY k.Data");
					
						if(!$result)
						{
							
							$this->redirect("index.php?con=szukaj", "error", "Brak wyników ze słowami '$szukana'");
						}
						else
						{
							$znalezionych=count($result);
						
						}
						

					}
					
				}
		include __DIR__ . "/../view/wyniki.phtml";

	}
	

}
?>