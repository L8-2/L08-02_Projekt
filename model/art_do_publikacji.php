<?php
include_once("model/model.php");

class art_do_publikacji_Model extends Model
{
	public function __construct()  
	{
		parent::__construct();
		$this->art_do_publikacji();
		include_once("view/art_do_publikacji.phtml");
	}
	
	public function art_do_publikacji()
	{	
		if(isset($_SESSION['id']))
		{	
			
			$result= $this->sql_query("SELECT a.ID_Artykul, a.Tytul, k.Nazwa, kon.Imie, kon.Nazwisko, a.Opublikowany
										 FROM artykul a
										 JOIN konferencja k on a.ID_Konferencja = k.ID_Konferencja
										 JOIN konto kon on kon.ID_Konto = a.ID_Konto
										 WHERE (a.ID_Konto = '".addslashes($_SESSION['id'])."') and a.Opublikowany='0'
										 ORDER BY a.Data_Utworzenia");
			 if(empty($result))
			 {
				 $this->redirect("index.php", "error", "Nie masz artykułów do publikacji");	
				 
			 } 
		
		
		}
		else $this->redirect("index.php?con=login", "error", "Musisz być zalogowany!");
		include __DIR__ . "/../view/art_do_publikacji.phtml";
	}
	
	
}
?>