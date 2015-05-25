<?php
include_once("model/model.php");
class dodajOrganizatora_2_Model extends Model
{
	
	public function __construct()
	{
		parent::__construct();
			
				$this->dodajOrganizatora_2 = $this->load();
				include_once("view/dodajOrganizatora_2.phtml");
	}
	public function load()
	{	
		$k= $_GET['konf'];
		
		if(isset($_POST['submit2']))
		{
			$selected = $_POST['decision'];
			mysql_query("DELETE FROM organizator WHERE (ID_Organizator = '".addslashes($selected)."')")
			or die(mysql_error());
			$this->redirect("index.php?con=dodajOrganizatora_2&konf=$k", "success", "Usunięto organizatora");
			
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
				
				mysql_query("INSERT INTO organizator (ID_Konto , ID_Konferencja ) VALUES
			('".$id."','".addslashes($k)."') ")
			or die(mysql_error());
			$tresc = "<h2>Zostałeś zarejestrowany jako organizator konferencji w aplikacji </h2><br>E-Konferencja.";
				$this->sendMail($_POST['email'], "E-Konferencje", $tresc);
			
			$this->redirect("index.php?con=dodajOrganizatora_2&konf=$k", "success", "Pomyślnie dodano organizatora");
			}
			
		}
		if(isset($_POST['submit3']))
		{
			$selected = $_POST['decision2'];
			mysql_query("INSERT INTO organizator (ID_Konto , ID_Konferencja ) VALUES
			('".addslashes($selected)."','".addslashes($k)."')")
			or die(mysql_error());
			$this->redirect("index.php?con=dodajOrganizatora_2&konf=$k", "success", "Pomyślnie dodano organizatora");
			
		}
		
		if(isset($_SESSION['id']))
		{
			$wynik['konferencja'] =  $this->sql_query("SELECT Nazwa  FROM konferencja  WHERE (ID_Konferencja = '".addslashes($k)."')")
			or die(mysql_error());
				
				
		}
		else
			$this->redirect("index.php?con=login", "error", "Musisz się zalogować!");
		
			$wynik['konto']  = $this->sql_query("SELECT * FROM konto WHERE (ID_Konto !=  '".addslashes($_SESSION['id'])."')") or die(mysql_error());
			
			
			$zap = $this->sql_query("SELECT o.ID_Konto  ,o.ID_Organizator
			FROM  organizator o  WHERE
			o.ID_Konferencja = '".addslashes($k)."'
			") or die(mysql_error());
			$i=0;
			
			foreach($zap as $za)
			{
				
				$zap1[$i] = $this->sql_query("SELECT k.ID_Konto , k.Imie ,k.Nazwisko  
			FROM  konto k  WHERE
			(k.ID_Konto = '".$za['ID_Konto']."') ") or die(mysql_error());
			$zap1[$i][0]['ID_Organizator'] = $za['ID_Organizator'];
				$i++;
				
			}
			$wynik['organizatorzy'] = $zap1;
			
			
			 
						
			 
			
			
			return $wynik;
			
	}
	}

?>