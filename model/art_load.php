<?php
include_once("model/model.php");

class art_load_Model extends Model
{
	public $art_load = false;
	
	public function __construct()
	{
		parent::__construct();
		

				$this->art_load = $this->load();
				include_once("view/art_load.phtml");

	}
	
	public function load()
	{
		if(isset($_GET['id']))
		{
			$result = $this->sql_query("SELECT a.ID_Artykul, a.Tytul,a.Tresc, k.Nazwa, kon.Imie, kon.Nazwisko, a.Opublikowany,a.Data_Utworzenia
										 FROM artykul a
										 JOIN konferencja k on a.ID_Konferencja = k.ID_Konferencja
										 JOIN konto kon on kon.ID_Konto = a.ID_Konto
										 WHERE (a.ID_Artykul = '".addslashes($_GET['id'])."')
										 ORDER BY a.Data_Utworzenia");
			if($result)
			{
				return $result[0];
			}
			else
				$this->redirect("index.php?con=art_load&id=".$_GET['id'], "error", "Taki artykuł nie istnieje.");
		}
		else if(!isset($_GET['ret']))
			$this->redirect("index.php?con=art_load", "error", "Nie wybrałeś żadnego artykułu!");
	}
	

	
}
?>