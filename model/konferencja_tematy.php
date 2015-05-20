<?php
require_once "model/model.php";

class konferencja_tematy_Model extends Model
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
	        if (!$temat) {
	            $this->setMessage('error', 'Wypełnij temat');
	        } else {
	            $q = mysql_query(sprintf(
	                "INSERT INTO konferencja_tematy (Temat, ID_Konto) VALUES('%s', %d)",
	                $temat,
	                $this->getLoggedId()
                ));
	            if (!$q) {
	                throw new RuntimeException('Nie można dodać tematu konferencji');
	            }
	            
	            $this->redirect($this->generateUrl(), "success", "Dodano temat konferencji");
	        }
	    }
	    
	    include __DIR__ . '/../view/konferencja_tematy_dodaj.phtml';
	}
}