<?php
include_once("model/model.php");
class dodajOrganizatora_Model extends Model
{
	public $zarz_artykulami = false;
	public function __construct()
	{
		parent::__construct();
			
				$this->dodajOrganizatora = $this->load();
				include_once("view/dodajOrganizatora.phtml");
	}
	public function load()
	{	
		if(isset($_POST['submit2']))
			{
				$selected = $_POST['decision'];
				$this->redirect("index.php?con=dodajOrganizatora_2&konf=$selected", "", "");
			}
		if(isset($_SESSION['id']))
		{
		
			$result = $this->sql_query("SELECT k.Nazwa ,k.ID_Konferencja FROM  konferencja k  RIGHT JOIN organizator o  ON k.ID_Konferencja =  o.ID_Konferencja
			WHERE  (o.ID_Konto = '".addslashes($_SESSION['id'])."')   ")
			or die(mysql_error());
			
				return $result;
		}
		else
			$this->redirect("index.php?con=login", "error", "Musisz się zalogować!");
			
	}
	}

?>