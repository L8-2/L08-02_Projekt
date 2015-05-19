<?php

abstract class Model extends Controller
{
	protected $mysql;
	
	public function __construct()
	{
		parent::__construct();
		$this->connectDatabase();
		
		include 'view/header.phtml';
	}
	
	public function __destruct()
	{
		include __DIR__ . '/../view/footer.phtml';
	}
	
	public function sql_query($query)
	{
		$ret = array();
		$result = mysql_query($query);
		
		while ($row = mysql_fetch_array($result, MYSQL_BOTH))
			$ret[] = $row;
		
		if (!$ret)
			return false; 
		
		mysql_free_result($result);
		
		return $ret;
	}
	
	public function findOne($sql)
	{
		return mysql_fetch_assoc(mysql_query($sql));
	}
	
	public function findRecenzentByKonto($id)
	{
		$q = sprintf('SELECT * FROM recenzent WHERE ID_Konto = %d', $id);
		
		return $this->findOne($q);
	}
	
	public function isLogged()
	{
		return !empty($_SESSION['logged']);
	}
	
	public function isAdmin()
	{
		if($this->isLogged())
		{
			$admin = $this->sql_query("SELECT * FROM `administrator` WHERE `ID_Administrator`='".$_SESSION['id']."'");
			if($admin)
				return true;
		}
		return false;
	}
	
	private function connectDatabase()
	{
		require_once('configuration.php');
		$this->mysql = mysql_connect($DBASE['host'], $DBASE['username'], $DBASE['password']);
	
		if($this->mysql)
		{
			mysql_select_db($DBASE['name']);
	
			if(mysql_error())
				throw new Exception('Nie można odnaleźć bazy '.$DBASE['name']);
		}
		else
			throw new Exception('Nie można połączyć się z bazą danych.');
	}
	
	public function sendMail($email, $subject, $body)
	{
		require("configuration.php");
		require("controller/phpmailer/class.phpmailer.php");
		
		$mail = new PHPMailer();
		$mail->PluginDir = "controller/phpmailer/";
		$mail->From = $MAIL['address'];
		$mail->FromName = "E-Konferencja";
		$mail->Host = $MAIL['smtp'];
		$mail->Mailer = "smtp";
		$mail->Username = $MAIL['username']; 
		$mail->Password = $MAIL['password']; 
		$mail->SMTPAuth = true;
		$mail->SetLanguage("pl", "phpmailer/language/");
		$mail->Subject = $subject;
		$mail->MsgHTML($body);
		$mail->AddAddress($email,"Uczestnik");

		if(!$mail->Send())
			echo $mail->ErrorInfo."<br>";

		$mail->ClearAddresses();
		$mail->ClearAttachments();
	}
}
?>
