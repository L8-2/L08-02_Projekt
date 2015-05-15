	<?php
	include_once("model/model.php"); 

	class dodajAdministratora_Model extends Model 
	{
		public function __construct()  
		{
			parent::__construct(); 
			
			$this->dodajAdministratora();

			include_once("view/dodajAdministratora.phtml"); 
		}
		
		public function dodajAdministratora() 
		{
			if(isset($_POST['dodaj'])) 
			{
				if(!$this->isLogged())
					$this->redirect("index.php?con=login", "error", "Nie jesteś zalogowany");
				else if(!$this->isAdmin())
					$this->redirect("index.php", "error", "Nie masz uprawnień.");
				else if($_POST['administrator'] == "")
					$this->redirect("index.php?con=dodajAdministratora", "error", "Nie wprowadzono danych."); 
				else
				{
					$result = $this->sql_query("SELECT * FROM `konto` WHERE `login`='".addslashes($_POST['administrator'])."' LIMIT 1");
						
					if($result)
					{
						$result2 = $this->sql_query("SELECT * FROM `administrator` WHERE `ID_Administrator` = '".$result[0][0]."'");

						if(count($result2[0]) > 0)	
							$this->redirect("index.php?con=dodajAdministratora", "error", "Użytkownik jest już administratorem.");							
						else 
						{
							mysql_query("INSERT INTO `administrator` (`ID_Administrator`, `Id_konto`) VALUES ('".$result[0][0]."', '".$result[0][0]."')") or die(mysql_error());
							$this->redirect("index.php?con=dodajAdministratora", "success", "Dodałeś administratora");
						}
					}
					else
						$this->redirect("index.php?con=dodajAdministratora", "error", "Nie ma takiego konta.");
				}
			}
		}
		
	}
	?>