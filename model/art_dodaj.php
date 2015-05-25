<?php
include_once("model/model.php"); 

class art_dodaj_Model extends Model 
{
	public $wynik = false;
	
	public function __construct()  
	{
		parent::__construct(); 
		
		if (!$this->isLogged()) {
		    $this->redirect($this->generateUrl(), "error", "Nie jesteś zalogowany");
		}
					$this->dodaj();

	}
	public function dodaj(){
		
		
		
		if (!isset($_GET['id_konferencji'])) {
		    $this->redirect($this->generateUrl(), 'error', 'Musisz wybrać konferencję');
		}
		   $autor = $this->getLoggedId();
			$artykulForm = $_POST;
	        $tresc = isset($_POST['tresc']) ? $_POST['tresc'] : null;
	        $tytul = isset($_POST['tytul']) ? $_POST['tytul'] : null;

		
		$idKonf = (int) $_GET['id_konferencji'];
		
		
		
		if(isset($_POST['dodaj']))
		{
			
			
	    if($this->isAdmin() || $this->isOrganizator() || $this->isRecenzent()|| $this->isUczestnik())
    	        $q = mysql_query(sprintf(
            		"INSERT INTO artykul (ID_Konferencja, ID_Konto, Tytul, Tresc, Opublikowany) VALUES(%d, %d, '%s', '%s',0)",
            		$idKonf,
					$autor,
					mysql_real_escape_string($tytul),
					mysql_real_escape_string($tresc)
        		));
		$this->redirect("index.php?", "success", "Dodałeś artykuł"); 

		}
		else
		{
			$artykuly = $this->sql_query("SELECT Tytul,ID_Artykul
			FROM artykul
			WHERE Opublikowany!='0'
			AND ID_Konferencja = $idKonf
			AND ID_Konto = ".$this->getLoggedId()."");	
		}
		
		include __DIR__ . "/../view/art_dodaj.phtml";
	}
		

}
	
?>