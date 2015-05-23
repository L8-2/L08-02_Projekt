<?php
require_once "model/model.php";

class artykuly_recenzje_Model extends Model
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
			
		if(isset($_POST['grupuj']))
		{
			if($_POST['grupujoceny'] == "")
			{
				switch ($type) {
					case 'admin':
					case 'organizator':
					case 'autor':
						$recenzje = $this->findAll(sprintf(
							'SELECT r.*, k.Imie AS Autor_Imie, k.Nazwisko AS Autor_Nazwisko,o.Ocena 
							 FROM recenzja r 
							 INNER JOIN recenzent rc ON r.ID_Recenzent = rc.ID_Recenzent 
							 INNER JOIN konto k ON k.ID_Konto = rc.ID_Konto 
							 LEFT OUTER JOIN ocena o ON r.ID_Ocena = o.ID_Ocena
							 WHERE o.Ocena is NULL AND
							 r.ID_Artykul = %d AND r.Opublikowana = 1',
							 $_SESSION['id_art']
						));
						break;
					case 'recenzent':
						$recenzje = $this->findOne(sprintf(
							'SELECT r.*, k.Imie AS Autor_Imie, k.Nazwisko AS Autor_Nazwisko,o.Ocena
							 FROM recenzja r
							 INNER JOIN recenzent rc ON r.ID_Recenzent = rc.ID_Recenzent 
							 INNER JOIN konto k ON k.ID_Konto = rc.ID_Konto
							 LEFT OUTER JOIN ocena o ON r.ID_Ocena = o.ID_Ocena
							 WHERE o.Ocena is NULL AND
							 r.ID_Artykul = %d AND r.Opublikowana = 1 AND k.ID_Konto = %d',
							$_SESSION['id_art'],
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
			}
			else if($_POST['grupujoceny'] < "-5" || $_POST['grupujoceny'] > "5")
			{
				switch ($type) {
					case 'admin':
					case 'organizator':
					case 'autor':
						$recenzje = $this->findAll(sprintf(
							'SELECT r.*, k.Imie AS Autor_Imie, k.Nazwisko AS Autor_Nazwisko,o.Ocena 
							 FROM recenzja r 
							 INNER JOIN recenzent rc ON r.ID_Recenzent = rc.ID_Recenzent 
							 INNER JOIN konto k ON k.ID_Konto = rc.ID_Konto 
							 LEFT OUTER JOIN ocena o ON r.ID_Ocena = o.ID_Ocena
							 WHERE o.Ocena is NULL AND
							 r.ID_Artykul = %d AND r.Opublikowana = 1',
							 $_SESSION['id_art']
						));
						$this->redirect("index.php?con=artykuly", "error", "Podaj ocene pomiędzy -5 a 5");
						break;
					case 'recenzent':
						$recenzje = $this->findOne(sprintf(
							'SELECT r.*, k.Imie AS Autor_Imie, k.Nazwisko AS Autor_Nazwisko,o.Ocena
							 FROM recenzja r
							 INNER JOIN recenzent rc ON r.ID_Recenzent = rc.ID_Recenzent 
							 INNER JOIN konto k ON k.ID_Konto = rc.ID_Konto
							 LEFT OUTER JOIN ocena o ON r.ID_Ocena = o.ID_Ocena
							 WHERE o.Ocena is NULL AND
							 r.ID_Artykul = %d AND r.Opublikowana = 1 AND k.ID_Konto = %d',
							$_SESSION['id_art'],
							$this->getLoggedId()
						));
						
						if (!$recenzje) {
							$recenzje = array();
						} else {
							$recenzje = array($recenzje);    
						}
						$this->redirect("index.php?con=artykuly", "error", "Podaj ocene pomiędzy -5 a 5");
						
						break;
					case 'user':
					default:
						$recenzje = array();
				}
			}
			else
			{
				switch ($type) {
					case 'admin':
					case 'organizator':
					case 'autor':
						$recenzje = $this->findAll(sprintf(
							'SELECT r.*, k.Imie AS Autor_Imie, k.Nazwisko AS Autor_Nazwisko,o.Ocena 
							 FROM recenzja r 
							 INNER JOIN recenzent rc ON r.ID_Recenzent = rc.ID_Recenzent 
							 INNER JOIN konto k ON k.ID_Konto = rc.ID_Konto 
							 LEFT OUTER JOIN ocena o ON r.ID_Ocena = o.ID_Ocena
							 WHERE o.Ocena = %d AND
							 r.ID_Artykul = %d AND r.Opublikowana = 1',
							 $_POST['grupujoceny'],$_SESSION['id_art']
						));
						break;
					case 'recenzent':
						$recenzje = $this->findOne(sprintf(
							'SELECT r.*, k.Imie AS Autor_Imie, k.Nazwisko AS Autor_Nazwisko,o.Ocena
							 FROM recenzja r
							 INNER JOIN recenzent rc ON r.ID_Recenzent = rc.ID_Recenzent 
							 INNER JOIN konto k ON k.ID_Konto = rc.ID_Konto
							 LEFT OUTER JOIN ocena o ON r.ID_Ocena = o.ID_Ocena
							 WHERE o.Ocena =%d AND
							 r.ID_Artykul = %d AND r.Opublikowana = 1 AND k.ID_Konto = %d',
							$_POST['grupujoceny'],$_SESSION['id_art'],
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
			}
		}
		else
		{	
		$_SESSION['id_art'] = $_GET['id'];
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
        		    'SELECT r.*, k.Imie AS Autor_Imie, k.Nazwisko AS Autor_Nazwisko,o.Ocena 
        		     FROM recenzja r 
        		     INNER JOIN recenzent rc ON r.ID_Recenzent = rc.ID_Recenzent 
        		     INNER JOIN konto k ON k.ID_Konto = rc.ID_Konto 
					 LEFT OUTER JOIN ocena o ON r.ID_Ocena = o.ID_Ocena
        		     WHERE r.ID_Artykul = %d AND r.Opublikowana = 1',
        		     $idArt
        	    ));
        		break;
		    case 'recenzent':
		        $recenzje = $this->findOne(sprintf(
		            'SELECT r.*, k.Imie AS Autor_Imie, k.Nazwisko AS Autor_Nazwisko,o.Ocena
        		     FROM recenzja r
		             INNER JOIN recenzent rc ON r.ID_Recenzent = rc.ID_Recenzent 
        		     INNER JOIN konto k ON k.ID_Konto = rc.ID_Konto
					 LEFT OUTER JOIN ocena o ON r.ID_Ocena = o.ID_Ocena
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
		}
		
		include __DIR__ . "/../view/artykuly_recenzje.phtml";
	}
}