<?php
define("RDFAPI_INCLUDE_DIR", "././rdfapi-php/api/");
include(RDFAPI_INCLUDE_DIR . "RDFAPI.php");

class ReadController extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
	}

	function index()
	{
		// kada se pokrene kontroler
		//$this->load->view('ReadView', array('error' => ' ' ));

		$this->load->library("simple_html_dom");
		
		
		$array = $this->uri->uri_to_assoc(3);
		
		
		if(isset ($array['textFileName']))
		{
			//echo $array['textFileName'];
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
			$this->load->view('ReadView', $data);
		}
		else
		{
			$this->load->view('ReadView', array('error' => ' ' ));
		}
	}

	function removeBottomLines($str)
	{
		return str_replace("_", " ", $str);
	}
	
	function getPredicate()
	{

		$sub = $_POST['s'];
		$obj = $_POST['o'];
	
		$rdfGraphName =/*"./modeli/" . */$_POST['rdfGraph'];
	
		$subject = new Resource ($sub);
		$object = new Literal ($obj);
	
		$model = ModelFactory::getDefaultModel();
	
	
		$exists = file_exists($rdfGraphName);
	
		// ucitavanje modela
		if($exists==true)
		{
			// ovde se prosledi ime modela, tj putanja i ime
			//$rdfGraphName = "model.rdf";
			$model->load($rdfGraphName);
		}
	
	
		$m = $model->find($subject, NULL, $object);
		//echo $st->getLabelPredicate();
	
		if($m->size() == 0)
		{
			echo "Trenutno ne postoji veza izmedju pojmova";
		}
		else
		{
			$it = $m->getStatementIterator();
			//echo $st->writeAsHtmlTable();
				
			while ($it->hasNext()) {
				$statement = $it->next();
				//echo "Statement number: " . $it->getCurrentPosition() . "<BR>";
	
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
	
		$rdfGraphName =/*"./modeli/" . */ $_POST['rdfGraph'];
	
	
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
	

	
	function getStringArraySO($iModela)
	{

		$subjectsObjects = "";

		$rdfGraphName =/*"./modeli/" . */$iModela;

		$model = ModelFactory::getDefaultModel();
	
		$exists = file_exists($rdfGraphName);
	
		// ucitavanje modela
		if($exists==true)
		{
			// ovde se prosledi ime modela, tj putanja i ime
			$model->load($rdfGraphName);
		}
	
	
		$m = $model->find(NULL, NULL, NULL);

	
		if($m->size() == 0)
		{
			//echo "Trenutno ne postoji veza izmedju pojmova";
		}
		else
		{
			$it = $m->getStatementIterator();

			while ($it->hasNext()) {
				$statement = $it->next();
	
				$subjectsObjects .= $statement->getLabelSubject() . " ";
	
				$subjectsObjects .= $statement->getLabelObject() . " ";
			}
	
	
		}
	
		//$m->close();
		$model->close();
	
		$arraySO = explode(' ', $subjectsObjects);
		$nizSubObj = array_unique($arraySO);
		
		return $nizSubObj;
	
	}
/*
	function SpanZaRead($tekst, $SO)
	{
		$nizreci = explode(' ', $tekst);
		$string = '';
		
		$interpukcija = array('.',',',';',':',')','"',').','),');
		//$nizreci = explode(' ', $string);
		$string = '';
		$interpuk = 0;
		foreach ($nizreci as &$rec) {
			if(!(strpos($rec, '<img') or strpos($rec, 'src=') or strpos($rec, '<iframe')))
			{
				if(in_array($rec, $SO))
				{
					$interpuk = 1;
					if((strpos($rec, '<span>') && strpos($rec, '</span>'))==true)
					{
						$interpuk=0;
					}
					elseif ((strpos($rec,'<span>')==true))
					{
						$rec = $rec."</span>";
						$string = $string.$rec.' ';
						$interpuk=0;
					}
					elseif ((strpos($rec,'</span>')==true))
					{
						$rec = "<span>".$rec;
						$string = $string.$rec.' ';
						$interpuk=0;
					}
					
					if(strpos($rec, '(')==true)
					{
						$rec = strstr($rec, '(', true)."<span style='color:grey;' class='dragdrop'>".strstr($rec, '(');
					}
					
					if(strpos($rec, '"')==true)
					{
						$rec = strstr($rec, '"', true)."<span style='color:grey;' class='dragdrop'>".strstr($rec, '"');
					}
				
					if($interpuk==1)
					{
						foreach ($interpukcija as $interp)
						{
							if(strpos($rec,$interp)!=false)
							{
								$rec = "<span style='color:grey;' class='dragdrop'>".strstr($rec,$interp,true)."</span>".strstr($rec,$interp);
								$string = $string.$rec.' ';
								$interpuk=0;
							}
						}
					}
					if ($interpuk==1)
					{
						$rec = "<span style='color:grey;' class='dragdrop'>".$rec."</span>";
						$string = $string.$rec.' ';
					}
				}
				else
				{
					$string = $string.$rec.' ';
				}
			}
		}
		return $string;



	}
	*/
	
	/*
	 function getText()
	 {
	$this->load->model('ReadModel');
	
	$str = $_POST['str'];
	$textFileName = $_POST['textFile'];
	
	$this->ReadModel->writeText($str, $textFileName);
	
	print_r($str);
	
	}*/
	

	function getText()
	{
		$textFileName = $_POST['textFile'];
		$rdfGraphName = $_POST['rdfGraph'];
		
		$this->load->model('ReadModel');
		$str = $this->ReadModel->readText($textFileName);

		if (file_exists(base_url('/' . $rdfGraphName)))
		{
			echo $rdfGraphName;
			echo $str;

		}
		else 
		{
			/*if(substr($textFileName,-4)=="html" || substr($textFileName,-4)==".htm")
			{
				$str =$this->dodajBlankoKodTagova("./tekstovi/".$textFileName);
				$str = $this->spanHTML($str, $this->getStringArraySO($rdfGraphName));
			}
			else
			{
				$str = $this->SpanZaRead($str, $this->getStringArraySO($rdfGraphName));
			}*/

			echo $str;
		}
	}
	
	function getSubjectsObjects()
	{

		$rdfGraphName = $_POST['rdfGraph'];

		if (file_exists(base_url('/' . $rdfGraphName)))
		{
			echo $rdfGraphName;
			echo $str;
	
		}
		else
		{
			$arraySO= $this->getStringArraySO($rdfGraphName);
			
			$str = "";
			
			foreach ($arraySO as $SubObj)
			{
				$str.=$SubObj."|";
			}
			
			$str = substr($str, 0, -2);
			echo $str;
		}
	}
	
	function writeText($tekst, $imeFajla)
	{
		$this->load->model('ReadModel');
		$this->ReadModel->writeText($tekst,$imeFajla);
	}
	
	
	/*
	function spanSmallString($string)
	{
		$interpukcija = array('.',',',';',':','?', '!');
		//$nizreci = explode(' ', $string);
	
		$rec = $string;
	
		$string = '';

		$interpuk = 1;
		foreach ($interpukcija as $interp)
		{
			if(strstr($rec, $interp)!="")
			{
				$pos = strpos($rec,$interp);
				$rec = "<span style='color:grey;' class='dragdrop'>".substr($rec, 0, $pos)."</span>".strstr($rec,$interp);
				$string = $string.$rec.' ';
				$interpuk=0;
			}
		}
		if ($interpuk==1)
		{
			$rec = "<span style='color:grey;' class='dragdrop'>".$rec."</span>";
			$string = $string.$rec.' ';
		}

		return $string;
	
	}
*/
	/*
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
	function spanHTML($string, $SO)
	{
		
		$this->tagFlag=0;
	
		$interpukcija = array('.',',',';',':','?', '!');
		$nizreci = explode(' ', $string);
		$string = '';
		$interpuk = 0;
	
		$recFlag = false;
		$rec1="";
		foreach ($nizreci as &$rec)
		{

			//$rec = $string;
			
			//$string = '';
			

			foreach ($interpukcija as $interp)
			{
				if(strstr($rec, $interp)!="")
				{
					$pos = strpos($rec,$interp);
					$rec1 = substr($rec, 0, $pos);
					$recInterp = strstr($rec,$interp);
					//echo "prolazi ovde" . $interp;
				}
				else
				{
					$rec1 = $rec;
				}
			}

			if(in_array($rec1, $SO))
			{
				$recFlag = true;
			}
			
			
			// ukoliko u tom stringu ima reci koja je subjekat ili objekat
			if($recFlag==true)
			{
				if($rec==$rec1)
				{
					$rec = $this->odvoji($rec);
					$string = $string.$rec.' ';
					//echo "NEMA INTERPUNKCIJE";
				}
				else
				{
					/////////////////////
					$rec2 = $this->odvoji($rec1);
					$string = $string.$rec2.$recInterp.' ';
					//echo "IMA INTERPUNKCIJE";
				}
	
			}
			else
			{
					$string = $string.$rec.' ';
					//echo "TRECA VARIJANTA";
			}
			$recFlag = false;
		}
	
		return $string;	
	}
	*/
	/*
	function dodajBlankoKodTagova($link)
	{
		//$h = $this->gethtml(base_url('proba.html'));
		$h = $this->gethtml($link);
		return $h;
	}
	*/
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
	}
	*/
}

?>