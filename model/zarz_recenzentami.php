<?php
include_once("model/model.php");
class zarz_recenzentami_Model extends Model
{
	public $selected_val;
	public $selected_val2;
	public $zarz_artykulami = false;
	public $wynik = array();
	public function __construct()
	{
		parent::__construct();
			switch($this->getAction())
			{
			case 'dodaj':
			{
				//$this->zarz_recenzentami = $this->load();
				$this->zarz_recenzentami= $this->dodaj();
				include_once("view/zarz_recenzentami.phtml"); 
			}
			case 'usun' :
			{
				$this->zarz_recenzentami= $this->usun();
				include_once("view/zarz_recenzentami.phtml"); 
			}
		default:
		{
				$this->zarz_recenzentami = $this->load();
				include_once("view/zarz_recenzentami.phtml");
				break;
		}
	}
	}
	public function dodaj()
	{
			$selected_val2=  $_POST['decision1'];
			echo $selected_val2;
			if ($selected_val2 !="Wybierz recenzenta do dodania")
			{
				if (empty($_GET['konf']))
					$selected_val=0;
				else
					$selected_val= $_GET['konf']; 
				mysql_query("INSERT INTO `recenzent` (ID_Konto , ID_Konferencja ) VALUES
				('".addslashes($selected_val2)."','".addslashes($selected_val)."')")
				or die(mysql_error());
				$this->redirect("index.php?con=zarz_recenzentami&konf=$selected_val&s1=1", "success", "Dodano recenzenta");
			}
	}
			

	public function load()
	{	
		
		
			if(isset($_POST['submit']))
				$this->redirect("index.php?con=zarz_recenzentami&konf=$s&s1=1", "error", "LAPL");
		if(isset($_POST['submit2']))
			{
			$selected_val= $_POST['decision'];
			$s=$selected_val-1;
			$this->redirect("index.php?con=zarz_recenzentami&konf=$s&s1=1", "", "");
			}
		if (empty($_GET['konf']))
			 $selected_val=0;
		else
			 $selected_val= $_GET['konf'];
			 $wynik[] = $selected_val;
		$selected_val2=0;
		if(isset($_SESSION['id']))
		{
			$result = $this->sql_query("SELECT k.Nazwa ,k.ID_Konferencja FROM  konferencja k  ,organizator o 
			WHERE  (k.ID_Organizator =  o.ID_Organizator)  AND  (o.ID_Konto = '".addslashes($_SESSION['id'])."')   ")
			or die(mysql_error());
		
				$wynik[] = $result;
				
			
			
				
		}
		else
			$this->redirect("index.php?con=login", "error", "Musisz się zalogować!");
		
		if (!(empty($_GET['s1'])))
			{ 
			$res = $this->sql_query("SELECT ko.Imie , ko.Nazwisko ,  ko.ID_Konto FROM konto ko  
			, recenzent r  WHERE (r.ID_Konferencja = '".addslashes($wynik[1][$selected_val]['ID_Konferencja'])."') 
			AND ( r.ID_Konto = ko.ID_Konto )") or die (mysql_error());
			$wynik[] = $res;
			}
			
			return $wynik;
	}
	
	public function usun()
	{
		
	}
}
?>