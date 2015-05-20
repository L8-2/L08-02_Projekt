<?php
require_once "model/model.php";

class artykuly_recenzje_wzor_Model extends Model
{
	public function __construct()
	{
		parent::__construct();
		
		$this->index();
	}
	
	public function index()
	{
	    if (!isset($_GET['id'])) {
	        $this->redirect("index.php?con=recenzja", "error", "Musisz wybrać artykuł.");
	    }
	    
	    $idArt = (int) $_GET['id'];
		$artykul = $this->findOne(sprintf('SELECT * FROM artykul WHERE ID_Artykul = %d', $idArt));
		if (!$artykul) {
		    $this->redirect("index.php?con=recenzja", "error", "Wybrany artykuł nie istnieje.");
		}
		
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
        		$recenzje = $this->findAll(sprintf(
        		    'SELECT r.*, k.Imie AS Autor_Imie, k.Nazwisko AS Autor_Nazwisko 
        		     FROM recenzja r 
        		     INNER JOIN recenzent rc ON r.ID_Recenzent = rc.ID_Recenzent 
        		     INNER JOIN konto k ON k.ID_Konto = rc.ID_Konto 
        		     WHERE r.ID_Artykul = %d AND r.Opublikowana = 1',
        		     $idArt
        	    ));
        		break;
		    case 'recenzent':
		        $recenzje = $this->findOne(sprintf(
		            'SELECT r.*, k.Imie AS Autor_Imie, k.Nazwisko AS Autor_Nazwisko
        		     FROM recenzja r
		             INNER JOIN recenzent rc ON r.ID_Recenzent = rc.ID_Recenzent 
        		     INNER JOIN konto k ON k.ID_Konto = rc.ID_Konto
        		     WHERE r.ID_Artykul = %d AND r.Opublikowana = 1 AND k.ID_Konto = %d',
		            $idArt,
		            $this->getLoggedId()
		        ));
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
		
		include __DIR__ . "/../view/artykuly_recenzje_wzor.phtml";
	}
}