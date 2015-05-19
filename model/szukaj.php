<?php
include_once("model/model.php"); 

class szukaj_Model extends Model 
{
	public function __construct()  
	{
		parent::__construct(); 
		

				$this->szukaj();


	}
		
	public function szukaj() 
	{
		 if(isset($_POST['szukaj'])) 
			 

			
			
			if($_POST['szukane'] == "" and $_POST['sortowanie'] == "")
			{
				$_SESSION['poprzednia']="";
				$this->redirect("index.php?con=szukaj", "error", "Wpisz interesujące Cię słowa i kliknij Szukaj");
			}
				else 
				{
					$_POST['szukane']=trim($_POST['szukane']);
					if($_POST['szukane'] == "" and $_POST['sortowanie'] == "")
					{
						
						$this->redirect("index.php?con=szukaj", "error", "Wpisz interesujące Cię słowa i kliknij Szukaj");
					}
				
					else	
					{	
				
						$szukana=addslashes($_POST['szukane']);
						if($_POST['szukane'] != "") $_SESSION['poprzednia']=$szukana;
						
						if($_POST['sortowanie'] != "")
						{	
							if($_SESSION['poprzednia']!="")
							{
							$szukana=$_SESSION['poprzednia'];
							
							if($_POST['sortowanie'] == "data1")
								$result = $this->sql_query("SELECT k.ID_Konferencja, k.Nazwa, kon.Imie, kon.Nazwisko, k.Miejsce, k.Data, k.Koszt, unix_timestamp(k.Data)
															FROM konferencja k 
															join organizator o on o.ID_Organizator=k.ID_Organizator
															join konto kon on kon.ID_Konto=o.ID_Konto WHERE k.Nazwa LIKE '%$szukana%' AND (k.Data>NOW()) ORDER BY k.Data");
							if($_POST['sortowanie'] == "data2")								
								$result = $this->sql_query("SELECT k.ID_Konferencja, k.Nazwa, kon.Imie, kon.Nazwisko, k.Miejsce, k.Data, k.Koszt, unix_timestamp(k.Data)
															FROM konferencja k 
															join organizator o on o.ID_Organizator=k.ID_Organizator
															join konto kon on kon.ID_Konto=o.ID_Konto WHERE k.Nazwa LIKE '%$szukana%' AND (k.Data>NOW()) ORDER BY k.Data DESC");
							if($_POST['sortowanie'] == "nazwa1")								
								$result = $this->sql_query("SELECT k.ID_Konferencja, k.Nazwa, kon.Imie, kon.Nazwisko, k.Miejsce, k.Data, k.Koszt, unix_timestamp(k.Data)
															FROM konferencja k 
															join organizator o on o.ID_Organizator=k.ID_Organizator
															join konto kon on kon.ID_Konto=o.ID_Konto WHERE k.Nazwa LIKE '%$szukana%' AND (k.Data>NOW()) ORDER BY k.Nazwa");
							if($_POST['sortowanie'] == "nazwa2")								
								$result = $this->sql_query("SELECT k.ID_Konferencja, k.Nazwa, kon.Imie, kon.Nazwisko, k.Miejsce, k.Data, k.Koszt, unix_timestamp(k.Data)
															FROM konferencja k 
															join organizator o on o.ID_Organizator=k.ID_Organizator
															join konto kon on kon.ID_Konto=o.ID_Konto WHERE k.Nazwa LIKE '%$szukana%' AND (k.Data>NOW()) ORDER BY k.Nazwa DESC");
							if($_POST['sortowanie'] == "koszt1")								
								$result = $this->sql_query("SELECT k.ID_Konferencja, k.Nazwa, kon.Imie, kon.Nazwisko, k.Miejsce, k.Data, k.Koszt, unix_timestamp(k.Data)
															FROM konferencja k 
															join organizator o on o.ID_Organizator=k.ID_Organizator
															join konto kon on kon.ID_Konto=o.ID_Konto WHERE k.Nazwa LIKE '%$szukana%' AND (k.Data>NOW()) ORDER BY k.Koszt");
							if($_POST['sortowanie'] == "koszt2")								
								$result = $this->sql_query("SELECT k.ID_Konferencja, k.Nazwa, kon.Imie, kon.Nazwisko, k.Miejsce, k.Data, k.Koszt, unix_timestamp(k.Data)
															FROM konferencja k 
															join organizator o on o.ID_Organizator=k.ID_Organizator
															join konto kon on kon.ID_Konto=o.ID_Konto WHERE k.Nazwa LIKE '%$szukana%' AND (k.Data>NOW()) ORDER BY k.Koszt DESC");
							}
								 else $this->redirect("index.php?con=szukaj", "error", "Wpisz interesujące Cię słowa i kliknij Szukaj");
							
							
							
						}
						else	$result = $this->sql_query("SELECT k.ID_Konferencja, k.Nazwa, kon.Imie, kon.Nazwisko, k.Miejsce, k.Data, k.Koszt, unix_timestamp(k.Data)
															FROM konferencja k 
															join organizator o on o.ID_Organizator=k.ID_Organizator
															join konto kon on kon.ID_Konto=o.ID_Konto WHERE k.Nazwa LIKE '%$szukana%' AND (k.Data>NOW()) ORDER BY k.Data");


									
						if(!$result)
						{
							$this->redirect("index.php?con=szukaj", "error", "Brak wyników ze słowami '$szukana'");
						}
						else
						{		
							if($_POST['dzien_od']!="" and $_POST['miesiac_od']!="" and $_POST['rok_od']!="")
							{	
								if (!checkdate( $_POST['miesiac_od'], $_POST['dzien_od'], $_POST['rok_od'])) {
									$this->redirect("index.php?con=szukaj", "error", "Data jest niepoprawna");
								} 
						
								$dzien   = $_POST['dzien_od'];
								$miesiac = $_POST['miesiac_od'];
								$rok     = $_POST['rok_od'];
								$godzina = 00;
								$minuta  = 00;
								$sekunda = 00;
								$ts = mktime($godzina, $minuta, $sekunda, $miesiac, $dzien, $rok);
								
								$j=0;
								for($i=0; $i<count($result); $i++)
								{	$p=$result[$i][7];
									if($p>=$ts)
									{
										$tymcz[$j]=$result[$i];
										$j++;
									}
									
								}
								unset($result);
								$result=$tymcz;
								unset($tymcz);
	
								if(count($result)==0) $this->redirect("index.php?con=szukaj", "error", "Brak wyników");
							}
							
							if($_POST['dzien_do']!="" and $_POST['miesiac_do']!="" and $_POST['rok_do']!="")
							{	
								if (!checkdate( $_POST['miesiac_do'], $_POST['dzien_do'], $_POST['rok_do'])) {
									$this->redirect("index.php?con=szukaj", "error", "Data jest niepoprawna");
								} 
						
								$dzien   = $_POST['dzien_do'];
								$miesiac = $_POST['miesiac_do'];
								$rok     = $_POST['rok_do'];
								$godzina = 23;
								$minuta  = 59;
								$sekunda = 59;
								$ts = mktime($godzina, $minuta, $sekunda, $miesiac, $dzien, $rok);
								
								$j=0;
								for($i=0; $i<count($result); $i++)
								{	$p=$result[$i][7];
									if($p<=$ts)
									{
										$tymcz[$j]=$result[$i];
										$j++;
									}
									
								}
								unset($result);
								$result=$tymcz;
								unset($tymcz);
	
								if(count($result)==0) $this->redirect("index.php?con=szukaj", "error", "Brak wyników");
							}
							
	

					
							if($_POST['cena_od']!="")
							{	
								$j=0;
								for($i=0; $i<count($result); $i++)
								{	
									if($result[$i][6]>=$_POST['cena_od'])
									{
										$tymcz[$j]=$result[$i];
										$j++;
									}
									
								}
								unset($result);
								$result=$tymcz;
								unset($tymcz);
								if(count($result)==0) $this->redirect("index.php?con=szukaj", "error", "Brak wyników");
							}
							
							if($_POST['cena_do']!="")
							{	
								$j=0;
								for($i=0; $i<count($result); $i++)
								{	
									if($result[$i][6]<=$_POST['cena_do'])
									{
										$tymcz[$j]=$result[$i];
										$j++;
									}
									
								}
								unset($result);
								$result=$tymcz;
								unset($tymcz);
								if(count($result)==0) $this->redirect("index.php?con=szukaj", "error", "Brak wyników");
							}
							
							if($_POST['miasto']!="")
							{	$_POST['miasto']=trim($_POST['miasto']);
								$j=0;
								for($i=0; $i<count($result); $i++)
								{	
									if(strtolower($result[$i][4])==strtolower($_POST['miasto']))
									{
										$tymcz[$j]=$result[$i];
										$j++;
									}
									
								}
								unset($result);
								$result=$tymcz;
								unset($tymcz);
								if(count($result)==0) $this->redirect("index.php?con=szukaj", "error", "Brak wyników");
							}
							
							if($_POST['organizator']!="")
							{	$_POST['organizator']=trim($_POST['organizator']);

								$j=0;
								for($i=0; $i<count($result); $i++)
								{									
								$cz1=strtolower($result[$i][2]);
								$cz2=strtolower($result[$i][3]);
								$cz3="$cz1 $cz2";
							
									if($cz3==$_POST['organizator'])
									{
										$tymcz[$j]=$result[$i];
										$j++;
									}
									
								}
								unset($result);
								$result=$tymcz;
								unset($tymcz);
								if(count($result)==0) $this->redirect("index.php?con=szukaj", "error", "Brak wyników");
							}

							
								
							$znalezionych=count($result);
						
							
						}
						

					}
					
				}
		include __DIR__ . "/../view/wyniki.phtml";

	}


}
?>