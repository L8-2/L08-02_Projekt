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
	$buttonRecenzje=false;
	$buttonKonferencje=false;
	$buttonArtykuly=false;
	$buttonUzytkownicy=false;
		if(isset($_POST['szukajKonferencji'])) 
		{
			
			if ( $this->isAdmin())
			{
				
				$buttonKonferencje=true;
	
					if( (($_POST['data_od']) == "" || ($_POST['data_do'])=="" ) || (($_POST['limit_miejsc'])==""))
					{
						$konferencje=mysql_query("SELECT konto.Login, konferencja.Nazwa,konferencja.Data, konferencja.Miejsce, konferencja.Limit_Miejsc, konferencja.Program, konferencja.Termin_Zgloszen,konferencja.Koszt
						FROM konferencja  LEFT JOIN organizator ON konferencja.ID_Konferencja=organizator.ID_Konferencja 
						LEFT JOIN konto ON konto.ID_Konto=organizator.ID_Konto");

					}			
					
					 if( (($_POST['data_od']) == "" || ($_POST['data_do'])=="" ) || (($_POST['limit_miejsc'])!="")) {
						
					}
			
					else
					{
						$konferencje=mysql_query("SELECT konto.Login, konferencja.Nazwa,konferencja.Data, konferencja.Miejsce, konferencja.Limit_Miejsc, konferencja.Program, konferencja.Termin_Zgloszen,konferencja.Koszt
						FROM konferencja  LEFT JOIN organizator ON konferencja.ID_Konferencja=organizator.ID_Konferencja 
						LEFT JOIN konto ON konto.ID_Konto=organizator.ID_Konto 
						WHERE (
						Data >= '".addslashes($_POST['data_od'])."' AND 
						Data <='".addslashes($_POST['data_do'])."') AND Limit_Miejsc >='".addslashes($_POST['limit_miejsc'])."'");
					}
		

				include __DIR__ . "/../view/wyniki_dziennika.phtml";

			}
			
			else 
				$this->redirect("index.php?con=dziennikZdarzen", "error", "Nie masz uprawnień");
				
		
		
		}
		
	
	

	if(isset($_POST['szukajRecenzji'])) 
		{
			
			if ( $this->isAdmin())
			{
				
				
				$buttonRecenzje=true;
	

				if(($_POST['data_od']) == "" || ($_POST['data_do'])=="" )
				{
	
	
					$recenzje=mysql_query("SELECT konto.Login, recenzja.Tresc, ocena.Ocena FROM recenzja 
					LEFT JOIN recenzent ON recenzja.ID_Recenzent=recenzent.ID_Recenzent
					LEFT JOIN konto ON recenzent.ID_Konto=konto.ID_Konto 
					LEFT JOIN ocena ON ocena.ID_Ocena=recenzja.ID_Ocena ")or die(mysql_error()) ;
							
				}
				
				else 
				{
					$recenzje=mysql_query("SELECT konto.Login, recenzja.Tresc, ocena.Ocena FROM recenzja 
					LEFT JOIN recenzent ON recenzja.ID_Recenzent=recenzent.ID_Recenzent
					LEFT JOIN konto ON recenzent.ID_Konto=konto.ID_Konto 
					LEFT JOIN ocena ON ocena.ID_Ocena=recenzja.ID_Ocena 
					WHERE (
					recenzja.Data_Utworzenia >= '".addslashes($_POST['data_od'])."' AND 
					recenzja.Data_Utworzenia <= '".addslashes($_POST['data_do'])."')")or die(mysql_error()) ;
				}
				
				
					include __DIR__ . "/../view/wyniki_dziennika.phtml";
			}
			
			
			else 
				$this->redirect("index.php?con=dziennikZdarzen", "error", "Nie masz uprawnień");
				
		
		
		}
		
		
		
			if(isset($_POST['szukajArtykul'])) 
		{
			
			if ( $this->isAdmin())
			{
				
				$buttonArtykuly=true;
	
					if((($_POST['data_od']) == "" || ($_POST['data_do'])=="" ))
					{
						$artykuly=mysql_query("SELECT konto.Login,artykul.Tytul,konferencja.Nazwa FROM  artykul, konferencja, konto 
						WHERE artykul.ID_Konferencja=konferencja.ID_Konferencja AND artykul.ID_Konto=konto.ID_Konto");

					}			
			
					else
					{
						$artykuly=mysql_query("SELECT konto.Login,artykul.Tytul,konferencja.Nazwa FROM  artykul, konferencja, konto 
						WHERE artykul.ID_Konferencja=konferencja.ID_Konferencja AND artykul.ID_Konto=konto.ID_Konto AND
						artykul.Data_Utworzenia >= '".addslashes($_POST['data_od'])."' AND 
						artykul.Data_Utworzenia <='".addslashes($_POST['data_do'])."'");
					}
		

				include __DIR__ . "/../view/wyniki_dziennika.phtml";

			}
			
			else 
				$this->redirect("index.php?con=dziennikZdarzen", "error", "Nie masz uprawnień");
		
		}
		
	if(isset($_POST['szukajUzytkownikow'])) 
		{
			
			if ( $this->isAdmin())
			{
				
				$buttonUzytkownicy=true;
				$uzytkownicy=mysql_query("SELECT * FROM konto ");
	
			
			
			
		

				include __DIR__ . "/../view/wyniki_dziennika.phtml";
			}
		
			
			else 
				$this->redirect("index.php?con=dziennikZdarzen", "error", "Nie masz uprawnień");
				
		
		}
		
		
		
		
		
	
	}
	
	
	
}
?>