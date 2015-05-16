<?php
include_once("model/model.php"); 
class artykuly_Model extends Model 
{
	public function __construct()  
	{
		parent::__construct(); 
		
			$this -> artykuly();
		
		
	}
	public function artykuly() 
	{		
		if(isset($_POST['artykuly']))
		{	
	
			$artykuly = $this->sql_query("SELECT a.Tytul,r.Tresc,o.Ocena,t.Opis
			FROM artykul a 
			INNER JOIN tematy t ON a.ID_Artykul = t.Artykul_ID_Artykul
			LEFT OUTER JOIN recenzja r ON a.ID_Artykul = r.Artykul_ID_Artykul
			LEFT OUTER JOIN ocena o ON r.Ocena_ID_Oceny = o.ID_Oceny");
			

			include_once("view/artykuly.phtml"); 
		
		}
	}
}
	
?>