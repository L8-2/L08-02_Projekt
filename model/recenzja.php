<?php
require_once "model/model.php";

class recenzja_Model extends Model
{
	public function __construct()
	{
		parent::__construct();
		
		switch ($this->getAction()) {
			case 'dodaj':
			    return $this->addRecenzja();
		    default:
		    	$this->index();
		}
	}
	
	public function index()
	{
		$recenzent = $this->findRecenzentByKonto($_SESSION['id']);
		$q = sprintf('
			SELECT r.*, a.Tresc as Artykul_Tytul
			FROM recenzja r
		      INNER JOIN (artykul a 
		          INNER JOIN artykul_recenzent ar ON (ar.ID_Recenzent = %d AND a.ID_Artykul = ar.ID_Artykul)
		      ) ON r.ID_Artykul = a.ID_Artykul
			ORDER BY r.ID_Recenzja DESC
		', $recenzent['ID_Recenzent']);
		$recenzje = $this->sql_query($q);
		
		include __DIR__ . "/../view/recenzja_index.phtml";
	}
	
	public function addRecenzja()
	{
	    if (!isset($_GET['id_artykul'])) {
	        $this->redirect("index.php?con=recenzja", "error", "Musisz wybrać artykuł.");
	    }
	
	    $recenzent = $this->findRecenzentByKonto($_SESSION['id']);
	    $idArtykul = (int) $_GET['id_artykul'];
	    $q = mysql_query(sprintf('SELECT 1 FROM artykul WHERE ID_Artykul = %d', $idArtykul));
	    if (!mysql_num_rows($q)) {
	        $this->redirect("index.php?con=recenzja", "error", "Wybrany artykuł nie istnieje.");
	    } elseif (!$this->isAllowedToAdd($idArtykul, $recenzent['ID_Recenzent'])) {
	    	$this->redirect("index.php?con=recenzja", "error", "Nie możesz dodać recenzji dla tego artykułu.");
	    } elseif ($this->isRecenzjaAddedBy($idArtykul, $recenzent['ID_Recenzent'])) {
	        $this->redirect("index.php?con=recenzja", "error", "Recenzja została już dodana do tego arytkułu.");
	    }
	    
	    $q = mysql_query(sprintf('SELECT 1 FROM artykul WHERE ID_Artykul = %d', $idArtykul));
	
	    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	        $tresc = isset($_POST['tresc']) ? $_POST['tresc'] : null;
	        $ocena = isset($_POST['ocena']) ? (int) $_POST['ocena'] : null;
	        $publikuj = isset($_POST['publikuj']) ? (bool) $_POST['publikuj'] : null;
	        if (!$tresc || !$ocena || $publikuj === null) {
	            $this->setMessage('error', 'Wypełnij wszystkie pola');
	        } elseif (!in_array($ocena, range(-5, 5))) {
	            $this->setMessage('error', 'Błędna ocena');
	        } else {
    	        // Dodanie oceny
    	        $q = mysql_query(sprintf("INSERT INTO ocena (Ocena, Opis) VALUES(%d, '%s')", $ocena, 'recenzja'));
    	        if (!$q) {
    	            throw new RuntimeException('Nie można dodać oceny');
    	        }
    	        $idOcena = (int) mysql_insert_id();
    	        	
    	        // Dodanie recenzji
    	        $q = mysql_query(sprintf(
            		"INSERT INTO recenzja (Tresc, ID_Recenzent, ID_Artykul, ID_Ocena, Opublikowana) VALUES('%s', %d, %d, %d, %d)",
            		mysql_real_escape_string($tresc),
            		$recenzent['ID_Recenzent'],
    	            $idArtykul,
            		$ocena,
            		$publikuj
        		));
    	        if (!$q) {
    	            throw new RuntimeException('Nie można dodać recenzji');
    	        }
    	        
    	        $this->redirect("index.php?con=recenzja", "success", "Dodano recenzję");
	        }
	    }
	
	    include __DIR__ . '/../view/recenzja_dodaj.phtml';
	}
	
	private function isAllowedToAdd($idArtykul, $idRecenzent)
	{
		$q = mysql_query(sprintf("SELECT 1 FROM artykul_recenzent WHERE ID_Artykul = %d AND ID_Recenzent = %d", $idArtykul, $idRecenzent));

		return (bool) mysql_num_rows($q);
	}
	
	private function isRecenzjaAddedBy($idArtykul, $idRecenzent)
	{
	    $q = mysql_query(sprintf("
	        SELECT 1 
	        FROM recenzja r 
	        INNER JOIN artykul_recenzent ar ON (ar.ID_Artykul = %d AND ar.ID_Recenzent = %d)
	        WHERE r.ID_Artykul = %d
        ", $idArtykul, $idRecenzent, $idArtykul));
	    
	    return (bool) mysql_num_rows($q);
	}
}