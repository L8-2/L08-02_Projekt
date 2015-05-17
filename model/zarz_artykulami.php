<?php
include_once("model/model.php");
class zarz_artykulami_Model extends Model
{
	public $zarz_artykulami = false;
	public function __construct()
	{
		parent::__construct();
			$this->zarz_artykulami= $this->zarz_artykulami();
		include_once("view/zarz_artykulami.phtml"); 
	}
	public function zarz_artykulami()
	{	
		if(isset($_SESSION['id']))
		{
			$result = $this->sql_query("SELECT a.ID_Artykul,k.Nazwa,a.ID_Konto ,a.Tytul , a.Tresc , a.ID_Konferencja , k.Nazwa ,ko.Imie,ko.Nazwisko,a.Data_Utworzenia,a.Opublikowany  FROM artykul a ,konto ko, konferencja k ,organizator o 
			WHERE (a.ID_Konferencja = k.ID_Konferencja) AND  (o.ID_Konto = '".addslashes($_SESSION['id'])."' ) AND (ko.ID_Konto = a.ID_Konto)")
			or die(mysql_error());
			if($result)
			{
				return $result;
			}
			else
				$this->redirect("index.php?con=zarz_artykulami", "error", "Brak artykułów w twoich konferencjach");
		}
		else
			$this->redirect("index.php?con=login", "error", "Musisz się zalogować!");
	}
}
?>