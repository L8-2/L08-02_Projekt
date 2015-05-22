<?php
require_once "model/model.php";

class uczestnicy_Model extends Model
{
	public function __construct()
	{
		parent::__construct();
		
		switch ($this->getAction()) {
			case 'usun':
			    return $this->usun();
			case 'accept':
				return $this->accept();
			case 'reject':
				return $this->reject();
		    default:
		    	$this->index();
		}
	}
	
	public function index()
	{
		$uczestnicy = $this->sql_query('
		    SELECT u.*, k.Imie, k.Nazwisko, kf.Nazwa AS Konferencja
		    FROM uczestnik u
		    INNER JOIN konto k ON k.ID_Konto = u.ID_Konto
		    INNER JOIN konferencja kf ON kf.ID_Konferencja = u.ID_Konferencja
		    ORDER BY u.ID_Konferencja DESC
	    ');
		
		include __DIR__ . "/../view/uczestnicy_index.phtml";
	}
	
	public function usun()
	{
	    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	        $this->redirect("index.php?con=uczestnicy", "error", "Błędne żądanie.");
	    } elseif (!isset($_POST['id']) || !is_array($_POST['id'])) {
	        $this->redirect("index.php?con=uczestnicy", "error", "Musisz wybrać uczestników do usunięcia.");
	    }
	    
	    $ids = array();
	    foreach ($_POST['id'] as $id) {
	       $ids[] = (int) $id;        
        }
	    
	    $q = mysql_query(sprintf('SELECT 1 FROM uczestnik WHERE ID_Konto IN (%s)', implode(',', $ids)));
	    if (mysql_num_rows($q) !== count($ids)) {
	        $this->redirect("index.php?con=uczestnicy", "error", "Przesłano błędne dane.");
	    }
	    
	    $q = mysql_query(sprintf('DELETE FROM uczestnik WHERE ID_Konto IN (%s)', implode(',', $ids)));
	    if (!$q) {
	        throw new RuntimeException('Wystąpił błąd przy usuwaniu uczestników');
	    }
	    
	    $this->redirect("index.php?con=uczestnicy", "success", "Usunięto wybranych uczestników");
	}
	
	public function accept()
	{
	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	        $this->redirect("index.php?con=uczestnicy", "error", "Błędne żądanie.");
	    } elseif (!isset($_POST['id']) || !is_array($_POST['id'])) {
	        $this->redirect("index.php?con=uczestnicy", "error", "Musisz wybrać uczestników do zaakceptowania.");
	    }
		$ids = array();
	    foreach ($_POST['id'] as $id) {
	       $ids[] = (int) $id;        
        }
		
	    $q = mysql_query(sprintf('SELECT 1 FROM uczestnik WHERE ID_Konto IN (%s)', implode(',', $ids)));
	    if (mysql_num_rows($q) !== count($ids)) {
	        $this->redirect("index.php?con=uczestnicy", "error", "Przesłano błędne dane.");
	    }
	    
	    $q = mysql_query(sprintf('UPDATE uczestnik SET Zaakceptowany = 1 WHERE ID_Konto IN (%s)', implode(',', $ids)));
	    if (!$q) {
	        throw new RuntimeException('Wystąpił błąd przy akceptacji uczestników');
	    }
	    
	    $this->redirect("index.php?con=uczestnicy", "success", "Zaakceptowano wybranych uczestników");
	
	}
	
		public function reject()
	{
	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	        $this->redirect("index.php?con=uczestnicy", "error", "Błędne żądanie.");
	    } elseif (!isset($_POST['id']) || !is_array($_POST['id'])) {
	        $this->redirect("index.php?con=uczestnicy", "error", "Wybierz uczestników, których chcesz odrzucić.");
	    }
		$ids = array();
	    foreach ($_POST['id'] as $id) {
	       $ids[] = (int) $id;        
        }
		
	    $q = mysql_query(sprintf('SELECT 1 FROM uczestnik WHERE ID_Konto IN (%s)', implode(',', $ids)));
	    if (mysql_num_rows($q) !== count($ids)) {
	        $this->redirect("index.php?con=uczestnicy", "error", "Przesłano błędne dane.");
	    }
	    
	    $q = mysql_query(sprintf('UPDATE uczestnik SET Zaakceptowany = 0 WHERE ID_Konto IN (%s)', implode(',', $ids)));
	    if (!$q) {
	        throw new RuntimeException('Wystąpił błąd podczas odrzucania uczestników');
	    }
	    
	    $this->redirect("index.php?con=uczestnicy", "success", "Odrzucono wybranych uczestników");
	
	}
	
}