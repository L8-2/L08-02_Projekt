<?php
include_once("model/model.php"); 
class tworzeniekonferencji_Model extends Model 
{
	public $tematy = false;
	
	public function __construct()  
	{
		parent::__construct(); 
		
		$this->tematy = $this->tworzeniekonferencji();

		include_once("view/tworzeniekonferencji.phtml"); 
	}
	
	public function tworzeniekonferencji() 
	{
		if(isset($_POST['Dodaj'])) 
		{
			if ( $this->isLogged())
			{
				
				$data = $_POST['data'] . ' ' . $_POST['czas'];
				$data = mysql_real_escape_string($data);
				$termin = $_POST['data_'] . ' ' . $_POST['czas_'];
				$termin = mysql_real_escape_string($termin);
				$dzisiaj=date("Y-m-d H:m:s");
				$program = nl2br($_POST['program']);
				//$a =  date( 'Y-m-d H:i:s', $data );
				//$a =  date( 'Y-m-d H:i:s', $termin );
				
				if ($data <= $dzisiaj)
					$this->redirect("index.php?con=tworzeniekonferencji", "error", "Nie można dodać konferencji która już się odbyła");
			
				if ($_POST['Nazwa'] =="" ||  $_POST['miejsce'] ==""|| $_POST['limit_miejsc'] ==""|| $_POST['program'] ==""  || $_POST['koszt']=="" )
				{
					$this->redirect("index.php?con=tworzeniekonferencji", "error", "Wszystkie pola są obowiązkowe");
				}
				
				 
				mysql_query("INSERT INTO `organizator`
				(`ID_Konto`) VALUES( '".addslashes((int)$_SESSION['id'])."')")
					or die(mysql_error()) ;
					$query = mysql_query("SELECT * FROM organizator");
					$query_l = mysql_num_rows($query);
	
				mysql_query("INSERT INTO `konferencja`
				(`Nazwa`, `Data`, `Miejsce`, `Limit_Miejsc`, `Program`, `Termin_Zgloszen` , `Koszt` , `ID_Organizator`)
				VALUES
				(
				'".addslashes($_POST['Nazwa'])."'
				,'".addslashes($data)."'  
				,'".addslashes($_POST['miejsce'])."' 
				,'".addslashes($_POST['limit_miejsc'])."'
				,'".addslashes($program)."' 
				,'".addslashes($termin)."'  
				,'".addslashes($_POST['koszt'])."'
				,'".addslashes($query_l)."'
				)
							")
			or die(mysql_error()) ;
			
			$konferencja_id = mysql_insert_id(); 
			
			$query2 = mysql_query("SELECT * FROM konferencja") or die(mysql_error()) ;
			$query_2_l = mysql_num_rows($query2) or die(mysql_error()) ;
			mysql_query("UPDATE `organizator` SET 
			`ID_Konferencja` = '".addslashes($query_2_l)."'
			WHERE ID_Organizator = '".$query_2_l."'") 
			or die(mysql_error());
			
			$temat_konferencji = mysql_query("DELETE FROM `temat_konferencji` WHERE `ID_Konferencja` = '".$konferencja_id."'");
			if($_POST['zaproponuj'] == '')
				mysql_query("INSERT INTO `temat_konferencji` (`ID_Konferencja`, `ID_Tematy`) VALUES ('".$konferencja_id."', '".addslashes($_POST['temat'])."')");
			else
			{
				$t = $this->sql_query("SELECT * FROM `tematy` WHERE `Opis` = '".addslashes($_POST['zaproponuj'])."'");
				if(!$t)
				{
					mysql_query("INSERT INTO `tematy` (`Opis`) VALUES ('".addslashes($_POST['zaproponuj'])."')");
					$id = mysql_insert_id(); 
					mysql_query("INSERT INTO `temat_konferencji` (`ID_Konferencja`, `ID_Tematy`) VALUES ('".$konferencja_id."', '".$id."')");
				}
				else
					mysql_query("INSERT INTO `temat_konferencji` (`ID_Konferencja`, `ID_Tematy`) VALUES ('".$konferencja_id."', '".addslashes($t[0]['ID_Tematy'])."')");
			}
			
			 $this->redirect("index.php?con=tworzeniekonferencji", "success", "Koferencja została dodana do bazy");
				
			}
			else
				$this->redirect("index.php?con=tworzeniekonferencji","error","Musisz się zalogować ,aby dodać nową konferencję.");
		}
		else
		{
			$temat = $this->sql_query("SELECT * FROM `tematy` WHERE `Zaakceptowany` = '1'");
			return $temat;
		}
	

}
}
?>