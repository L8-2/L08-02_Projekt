<?php
require_once "model/model.php";

class publikacjaTematow_Model extends Model
{
	public function __construct()
	{
		parent::__construct();
		
		switch ($this->getAction()) {
			case 'aktualizuj':
			    return $this->aktualizuj();
		    default:
		    	$this->index();
		}
	}
	
	public function index()
	{
		$tematy = $this->sql_query('
		    SELECT * 
		    FROM tematy WHERE zaakceptowany=0
	    ');
		
		include __DIR__ . "/../view/publikacjaTematow.phtml";
	}
	
	public function aktualizuj()
	{
	    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	        $this->redirect("index.php?con=publikacjaTematow", "error", "Błędne żądanie.");
	    } elseif (!isset($_POST['id']) || !is_array($_POST['id'])) {
	        $this->redirect("index.php?con=publikacjaTematow", "error", "Musisz wybrać temat do zaakceptowania.");
	    }
	    
	    $ids = array();
	    foreach ($_POST['id'] as $id) {
	       $ids[] = (int) $id;        
        }
	    
		$q = mysql_query(sprintf('UPDATE `tematy` SET `Zaakceptowany`= 1 WHERE ID_Tematy IN (%s)', implode(',', $ids)));
	    
	    $this->redirect("index.php?con=publikacjaTematow", "success", "Wybrane tematy zostały zaakceptowane");
	}
}