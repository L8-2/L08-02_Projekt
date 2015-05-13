<?php
include_once("model/model.php"); 

class login_Model extends Model 
{
	public function __construct()  
	{
		parent::__construct(); 
		
		switch($this->getAction()) 
		{
			case 'logout': 
				$this->logout();
				break;
			default: 
				$this->login(); 
				break;
		}
		
		include_once("view/login.phtml"); 
	}
	
	public function login() 
	{
		if(isset($_POST['login'])) 
		{
			$result = $this->sql_query("SELECT * FROM `konto` WHERE `login`='".addslashes($_POST['username'])."' LIMIT 1"); 
			
			if($_POST['username'] == "" || $_POST['password'] == "") 
				$this->redirect("index.php?con=login", "error", "Nie wprowadzono danych."); 
			else if(!$result) 
				$this->redirect("index.php?con=login", "error", "Niepoprawna nazwa użytkownika."); 
			else if(md5($_POST['password']) != $result[0][2]) 
				$this->redirect("index.php?con=login", "error", "Niepoprawne hasło."); 
			else
			{
				$_SESSION['logged'] = true; 
					$this->redirect("index.php", "success", "Zostałeś zalogowany pomyślnie!");
			}
		}
	}
	
	public function logout() 
	{
		session_unset(); 
		$this->redirect("index.php?con=login", "info", "Zostałeś wylogowany z serwisu!");
	}
}
?>