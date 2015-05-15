<?php
require_once "model/model.php";

class recenzja_Model extends Model
{
	public function __construct()
	{
		parent::__construct();
		
		switch ($this->getAction()) {
			
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
				INNER JOIN (artykul_recenzent ar INNER JOIN artykul a ON a.ID_Artykul = ar.ID_Artykul) ON ar.ID_Recenzent = %d
			ORDER BY r.ID_Recenzja DESC
		', $recenzent['ID_Recenzent']);
		$recenzje = $this->sql_query($q);
		
		include __DIR__ . "/../view/recenzja_index.phtml";
	}
	
}