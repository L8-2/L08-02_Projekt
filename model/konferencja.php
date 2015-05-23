<?php
include_once("model/model.php");

class konferencja_Model extends Model
{
	public $konferencja = false;
	
	public function __construct()
	{
		parent::__construct();
		
		switch($this->getAction())
		{
			case 'edit':
				$this->konferencja = $this->edit();
				include_once("view/edytuj_konferencje.phtml");
				break;
			case 'delete':
				$this->konferencja = $this->delete();
				include_once("view/usun_konferencje.phtml");
				break;
			case 'add':			
			$this->konferencja=$this->add();
			include_once("view/usun_konferencje.phtml");
			break;
			
			default:
				$this->konferencja = $this->load();
				include_once("view/konferencja.phtml");
				break;
		}
	}
	
	public function load()
	{
		if(isset($_GET['id']))
		{
			$_SESSION['id_konf'] = $_GET['id'];
			$result = $this->sql_query("SELECT * FROM `konferencja` WHERE `ID_Konferencja`='".addslashes($_GET['id'])."' LIMIT 1");
			if($result)
			{
				$uczestnicy = $this->sql_query("SELECT * FROM `uczestnik` WHERE `ID_Konferencja` = '".addslashes($_GET['id'])."'");
				$result[0]['Ilosc_uczestnikow'] = (count($uczestnicy));
				$organizator = $this->sql_query("SELECT * FROM `organizator` WHERE `ID_Konferencja` = '".addslashes($_GET['id'])."'");
				$organizator = $this->sql_query("SELECT * FROM `konto` WHERE `ID_Konto` = '".$organizator[0]['ID_Konto']."'");
				$result[0]['Organizator_ID_Konto'] = $organizator[0]['ID_Konto'];
				$organizator = $organizator[0]['Imie']." ".$organizator[0]['Nazwisko'];
				$result[0]['Organizator'] = $organizator;
				$artykuly = $this->sql_query("SELECT * FROM `artykul` WHERE `ID_Konferencja` = '".addslashes($_GET['id'])."'");
				$result[0]['Ilosc_artykulow'] = (count($artykuly));
				$temat = false;
				$temat_konferencji = $this->sql_query("SELECT * FROM `temat_konferencji` WHERE `ID_Konferencja` = '".addslashes($_GET['id'])."'");
				if(count($temat_konferencji[0]) > 0)
					foreach($temat_konferencji as $t)
					{
						$tematy = $this->sql_query("SELECT * FROM `tematy` WHERE `ID_Tematy` = '".$t['ID_Tematy']."' AND `Zaakceptowany` = '1'");
						$temat = $tematy[0]['Opis'];
					}
				$result[0]['Temat'] = $temat;
				if($this->isLogged())
					$result[0]['Recenzent'] = $this->sql_query("SELECT * FROM `recenzent` WHERE ID_Konto = '".$_SESSION['id']."' and ID_konferencja = '".addslashes($_GET['id'])."' and Oceniono =0");
				return $result[0];
			}
			else
				$this->redirect("index.php?con=konferencja&id=".$_GET['id'], "error", "Taka konferencja nie istnieje.");
		}
		else if(!isset($_GET['ret']))
			$this->redirect("index.php?con=konferencja", "error", "Nie wybrałeś żadnej konferencji!");
	}
	
