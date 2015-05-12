<?php
abstract class Model extends Controller
{
	public $mysql;
	
	public function __construct()
	{
		try
		  {
            require_once('configuration.php');
			$connection = mysql_connect($DBASE['host'], $DBASE['username'], $DBASE['password']);

			if($connection)
			{
				$this->mysql = $connection;
				
				mysql_select_db($DBASE['name']);

				if(mysql_error())
					throw new Exception('Nie można odnaleźć bazy '.$DBASE['name']);
			}
			else
				throw new Exception('Nie można połączyć się z bazą danych w: '.$path);
				
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

}
?>