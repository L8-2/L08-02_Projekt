<?php
include_once("model/model.php"); 

class dziennikZdarzen_Model extends Model 
{
	public function __construct()  
	{
		parent::__construct(); 
		
		
				$this->dziennikZdarzen();
			
	 include_once("view/dziennikZdarzen.phtml");

	}
	

 
	public function dziennikZdarzen() 
	{
		
						$buttonSzukajKonf=0;
						$buttonSzukajRec=0;
		if(isset($_POST['szukaj'])) 
		{
			
			if ( $this->isAdmin())
			{
				
				
	
					
		/*	$konferencje=mysql_query("SELECT * FROM konferencja WHERE 
			Data >= '".addslashes($_POST['data_od'])."' AND 
			Data <='".addslashes($_POST['data_do'])."' OR Limit_Miejsc >='".addslashes($_POST['limit_miejsc'])."'")or die(mysql_error()) ;*/
				
				$konferencje=mysql_query("SELECT konto.Login, konferencja.Nazwa,konferencja.Data, konferencja.Miejsce, konferencja.Limit_Miejsc, konferencja.Program, konferencja.Termin_Zgloszen,konferencja.Koszt
				FROM konferencja LEFT JOIN organizator ON konferencja.ID_Konferencja=organizator.ID_Konferencja 
				LEFT JOIN konto ON konto.ID_Konto=organizator.ID_Konto");
				
					$recenzje=mysql_query("SELECT konto.Login, recenzja.Tresc, ocena.Ocena FROM recenzja 
							LEFT JOIN recenzent ON recenzja.ID_Recenzent=recenzent.ID_Recenzent
							LEFT JOIN konto ON recenzent.ID_Konto=konto.ID_Konto 
							LEFT JOIN ocena ON ocena.ID_Ocena=recenzja.ID_Ocena")or die(mysql_error()) ;
							


				include __DIR__ . "/../view/wyniki_dziennika.phtml";

			}
			
			else 
				$this->redirect("index.php?con=dziennikZdarzen", "error", "Nie masz uprawnień");
				
		
		
		}
		
	}
}
?>