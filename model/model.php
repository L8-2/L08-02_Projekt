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
	
	public function findAll($sql)
	{
	    $rows = array();
	    $q = mysql_query($sql);
	    while ($row = mysql_fetch_assoc($q)) {
	        $rows[] = $row;
	    }
	    
	    return $rows;
	}
	
	public function findOne($sql)
	{
		return mysql_fetch_assoc(mysql_query($sql));
	}
	
	public function findRecenzjaByOwner($idArtykul, $idRecenzent)
	{
	    return $this->findOne(sprintf("
	        SELECT *
	        FROM recenzja r
	        INNER JOIN artykul_recenzent ar ON (ar.ID_Artykul = %d AND ar.ID_Recenzent = %d)
	        WHERE r.ID_Artykul = %d
	        LIMIT 1
        ", $idArtykul, $idRecenzent, $idArtykul));
	}
	
	public function isRecenzjaAddedByOwner($idArtykul, $idRecenzent)
	{
	    return (bool) $this->findRecenzjaByOwner($idArtykul, $idRecenzent);
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
	
	public function getLoggedId()
	{
	    return isset($_SESSION['id']) ? $_SESSION['id'] : null;
	}
	
	public function isAdmin()
	{
		if($this->isLogged())
		{
			$admin = $this->sql_query("SELECT * FROM `administrator` WHERE `ID_Konto`='".$_SESSION['id']."'");
			if($admin)
				return true;
		}
		return false;
	}
	
	public function isOrganizator()
	{
	    if (!$this->isLogged()) {
	        return false;
	    }
	    
	    $id = $_SESSION['id'];
	    $q = mysql_query(sprintf("SELECT * FROM `organizator` WHERE `ID_Konto`=%d", $id));
	    
	    return (bool) mysql_num_rows($q);
	}
	
	public function isRecenzent()
	{
	    if (!$this->isLogged()) {
	        return false;
	    }
	
	    $id = $_SESSION['id'];
	    $q = mysql_query(sprintf("SELECT * FROM `recenzent` WHERE `ID_Konto`=%d", $id));
	
	    return (bool) mysql_num_rows($q);
	}
	
	public function isUczestnik($konf)
	{
	    if (!$this->isLogged()) {
	        return false;
	    }
	     
	    $id = $_SESSION['id'];
	    $q = mysql_query("SELECT * FROM uczestnik WHERE ID_Konto = '".addslashes($id)."' 
		AND ID_Konferencja = '".addslashes($konf)."' ");
	    
	    if ( mysql_num_rows($q) >0 )
		{
			
			return true;
		}
		else return false;
	}
	public function isUczestnik_accepted($konf)
	{
	    if (!$this->isLogged()) {
	        return false;
	    }
	     
	    $id = $_SESSION['id'];
	    $q = mysql_query("SELECT * FROM uczestnik WHERE ID_Konto = '".addslashes($id)."' 
		AND ID_Konferencja = '".addslashes($konf)."' AND  Zaakceptowany !='0' ");
	    
	    if ( mysql_num_rows($q) >0 )
		{
			
			return true;
		}
		else return false;
	}
	
	public function canAddRecenzja($idArtykul, $idRecenzent)
	{
	    $q = mysql_query(sprintf("SELECT 1 FROM artykul_recenzent WHERE ID_Artykul = %d AND ID_Recenzent = %d", $idArtykul, $idRecenzent));
	
	    return (bool) mysql_num_rows($q);
	}
	
	private function connectDatabase()
	{
		require_once('configuration.php');
		$this->mysql = @mysql_connect($DBASE['host'], $DBASE['username'], $DBASE['password']);
		mysql_set_charset('utf8');
	
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
