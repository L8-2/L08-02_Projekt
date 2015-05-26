<?php
include_once("model/model.php");
class zarz_recenzentami_2_Model extends Model
{
	public $wynik = array();
	public function __construct()
	{
		parent::__construct();
			
			
		
				$this->zarz_recenzentami_2 = $this->load();
				include_once("view/zarz_recenzentami_2.phtml");
			
		
	
	}
	
			

	public function load()
	{	
		
		$k= $_GET['konf'];
		if(isset($_POST['submit2']))
		{
			$selected = $_POST['decision'];
			mysql_query("DELETE FROM recenzent WHERE (ID_Recenzent = '".addslashes($selected)."')")
			or die(mysql_error());
			$this->redirect("index.php?con=zarz_recenzentami_2&konf=$k", "success", "Usunięto recenzenta");
			
		}
		if(isset($_POST['submit4']))
		{
			$result = $this->sql_query("SELECT * FROM `konto` WHERE `login`='".addslashes($_POST['login'])."' LIMIT 1"); 
	
			if($_POST['login'] == "" || $_POST['haslo'] == "" || $_POST['imie'] == "" || $_POST['nazwisko'] == "" ||
				$_POST['email'] == "" || $_POST['nr_telefonu'] == "")
					$this->redirect("index.php?con=zarz_recenzentami_2&konf=$k", "error", "Nie wprowadzono danych."); 
			else if($result) 
				$this->redirect("index.php?con=zarz_recenzentami_2&konf=$k", "error", "Takie konto już istnieje."); 
			else if(strlen($_POST['imie']) > 20 || !preg_match('@^[a-zA-Z]{3,20}$@', $_POST['imie']))
				$this->redirect("index.php?con=rejestracja", "error", "Podane imię jest nieprawidłowe!"); 
			else if(strlen($_POST['nazwisko']) > 40 || !preg_match('@^[a-zA-Z]{2,40}$@', $_POST['nazwisko']))
				$this->redirect("index.php?con=rejestracja", "error", "Podane nazwisko jest nieprawidłowe!");
			else if(strlen($_POST['email']) > 60 || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
				$this->redirect("index.php?con=rejestracja", "error", "Adres e-mail jest nieprawidłowy!");
			else if(strlen($_POST['nr_telefonu']) > 20 || !preg_match('@^[0-9]{6,20}$@', $_POST['nr_telefonu']))
				$this->redirect("index.php?con=rejestracja", "error", "Numer telefonu jest nieprawidłowy!");
			else 
			{
				 mysql_query("INSERT INTO `konto` (`Login`, `Haslo`, `Imie`, `Nazwisko`, `Email`, `Nr_Telefonu`)
			  VALUES ('".addslashes($_POST['login'])."', md5('".addslashes($_POST['haslo'])."'), 
			'".addslashes($_POST['imie'])."','".addslashes($_POST['nazwisko'])."','".addslashes($_POST['email'])."',
			'".addslashes($_POST['nr_telefonu'])."')")or die(mysql_error()) ;
				
				$id = mysql_insert_id(); 
				
				mysql_query("INSERT INTO recenzent (ID_Konto , ID_Konferencja ) VALUES
			('".$id."','".addslashes($k)."') ")
			or die(mysql_error());
			$tresc = "<h2>Zostałeś zarejestrowany w aplikacji </h2><br>E-Konferencja.";
				$this->sendMail($_POST['email'], "E-Konferencje", $tresc);
			
			$this->redirect("index.php?con=zarz_recenzentami_2&konf=$k", "success", "Pomyślnie dodano recenzenta");
			}
			
		}
		if(isset($_POST['submit3']))
		{
			$selected = $_POST['decision2'];
			$recenzent = $this->sql_query("SELECT * FROM `recenzent` WHERE ID_Konto = '".addslashes($selected)."' AND `ID_Konferencja` = '".addslashes($k)."'");
			if($recenzent)
				$this->redirect("index.php?con=zarz_recenzentami_2&konf=$k", "error", "Wybrany użytkownik już jest recenzentem danej konferencji!");
			else
			{
				mysql_query("INSERT INTO recenzent (ID_Konto , ID_Konferencja ) VALUES ('".addslashes($selected)."','".addslashes($k)."')") or die(mysql_error());
				$this->redirect("index.php?con=zarz_recenzentami_2&konf=$k", "success", "Pomyślnie dodano recenzenta");
			}	
		}
		
		if(isset($_SESSION['id']))
		{
			$wynik[] =  $this->sql_query("SELECT k.Nazwa  FROM konferencja k WHERE (k.ID_Konferencja = '".addslashes($k)."')")
			or die(mysql_error());
				
				
		}
		else
			$this->redirect("index.php?con=login", "error", "Musisz się zalogować!");
		
			$res = $this->sql_query("SELECT * FROM konto WHERE (ID_Konto !=  '".addslashes($_SESSION['id'])."')") or die(mysql_error());
			
			$wynik[] = $res; 
			$res = $this->sql_query("SELECT r.ID_Recenzent ,ko.Imie , ko.Nazwisko ,  ko.ID_Konto FROM konto ko , recenzent r  WHERE (r.ID_Konferencja = '".addslashes($k)."') 
			AND ( r.ID_Konto = ko.ID_Konto )") ;
						
			$wynik[] = $res;
			
			
			return $wynik;
	
	}
	public function usun()
	{
			$this->redirect("index.php?con=login", "error", "Musisz się zalogować!");	
	}
}
?>