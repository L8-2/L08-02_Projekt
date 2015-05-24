<?php
require_once "model/model.php";

class temat_konferencji_Model extends Model
{
	public function __construct()
	{
		parent::__construct();
		
	    if (!$this->isLogged()) {
		    $this->redirect($this->generateUrl(), "error", "Nie jesteś zalogowany");
		}
		
		switch ($this->getAction()) {
			case 'dodaj':
			    return $this->dodaj();
		    default:
		    	$this->index();
		}
	}
	
	public function index()
	{
		echo 'Strona pusta';
	}
	
	public function dodaj()
	{
	    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	        $temat = isset($_POST['temat']) ? mysql_real_escape_string($_POST['temat']) : null;
	        $arts = isset($_POST['art']) && is_array($_POST['art']) ? $_POST['art'] : null;
	        if (!$temat) {
	            $this->setMessage('error', 'Wypełnij temat');
	        } else {
	            $q = mysql_query(sprintf("INSERT INTO tematy (ID_Konto, Opis) VALUES(%d, '%s')", $this->getLoggedId(), $temat));
	            if (!$q) {
	                throw new RuntimeException('Nie można dodać tematu konferencji');
	            }
	            
	            if ($arts) {
    	            $idTemat = (int) mysql_insert_id();
    	            $sqlParts = array();
    	            foreach ($arts as $art) {
    	                if (!$art) {
    	                    continue;
    	                }
    	                
    	                $sqlParts[] = sprintf("(%d, '%s')", $idTemat, mysql_real_escape_string($art));
    	            }
    	            
    	            $q = mysql_query('INSERT INTO temat_artykulu (ID_Tematy, Temat) VALUES '.implode(',', $sqlParts));
    	            if (!$q) {
    	                throw new RuntimeException('Nie można dodać tematów artykulu');
    	            }
	            }
	            
	            $this->redirect($this->generateUrl(), "success", "Dodano temat konferencji");
	        }
	    }
	    
	    include __DIR__ . '/../view/temat_konferencji_dodaj.phtml';
	}
}