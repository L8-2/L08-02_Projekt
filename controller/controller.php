<?php
class Controller
{
	public function __construct()
    {
	try
		{
			$model = (isset($_GET['con'])?$_GET['con']:'main');
			$path = 'model/'.$model.'.php';
			$model .= "_Model"; 
			
			if(is_file($path))
			{
				include_once($path);
				$this->model = new $model;
			}
			else
				throw new Exception('Nie można otworzyć pliku modelu: '.$path);
		}
        catch(Exception $e)
		{
			$this->throw_error($e);
        }
    }
	public function throw_error($e)
	{
		try
		{
			$str = $e->getMessage().'<br><br>
                File: '.$e->getFile().'<br>
                Code line: '.$e->getLine().'<br>
                <pre>Trace: <br>'.$e->getTraceAsString().'</pre>';
				
			$handle = @file_get_contents("view/exception.phtml");
			if($handle)
			{
				echo(str_replace("{message}", $str, $handle));
				exit;
			}
			else
				throw new Exception('Nie można otworzyć widoku błędu: view/exception.html<br>Aby wyświetlić: <pre>'.$e.'</pre>');
		}
        catch(Exception $e)
		{
			echo($e->getMessage().'<br>
                W pliku: '.$e->getFile().'<br>
                Linia: '.$e->getLine().'<br>
                <pre>Trace: <br>'.$e->getTraceAsString().'</pre>');
			exit;
        }
	}
    public function redirect($url, $type="", $ret="")
	{
		if($type != "" && $ret != "")
			if(count($_GET) == 1)
				$url .= '?type='.$type.'&ret='.base64_encode($ret);
			else
				$url .= '&type='.$type.'&ret='.base64_encode($ret);
		
		if(headers_sent())
		{
			echo('<meta http-equiv="refresh" content="0; URL='.$url.'">');
			exit();
		}
		else
			header('location: '.$url);
    }
	
    public function getMessage()
	{
		if(isset($_GET['type']) && isset($_GET['ret']))
			echo('<aside class="alert success">'.base64_decode($_GET['ret']).'</aside>');
	}
	
	public function getAction()
	{
		return (isset($_GET['act'])?$_GET['act']:'');
	}
	
	public function action($str)
	{
		if($str)
			echo "index.php?con=".$_GET['con']."&act=".$str;
		else
			echo "index.php?con=".$_GET['con'];
	}
}
?>