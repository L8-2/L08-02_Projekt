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
			$result = array();
			$organizator = $this->sql_query("SELECT * FROM `organizator` WHERE `ID_Konto` = '".$_SESSION['id']."'");
			foreach($organizator as $o)
			{
				$konferencja = $this->sql_query("SELECT * FROM `konferencja` WHERE `ID_Konferencja` = '".$o['ID_Konferencja']."'");
				$art = array();
				$artykuly = $this->sql_query("SELECT * FROM `artykul` WHERE `ID_Konferencja` = '".$o['ID_Konferencja']."'");
				if ($artykuly)
				foreach($artykuly as $a)
				{
					$art[(int)$a['ID_Artykul']] = $a;
					$autor = $this->sql_query("SELECT * FROM `konto` WHERE `ID_Konto` = '".$a['ID_Konto']."'");
					$art[(int)$a['ID_Artykul']]['autor'] = $autor[0];
				}
				
				$result['organizator'][(int)$konferencja[0]['ID_Konferencja']] = $o;
				$result['konferencja'][(int)$konferencja[0]['ID_Konferencja']] = $konferencja[0];
				$result['artykuly'][(int)$konferencja[0]['ID_Konferencja']] = $art;
			}
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