<?php

class Controller
{
    private $message;
    
    public function __construct()
    {
    	if (isset($_GET['type']) && isset($_GET['ret'])) {
            $this->message = array(
        		'type' => $_GET['type'],
        		'msg' => base64_decode($_GET['ret'])
            );
    	}
    }

    public function run()
    {
    	try {
            $model = isset($_GET['con']) ? $_GET['con'] : 'main';
            $path = 'model/' . $model . '.php';
            $model .= "_Model";
            
            if (is_file($path)) {
                include_once ($path);
                $this->model = new $model();
            } else
                throw new RuntimeException('Nie można otworzyć pliku modelu: ' . $path);
    	} catch (Exception $e) {
    		$this->handleError($e);
    	}
    }

    public function handleError($e)
    {
    	// Czyszczenie bufora php
    	ob_clean();
    	
        $str = $e->getMessage() . '<br><br>
            File: ' . $e->getFile() . '<br>
            Code line: ' . $e->getLine() . '<br>
            <pre>Trace: <br>' . $e->getTraceAsString() . '</pre>';
        
        $content = file_get_contents("view/exception.phtml");
        if (!$content) {
        	echo ($e->getMessage() . '<br>
            W pliku: ' . $e->getFile() . '<br>
            Linia: ' . $e->getLine() . '<br>
            <pre>Trace: <br>' . $e->getTraceAsString() . '</pre>');
        } else {
        	echo (str_replace("{message}", $str, $content));
        }
        
        exit();
    }

    public function redirect($url, $type = "", $ret = "")
    {
        if ($type != "" && $ret != "")
            if (strpos($url, '?') !== false)
                $url .= '&type=' . $type . '&ret=' . base64_encode($ret);
            else
                $url .= '?type=' . $type . '&ret=' . base64_encode($ret);
        
        if (headers_sent()) {
            echo ('<meta http-equiv="refresh" content="0; URL=' . $url . '">');
        } else
            header('location: ' . $url);
        
        exit();
    }

    public function getMessage()
    {
        if ($this->message) {
            echo '<aside class="alert ' . $this->message['type'] . '">' . $this->message['msg'] . '</aside>';
        }
    }
    
    public function setMessage($type, $msg)
    {
    	$this->message = array(
			'type' => $type,
			'msg' => $msg
    	);
    }

    public function getAction()
    {
        return (isset($_GET['act']) ? $_GET['act'] : '');
    }

    public function action($str)
    {
        if ($str)
            echo "index.php?con=" . $_GET['con'] . "&act=" . $str;
        else
            echo "index.php?con=" . $_GET['con'];
    }
    
    public function generateUrl(array $params = array())
    {
        $base = 'index.php';
        if ($params) {
            $base .= '?' . http_build_query($params);
        }
        
        return $base;
    }
}
?>