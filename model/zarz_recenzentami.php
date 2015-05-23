<?php
include_once("model/model.php");
class zarz_recenzentami_Model extends Model
{
	public $selected_val;
	public $selected_val2;
	public $zarz_artykulami = false;
	public $wynik = array();
	public function __construct()
	{
		parent::__construct();
			
				$this->zarz_recenzentami = $this->load();
				include_once("view/zarz_recenzentami.phtml");
				
		}
	
	
	
			

	public function load()
	{	
			if(isset($_POST['submit2']))
			{
				$selected = $_POST['decision'];
				$this->redirect("index.php?con=zarz_recenzentami_2&konf=$selected", "", "");
			}
		if(isset($_SESSION['id']))
		{
		
			$result = $this->sql_query("SELECT k.Nazwa ,k.ID_Konferencja FROM  konferencja k  RIGHT JOIN organizator o  ON k.ID_Organizator =  o.ID_Organizator
			WHERE  (o.ID_Konto = '".addslashes($_SESSION['id'])."')   ")
			or die(mysql_error());
			
				return $result;
		}
		else
			$this->redirect("index.php?con=login", "error", "Musisz się zalogować!");
			
	}
	
}
?>