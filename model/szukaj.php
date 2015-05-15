<?php
include_once("model/model.php"); 

class szukaj_Model extends Model 
{
	public function __construct()  
	{
		parent::__construct(); 
		

				$this->szukaj();


				
		
		include_once("view/wyniki.phtml"); 
	}
		
	public function szukaj() 
	{
		 if(isset($_POST['szukaj'])) 
			 

			
			
			if($_POST['szukane'] == "")
			{
				$_SESSION['szukane'] = "";
				$this->redirect("index.php?con=szukaj", "error", "Wpisz interesujące Cię słowa i kliknij Szukaj");
			}
				else 
				{
					$_POST['szukane']=trim($_POST['szukane']);
					if($_POST['szukane'] == "")
					{
						$_SESSION['szukane'] = "";
						$this->redirect("index.php?con=szukaj", "error", "Wpisz interesujące Cię słowa i kliknij Szukaj");
					}
				
					else	
					{	
						$szukana=addslashes($_POST['szukane']);
						$result = $this->sql_query("SELECT * FROM `konferencja` WHERE `Nazwa` LIKE '%$szukana%'");
					
						if(!$result)
						{
							$_SESSION['szukane'] = "";
							$this->redirect("index.php?con=szukaj", "error", "Brak wyników ze słowem '$szukana'");
						}
						else
						{
							$_SESSION['znalezionych']=count($result);
							$_SESSION['szukane'] = $_POST['szukane'];
							
							
							
							
						}
						

					}
					
				}

	}
	

}
?>