<?php
abstract class Model extends Controller
{
	public $mysql;
	
	public function __construct()
	{
		try
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
				
			include_once("view/header.phtml");
           }

        catch(Exception $e)
		{
			$this->throw_error($e);
		}
	}
	
	public function __destruct()
	{
		include_once(dirname($_SERVER['SCRIPT_FILENAME'])."/view/footer.phtml");
		mysql_close($this->mysql);
	}
	
	public function sql_query($query)
	{
		$ret = array();
		$result = mysql_query($query);
		
		while ($row = mysql_fetch_array($result, MYSQL_BOTH))
			$ret[] = $row;
		
		if(sizeof($ret) == 0)
			$ret = false; 
		
		mysql_free_result($result);
		
		return $ret;
	}
	
	public function isLogged()
	{
		return (isset($_SESSION['logged']));
	}
}
?>