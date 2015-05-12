<?php
include_once("model/model.php");

class login_Model extends Model
{
	public function __construct()  
	{
		parent::__construct();
		include_once("view/login.phtml");
	}
}
?>