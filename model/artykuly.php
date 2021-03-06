<?php
include_once("model/model.php"); 

class artykuly_Model extends Model 
{
	public $wynik = false;
	
	public function __construct()  
	{
		parent::__construct(); 
		
		if (!$this->isLogged()) {
		    $this->redirect($this->generateUrl(), "error", "Nie jesteś zalogowany");
		}
		
		switch ($this->getAction()) {
		    case 'pokaz':
		        $this->pokaz();
		        break;
		    default:
		        $this->artykuly();
		        break;
		}
	}
	
	public function pokaz()
	{
	    if (!isset($_GET['id'])) {
	        $this->redirect("index.php?con=recenzja", "error", "Musisz wybrać artykuł.");
	    }
	    
	    $idArt = (int) $_GET['id'];
		$artykul = $this->findOne(sprintf('SELECT * FROM artykul WHERE ID_Artykul = %d', $idArt));
		if (!$artykul) {
		    $this->redirect("index.php?con=recenzja", "error", "Wybrany artykuł nie istnieje.");
		}
		
		$konferencja = $this->findOne(sprintf('SELECT * FROM konferencja WHERE ID_Konferencja = %d', $artykul['ID_Konferencja']));
		$autor = $this->findOne(sprintf('SELECT * FROM konto WHERE ID_Konto = %d', $artykul['ID_Konto']));
		
		$type = 'user';
		if ($this->isAdmin()) {
		    $type = 'admin';
		} elseif ($this->isOrganizator()) {
		    $type = 'organizator';
		} elseif ($this->isRecenzent()) {
		    $type = 'recenzent';
		} elseif ($artykul['ID_Konto'] == $this->getLoggedId()) {
		    $type = 'autor';
		}
		
		switch ($type) {
		    case 'admin':
		    case 'organizator':
		    case 'autor':
		        $where = array(
                    'r.ID_Artykul = '.$idArt,
                    'r.Opublikowana = 1'
		        );
		        $sql = 
		        'SELECT r.*, k.Imie AS Autor_Imie, k.Nazwisko AS Autor_Nazwisko, o.Ocena
    		     FROM recenzja r
    		     INNER JOIN recenzent rc ON r.ID_Recenzent = rc.ID_Recenzent
    		     INNER JOIN konto k ON k.ID_Konto = rc.ID_Konto
		         INNER JOIN ocena o ON o.ID_Ocena = r.ID_Ocena';
		        $this->filterPokaz($where);
		        $sql .= ' WHERE '.implode(' AND ', $where);
		        
        		$recenzje = $this->findAll($sql);
        		break;
		    case 'recenzent':
		        $where = array(
		            'r.ID_Artykul = '.$idArt,
		            'r.Opublikowana = 1',
		            'k.ID_Konto = '.$this->getLoggedId()
		        );
		        $sql =
		        'SELECT r.*, k.Imie AS Autor_Imie, k.Nazwisko AS Autor_Nazwisko, o.Ocena
    		     FROM recenzja r
    		     INNER JOIN recenzent rc ON r.ID_Recenzent = rc.ID_Recenzent
    		     INNER JOIN konto k ON k.ID_Konto = rc.ID_Konto
		         INNER JOIN ocena o ON o.ID_Ocena = r.ID_Ocena';
		        $this->filterPokaz($where);
		        $sql .= ' WHERE '.implode(' AND ', $where);
		        
		        $recenzje = $this->findOne($sql);
		        if (!$recenzje) {
		            $recenzje = array();
		        } else {
		            $recenzje = array($recenzje);    
		        }
		        
		        break;
		    case 'user':
	        default:
	            $recenzje = array();
		}
		
		include __DIR__ . "/../view/artykuly_pokaz.phtml";
	}
	
	public function artykuly() 
	{		
		if (!isset($_GET['id_konferencji'])) {
		    $this->redirect($this->generateUrl(), 'error', 'Musisz wybrać konferencję');
		}
		
		$idKonf = (int) $_GET['id_konferencji'];
	    if($this->isAdmin() || $this->isOrganizator() || $this->isRecenzent())
		{
			$artykuly = $this->sql_query("SELECT Tytul,ID_Artykul
			FROM artykul
			WHERE Opublikowany!='0'
			AND ID_Konferencja = $idKonf");
		}
		else
		{
			$artykuly = $this->sql_query("SELECT Tytul,ID_Artykul
			FROM artykul
			WHERE Opublikowany!='0'
			AND ID_Konferencja = $idKonf
			AND ID_Konto = ".$this->getLoggedId()."");	
		}
		
		include __DIR__ . "/../view/artykuly.phtml";
	}
	
	public function getLoggedRecenzentId()
	{
	    $recenzent = $this->findRecenzentByKonto($this->getLoggedId());
	    
	    return $recenzent['ID_Recenzent'];
	}
	
	private function filterPokaz(array &$conds)
	{
	    if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['grupuj'])) {
	        return;
	    }
	    
	    if (!empty($_POST['ocena'])) {
	        if (!in_array($_POST['ocena'], range(-5, 5))) {
	            $this->setMessage('error', 'Błędna ocena, poprawne oceny to od -5 do 5');
	        } else {
	             $conds[] = sprintf('o.Ocena = %d', $_POST['ocena']);
	        }
	    }
	}
}
	
?>