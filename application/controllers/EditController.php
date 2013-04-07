<?php
define("RDFAPI_INCLUDE_DIR", "././rdfapi-php/api/");
include(RDFAPI_INCLUDE_DIR . "RDFAPI.php");

class EditController extends CI_Controller {
		
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->helper('url');

	}
	
	function index()
	{
		// kada se pokrene kontroler
		//$this->load->view('EditView', array('error' => ' ' ));
		
		$this->load->library("simple_html_dom");
		
		
	
		$array = $this->uri->uri_to_assoc(3);
		
		
		if(isset ($array['textFileName']))
		{
			$data['textFileName'] = $array['textFileName'];
		}
		
		if(isset ($array['textFileType']))
		{
			$data['textFileType'] = $array['textFileType'];
		}
		
		if(isset ($array['rdfGraphName']))
		{
			//echo $array['rdfGraphName'];
			$data['rdfGraphName'] = $array['rdfGraphName'];
		}
		
		
		if(isset ($data['textFileName']) || isset ($data['rdfGraphName']))
		{
			$this->load->view('EditView', $data);
		}
		else
		{
			$this->load->view('EditView', array('error' => ' ' ));
		}
	}
	
	function putBottomLines($str)
	{
		return str_replace(" ", "_", $str);
	}
	
	function removeBottomLines($str)
	{
		return str_replace("_", " ", $str);
	}
	
	function writeStatement()
	{

		$sub = $_POST['s'];
		$obj = $_POST['o'];
		$pre = $_POST['p'];
		
		$pre =  $this->putBottomLines($pre);
		
		$rdfGraphName =/*"modeli/" .*/ $_POST['rdfGraph'];
		
		$subject = new Resource ($sub);
		$object = new Literal ($obj);
		$predicate = new Resource ($pre);
		
		$statement = new Statement ($subject, $predicate, $object);
		
		$model = ModelFactory::getDefaultModel();
		
		// ucitavanje modela
		$exists = file_exists($rdfGraphName);
		
		if($exists==true)
		{
			// ovde se prosledi ime modela, tj putanja i ime
			$model->load($rdfGraphName);
		}
		
		$model->addWithoutDuplicates($statement);
		

		$model->saveAs($rdfGraphName, "rdf");
		
		$model->close();
	}
	
	function getPredicate()
	{		
		$sub = $_POST['s'];
		$obj = $_POST['o'];
		
		$rdfGraphName =/*"modeli/" .*/ $_POST['rdfGraph'];
		
		$subject = new Resource ($sub);
		$object = new Literal ($obj);
		
		$model = ModelFactory::getDefaultModel();
		
		
		$exists = file_exists($rdfGraphName);
		
		// ucitavanje modela
		if($exists==true)
		{
			// ovde se prosledi ime modela, tj putanja i ime
			$model->load($rdfGraphName);
		}
		
		
		$m = $model->find($subject, NULL, $object);

		if($m->size() == 0)
		{
			echo "Trenutno ne postoji veza izmedju pojmova";
		}
		else
		{
			$it = $m->getStatementIterator();
			
			while ($it->hasNext()) {
				
				$statement = $it->next();
				
				echo $statement->getLabelSubject();
				echo " <span style='color:green; font-weight:bold;'>" . $this->removeBottomLines($statement->getLabelPredicate()) . "</span> ";
				echo " " . $statement->getLabelObject() . "<BR>";
			}
			
			
		}
		
		//$m->close();
		$model->close();
		
	}
	
	function getObjects()
	{
		$sub = $_POST['s'];
	
		$rdfGraphName =/*"modeli/" .*/ $_POST['rdfGraph'];
		
		$subject = new Resource ($sub);

		$model = ModelFactory::getDefaultModel();
	
		$exists = file_exists($rdfGraphName);
	
		// ucitavanje modela
		if($exists==true)
		{
		// ovde se prosledi ime modela, tj putanja i ime
			$model->load($rdfGraphName);
		}
	
		$m = $model->find($subject, NULL, NULL);
	
		if($m->size() == 0)
		{
			//echo "Trenutno ne postoji veza izmedju pojmova";
		}
		else
		{
			$it = $m->getStatementIterator();
				
			while ($it->hasNext()) {
				$statement = $it->next();

				if($it->hasNext())
				{
					echo "span:contains(" . $statement->getLabelObject() . "),";
				}
				else
				{
					echo "span:contains(" . $statement->getLabelObject() . ")";
				}
			}
					
		}
	
		//$m->close();
		$model->close();
	
	}

	function uploadRdfGraph()
	{
		//$uploaddir = './modeli/';
		$uploaddir = '';
		$uploadfile = $uploaddir . basename($_FILES['filesRdf']['name']);
		echo basename($_FILES['filesRdf']['name']);
		
		 if (move_uploaded_file($_FILES['filesRdf']['tmp_name'], $uploadfile)) 
		 {
		  echo "success";
		  	
		 } 
		 else 
		 {
		   echo "error";
		 }
	}
	
	function uploadTextFile()
	{
		$uploaddir = './tekstovi/';
		$uploadfile = $uploaddir . basename($_FILES['filesText']['name']);
		echo basename($_FILES['filesText']['name']);
	
		if (move_uploaded_file($_FILES['filesText']['tmp_name'], $uploadfile))
		{
			echo "success";
			 
		}
		else
		{
			echo "error";
		}
	}
	
	function writeText($tekst, $imeFajla)
	{
		//$str = $_POST['str'];
	
		//print_r($str);
	
		//$str = $this->span($str);
	
		//print_r($str);
		//print_r('<span>jen</span> <span>d</span> <span>tr</span>');
		$this->load->model('ReadModel');
		$this->ReadModel->writeText($tekst,$imeFajla);
	}
	
	function getText()
	{
		$textFileName = $_POST['textFile'];
		$imeModela = $_POST['rdfGraph'];
	
		$this->load->model('ReadModel');
		$str = $this->ReadModel->readText($textFileName);
	
		//print($str);
		/*
			if(substr($textFileName,-4)=="html" || substr($textFileName,-4)==".htm")
			{
		$str =$this->dodajBlankoKodTagova("./tekstovi/".$textFileName);
		$str = $this->spanHTML($str);
		}
		else
		{
		$str = $this->span($str);
		}
		*/
	
		echo $str;
	}
	
	
	
	/////////  FUNKCIJE ZA RAD SA HTML fajlovima  ////////////////
	/*
	function gethtml($html)
	{
		$this->load->library("simple_html_dom");
		$h = file_get_html($html);
		$ht = $h->find('body',0);
	
		$tagovi = array( 'span', 'b', 'u', 'i', 'font', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'br', 'hr', 'code', 'em', 'kbd', 'pre' ,'small', 'strong', 'abbr', 'address', 'bdo', 'blockquote', 'cite', 'del', 'ins' ,'sub', 'sup', 'a', 'img',  'style',  'div', 'ul', 'li', 'ol', 'dl', 'dt', 'dd', 'table',  'tr', 'th', 'td', 'iframe',  'form','input', 'select', 'option', 'textarea' );
	
		foreach($tagovi as $tag)
		{
			foreach($ht->find($tag) as $t)
			{
				$t->innertext=' '.$t->innertext.' ';
				$t->outertext=' '.$t->outertext.' ';
			}
		}
	
		return $ht->innertext;
	}*/