	public function edit()
	{
		if(isset($_GET['id']))
		{
			if(!isset($_POST['edit']))
			{
				$result = $this->sql_query("SELECT * FROM `konferencja` WHERE `ID_Konferencja`='".addslashes($_GET['id'])."' LIMIT 1");
				if($result)
				{
					$organizator = $this->sql_query("SELECT * FROM `organizator` WHERE `ID_Konferencja` = '".addslashes($_GET['id'])."'");
					if(!$this->isLogged())
						$this->redirect("index.php?con=konferencja&id=".$_GET['id'], "error", "Musisz być zalogowany aby edytować konferencje.");
					else if(!$this->isAdmin() && $organizator[0]['ID_Konto'] != $_SESSION['id'])
						$this->redirect("index.php?con=konferencja&id=".$_GET['id'], "error", "Nie masz praw do edycji tej konferencji.");
					else				
					{
						$date = explode(' ', $result[0]['Data']);
						$result[0]['konferencja_dzien'] = $date[0];
						$result[0]['konferencja_czas'] = $date[1];
						$date = explode(' ', $result[0]['Termin_Zgloszen']);
						$result[0]['termin_dzien'] = $date[0];
						$result[0]['termin_czas'] = $date[1];
						
						$temat = false;
						$temat_konferencji = $this->sql_query("SELECT * FROM `temat_konferencji` WHERE `ID_Konferencja` = '".addslashes($_GET['id'])."'");
						if(count($temat_konferencji[0]) > 0)
							foreach($temat_konferencji as $t)
							{
								$tematy = $this->sql_query("SELECT * FROM `tematy` WHERE `ID_Tematy` = '".$t['ID_Tematy']."' AND `Zaakceptowany` = '1'");
								$temat = $tematy[0]['Opis'];
							}
						$result[0]['Temat'] = $temat;
						
						$tematy = $this->sql_query("SELECT * FROM `tematy` WHERE `Zaakceptowany` = '1'");
						$result[0]['tematy'] = $tematy;
						return $result[0];
					}
				}
				else
					$this->redirect("index.php?con=konferencja&id=".$_GET['id'], "error", "Taka konferencja nie istnieje.");
			}
			else
			{
				if($_POST['nazwa'] == '' || $_POST['konferencja_dzien'] == '' || $_POST['konferencja_czas'] == '' 
				|| $_POST['miejsce'] == '' || $_POST['limit'] == '' || $_POST['termin_dzien'] == '' 
				|| $_POST['termin_czas'] == '' || $_POST['koszt'] == '' || $_POST['program'] == '')
					$this->redirect("index.php?con=konferencja&act=edit&id=".$_GET['id'], "error", "Nie wprowadzono wszystkich danych!");
				else if($_POST['temat'] == '' && $_POST['zaproponuj'] == '')
					$this->redirect("index.php?con=konferencja&act=edit&id=".$_GET['id'], "error", "Nie wybrałeś ani nie zaproponowałeś tematu!");
				else if(!is_numeric($_POST['limit']))
					$this->redirect("index.php?con=konferencja&act=edit&id=".$_GET['id'], "error", "Limit miejsc musi być liczbą!");
				else if($_POST['limit'] < 0)
					$this->redirect("index.php?con=konferencja&act=edit&id=".$_GET['id'], "error", "Niepoprawna wartość dla limitu miejsc!");
				else if(!is_numeric($_POST['koszt']))
					$this->redirect("index.php?con=konferencja&act=edit&id=".$_GET['id'], "error", "Koszt musi być liczbą!");
				else if($_POST['koszt'] < 0)
					$this->redirect("index.php?con=konferencja&act=edit&id=".$_GET['id'], "error", "Niepoprawna wartość kosztu!");
				else
				{
					$data_konferencji = addslashes($_POST['konferencja_dzien']).' '.addslashes($_POST['konferencja_czas']);
					$termin_konferencji = addslashes($_POST['termin_dzien']).' '.addslashes($_POST['termin_czas']);
					mysql_query("UPDATE `konferencja` SET `Nazwa`='".addslashes($_POST['nazwa'])."', `Data`='".$data_konferencji."',
					`Miejsce`='".addslashes($_POST['miejsce'])."', `Limit_Miejsc`='".addslashes($_POST['limit'])."', 
					`Program`='".addslashes($_POST['program'])."', `Termin_Zgloszen`='".$termin_konferencji."', `Koszt`='".addslashes($_POST['koszt'])."'
					WHERE ID_Konferencja='".addslashes($_GET['id'])."'");
					
					$temat_konferencji = mysql_query("DELETE FROM `temat_konferencji` WHERE `ID_Konferencja` = '".addslashes($_GET['id'])."'");
					if($_POST['zaproponuj'] == '')
						mysql_query("INSERT INTO `temat_konferencji` (`ID_Konferencja`, `ID_Tematy`) VALUES ('".addslashes($_GET['id'])."', '".addslashes($_POST['temat'])."')");
					else
					{
						mysql_query("INSERT INTO `tematy` (`Opis`) VALUES ('".addslashes($_POST['zaproponuj'])."')");
						$id = mysql_insert_id(); 
						mysql_query("INSERT INTO `temat_konferencji` (`ID_Konferencja`, `ID_Tematy`) VALUES ('".addslashes($_GET['id'])."', '".$id."')");
					}
					
					$this->redirect("index.php?con=konferencja&act=edit&id=".$_GET['id'], "success", "Konferencja została zmieniona pomyślnie.");
				}
			}
		}
		else if(!isset($_GET['ret']))
			$this->redirect("index.php?con=konferencja", "error", "Nie wybrałeś żadnej konferencji!");
	}
	
	public function delete()
	{
		if(isset($_GET['id']))
		{
			$result = $this->sql_query("SELECT * FROM `konferencja` WHERE `ID_Konferencja`='".addslashes($_GET['id'])."' LIMIT 1");
			if($result)
			{
				$organizator = $this->sql_query("SELECT * FROM `organizator` WHERE `ID_Konferencja` = '".addslashes($_GET['id'])."'");
				if(!$this->isLogged())
					$this->redirect("index.php?con=konferencja&id=".$_GET['id'], "error", "Musisz być zalogowany aby usunąć konferencje.");
				else if(!$this->isAdmin() && $organizator[0]['ID_Konto'] != $_SESSION['id'])
					$this->redirect("index.php?con=konferencja&id=".$_GET['id'], "error", "Nie masz praw do usunięcia tej konferencji.");
				else				
				{
					if(isset($_POST['delete']))
					{
						mysql_query("DELETE FROM `konferencja` WHERE `ID_Konferencja` = '".addslashes($_GET['id'])."'");
						$this->redirect("index.php", "error", "Konferencja została usunięta.");
					}
					else if(isset($_POST['back']))
						$this->redirect("index.php?con=konferencja&id=".$_GET['id'], "info", "Operacja usuwania przerwana.");
				}
			}
		}
		else
			$this->redirect("index.php?con=konferencja", "error", "Nie wybrałeś żadnej konferencji!");
	}
	
	
	
	public function add()
	{
		if(isset($_GET['id']))
		{
			if(!isset($_POST['add']))
			{
			
				$limit_miejsc=$this->sql_query("SELECT * FROM `uczestnik` WHERE ID_KONTO='".$_SESSION['id']."' 
				AND ID_Konferencja ='".addslashes($_GET['id'])."'");
				
				if(!$limit_miejsc)
				{
				$limit_miejsc1=mysql_query("INSERT INTO `uczestnik`( ID_Konto ,  ID_Konferencja ,  Zaakceptowany ) VALUES 
				('".$_SESSION['id']."', '".addslashes($_GET['id'])."',0)");
				$nazwa = $this->sql_query("SELECT Nazwa , Data FROM konferencja WHERE ID_Konferencja =  '".addslashes($_GET['id'])."'");
				$tresc = "<h2>Zostałeś zapisany na konferencje : ".$nazwa['nazwa']."<br> Data : ".$nazwa['data']."</h2><br>Masz pytania? - Napisz do nas:<br>E-mail: e-konferencja@wp.pl <br>Lub zadzwoń: <br>Nr. telefonu: 17555111";
				$this->sendMail($_POST['email'], "E-Konferencje", $tresc);
				$this->redirect("index.php?con=konferencja&add", "success", "Dolaczyłeś do konferencji");
				
				}
				
				else		
				
			$this->redirect("index.php?con=konferencja&add", "error", "Bierzesz już udział w tej konferencji");
			}
		}
	}
	
	
	
	
	
	
	
	
}
?>