/*	
	function dodajBlankoKodTagova($link)
	{
		 //$h = $this->gethtml(base_url('proba.html'));
		 $h = $this->gethtml($link);
		 return $h;
	}
		*/
	
	/*
	function spanSmallString($string)
	{
		$interpukcija = array('.',',',';',':','?', '!');
		//$nizreci = explode(' ', $string);
		
		$rec = $string;
		
		$string = '';
		
		
		$interpuk = 0;
		//foreach ($nizreci as &$rec) {
			$interpuk = 1;
			foreach ($interpukcija as $interp)
			{
				if(strstr($rec, $interp)!="")
				{
					$pos = strpos($rec,$interp);
					$rec = "<span class='dragdrop'>".substr($rec, 0, $pos)."</span>".strstr($rec,$interp);
					$string = $string.$rec.' ';
					$interpuk=0;
				}
			}
			if ($interpuk==1)
			{
				$rec = "<span class='dragdrop'>".$rec."</span>";
				$string = $string.$rec.' ';
			}
		//}
		return $string;
	
	}
	
	function odvoji($str)
	{
		$rec = $str;
		
		$rezString='';
		
		// dva slucaja
		
		// < pre >
		// > pre <
		// proveri sa strpos i to je to
		
		$imaO = false;
		
		if(strstr($rec, "<")!="")
		{
			$imaO=true;
		}
		
		$imaZ = false;
		
		if(strstr($rec, ">")!="")
		{
			$imaZ=true;
		}
		
		
		// ukoliko je u pitanju rec bez < ili >
		if($imaO == false && $imaZ == false)
		{
			// ukoliko tag nije otvoren znakom <
			if($this->tagFlag==0)
			{
				$rec = $this->spanSmallString($rec);
				$rezString = $rezString.$rec;
				
				return $rezString;
			}
			else
			{
				$rezString = $rezString.$rec;
				return $rezString;
			}
		}
		
		
		
		
		if($imaO==true && $imaZ==true)
		{
			
			
			if( (strpos($rec, "<")) < (strpos($rec, ">")) )
			{
				// < pre >
			
				if(strpos($rec, "<")==0)
				{
					// situacija "<p hgfghgh>dfvfdcvfd"
					$pos = strpos($rec, ">");
			
					$rec1 = substr($rec, 0, $pos+1);
			
					//dodavanje u string
					$rezString = $rezString.$rec1;
			
					
					// zatvara se otvaranje taga
					$this->tagFlag=0;/////////////////////////////////
					
					
					//$rezString = $rezString."LALA".' '.$this->tagFlag;
					
					// ukoliko ima jos karaktera preostalih u stringu
					if($pos<strlen($rec)-1)
					{
						$rec = substr($rec, $pos+1);
						$rezString = $rezString. $this->odvoji($rec);
					}
					else
					{
						// doslo se do kraja stringa
						
						
						return $rezString;
					}
				}
				else
				{
					// situacija "sfdgfdfg<p bdfbvvd..."
					$pos = strpos($rec, "<");
			
					$rec1 = substr($rec, 0, $pos+1);
			
					//dodavanje u string
					$rec1 = $this->spanSmallString($rec1);
			
					$rezString = $rezString.$rec1;
			
					
					if($pos<strlen($rec)-1)
					{
						$rec = substr($rec, $pos+1);
						$rezString = $rezString. $this->odvoji($rec);
					}
					else
					{
						// doslo se do kraja stringa
						return $rezString;
					}
			
				}
			}
			else
			{
				// > ispred <
				
				if($this->tagFlag==0)
				{
					// trazimo poziciju prvog < zato sto u ovom slucaju svaki znak >>>>> predstavlja deo teksta
					$pos = strpos($rec, "<");
					$rec1 = substr($rec, 0, $pos);
					
					// nijedan tag nije otvoren, > se nalazi u tekstu, spanovanje reci
					$rec1 = $this->spanSmallString($rec1);
					$rezString = $rezString.$rec1;

					$rec = substr($rec, $pos);
					$rezString = $rezString. $this->odvoji($rec);
	
				}
				else 
				{
					
					$pos = strpos($rec, ">");
					$rec1 = substr($rec, 0, $pos+1);
						
					$rezString = $rezString.$rec1;
					
					$this->tagFlag=0;
					
					$rec = substr($rec, $pos+1);
					$rezString = $rezString. $this->odvoji($rec);
				}
				
			}	
		}
		else
		{
			
			if($imaO == true)
			{
				
				
				// ima samo <
				if(strpos($rec, "<")==0)
				{
			
					$rezString = $rezString.$rec;
			
					$this->tagFlag=1;
					// doslo se do kraja stringa
					return $rezString;
			
				}
				else
				{
					
					$pos = strpos($rec, "<");
						
					$rec1 = substr($rec, 0, $pos+1);
						
					//dodavanje u string
					$rec1 = $this->spanSmallString($rec1);
						
					$rezString = $rezString.$rec1;
						
						
					if($pos<strlen($rec)-1)
					{
						$rec = substr($rec, $pos+1);
						$rezString = $rezString. $this->odvoji($rec);
					}
					else
					{
						// doslo se do kraja stringa

						return $rezString;
					}
						
				}
			}
			else
			{
				// ukoliko ima samo >


					if($this->tagFlag==0)
					{

						// nijedan tag nije otvoren, > se nalazi u tekstu, spanovanje reci
						$rec1 = $this->spanSmallString($rec);
						$rezString = $rezString.$rec1;
						
						return $rezString;
						
					}
					else
					{
						$pos = strpos($rec, ">");
							
						$rec1 = substr($rec, 0, $pos+1);
						
						$rezString = $rezString.$rec1;
						
						$this->tagFlag=0;
						
						if($pos<strlen($rec)-1)
						{
							$rec = substr($rec, $pos+1);
							$rezString = $rezString. $this->odvoji($rec);
						}
						else
						{
							// doslo se do kraja stringa
							return $rezString;
						}
					}
			}
		}
		
	
	}
	*/
	
	/*
	function spanHTML($string)
	{
		$this->tagFlag=0;
		
		$interpukcija = array('.',',',';',':','?', '!');
		$nizreci = explode(' ', $string);
		$string = '';
		$interpuk = 0;
		
		// 0 ako nije otvorena zagrada <, 1 ako je otvorena a nije nadjeno >
		//$tagFlag=0;
		
		foreach ($nizreci as &$rec) 
		{
			//$interpuk = 1;
		
			if(strstr($rec, "<")!="" || strstr($rec, ">")!="")
			{
				$rec = $this->odvoji($rec);
				$string = $string.$rec.' ';
			}
			else
			{
				if($this->tagFlag==1)
				{
					$string = $string.$rec.' ';
				}
				else 
				{
					
					$interpuk = 1;
					foreach ($interpukcija as $interp)
					{
						if(strstr($rec, $interp)!="")
						{
							$pos = strpos($rec,$interp);
							$rec = "<span class='dragdrop'>".substr($rec, 0, $pos)."</span>".strstr($rec,$interp);
							$string = $string.$rec.' ';
							$interpuk=0;
						}
					}
					if ($interpuk==1)
					{
						$rec = "<span class='dragdrop'>".$rec."</span>";
						$string = $string.$rec.' ';
					}
				}
				
			}
		}
		
		return $string;
	}
	*/
	
	/*
	
	/// fja za spanovanje teksta iz txt fajla
	function span($string)
	{
			$interpukcija = array('.',',',';',':','?', '!');
			$nizreci = explode(' ', $string);
			$string = '';
			$interpuk = 0;
			foreach ($nizreci as &$rec) {
				$interpuk = 1;
				foreach ($interpukcija as $interp)
				{
					if(strpos($rec,$interp)!=false)
					{
						$rec = "<span class='dragdrop'>".strstr($rec,$interp,true)."</span>".strstr($rec,$interp);
						$string = $string.$rec.' ';
						$interpuk=0;
					}
				}
				if ($interpuk==1)
				{
					$rec = "<span class='dragdrop'>".$rec."</span>";
					$string = $string.$rec.' ';
				}
			}
			return $string;
		
	
		
	}
	*/
	/*function getText()
	{
		$this->load->model('ReadModel');
		
		$str = $_POST['str'];
		$textFileName = $_POST['textFile'];
		
		$this->ReadModel->writeText($str, $textFileName);
		*/
								/*if(substr($textFileName,-4)=="html" || substr($textFileName,-4)==".htm")
								{
									$str =$this->dodajBlankoKodTagova("./tekstovi/".$textFileName);
									$str = $this->spanHTML($str);
								}
								else
								{
									$str = $this->span($str);
								}*/
	/*	
		print_r($str);
	}*/
	


}

?>