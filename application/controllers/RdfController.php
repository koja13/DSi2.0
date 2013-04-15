<?php
define("RDFAPI_INCLUDE_DIR", "././rdfapi-php/api/");
include(RDFAPI_INCLUDE_DIR . "RDFAPI.php");

class RdfController extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->helper('url');

	}

	function index()
	{	
		$this->load->library("simple_html_dom");
	}

	// ================================ putBottomLines($str) ================================
	//
	// poziva je fja writeStatement()
	//
	// Ulazni parametri: $str - string kod koga treba da se umesto blanko znaka " " stave donje crte "_"
	//
	function putBottomLines($str)
	{
		return str_replace(" ", "_", $str);
	}

	// ================================ removeBottomLines($str) ================================
	//
	// poziva je fja getPredicate()
	//
	// Ulazni parametri: $str - string kod koga treba da se umesto donjih crta "_" stave blanko znaci " " 
	//
	function removeBottomLines($str)
	{
		return str_replace("_", " ", $str);
	}

	// ================================ uploadRdfGraph() ================================
	//
	// poziva se sa klijenta prilikom upload-a rdf grafa,
	// link ka ovoj fji se postavlja u action propertiju forme za upload rdf grafa
	//
	function uploadRdfGraph()
	{
		$uploaddir = '';
		$uploadfile = $uploaddir . basename($_FILES['filesRdf']['name']);
		echo basename($_FILES['filesRdf']['name']);
	
		if (move_uploaded_file($_FILES['filesRdf']['tmp_name'], $uploadfile))
		{
			echo "success";
			
			echo
			
			// ukoliko je u pitanju ReadController onda se vrsi spanovanje nakon upload-a
			// rdf fajla, pa se nakon toga prikazuje dugme za download
			// ovo radimo kako bi obezbedili spanovanje teksta na klijentu tek nakon
			// zavrsenog upload-a rdf fajla na server
			"<script>
				
				if(parent.config.controller=='ReadController')
				{
					parent.span();
				}
					
			</script>";
		}
		else
		{
			echo "error";
		}
	}
	
	// ================================ uploadTextFile() ================================
	//
	// poziva se sa klijenta prilikom upload-a text fajla,
	// link ka ovoj fji se postavlja u action propertiju forme za upload fajla sa tekstom
	//
	function uploadTextFile()
	{
		$uploaddir = './textFiles/';
		$uploadfile = $uploaddir . basename($_FILES['filesText']['name']);
		echo basename($_FILES['filesText']['name']);
	
		if (move_uploaded_file($_FILES['filesText']['tmp_name'], $uploadfile))
		{
			echo "success";
			
			// poziv fje na klijentu iz iframe-a pristupanjem njegovom parent-u, tj celom window objektu
			// ovo radimo kako bi obezbedili ucitavanje teksta na klijentu tek nakon zavrsenog upload-a na server
			echo
			
			"<script>
					
					parent.getTextFromServer(\"". $_FILES['filesText']['name'] . "\");
							
			</script>";
	
		}
		else
		{
			echo "error";
		}
	}
	
	// ================================ writeStatement() ================================
	//
	// ajax odgovor
	// poziva je sa klijenta fja sendSubjectObjectPredicate(form)
	//
	// Ulazni parametri: $sub     - subjekat iskaza	- stize kroz url u okvicu $_POST
	//                   $obj     - objekat iskaza	- stize kroz url u okvicu $_POST
	//					 $pre	  - predikat iskaza - stize kroz url u okvicu $_POST
	//					 $rdfGraphName - ime RDF fajla	- stize kroz url u okvicu $_POST                
	//
	// Na osnovu subjekta, objekta i predikta koje je dobila, ova funkcija otvara RDF fajl,
	// i upisuje novi iskaz u rdf graf ukoliko takav vec ne postoji
	// (za upisivanje se koristi fja addWithoutDuplicates($statement))
	//
	function writeStatement()
	{

		$sub = $_POST['s'];
		$obj = $_POST['o'];
		$pre = $_POST['p'];
		$rdfGraphName = $_POST['rdfGraph'];
		
		$pre =  $this->putBottomLines($pre);

		$subject = new Resource ($sub);
		$object = new Literal ($obj);
		$predicate = new Resource ($pre);

		$statement = new Statement ($subject, $predicate, $object);

		$rdfGraph = ModelFactory::getDefaultModel();

		// ucitavanje RDF grafa
		$exists = file_exists($rdfGraphName);

		if($exists==true)
		{
			// ovde se prosledi ime RDF grafa, tj putanja i ime
			$rdfGraph->load($rdfGraphName);
		}

		$rdfGraph->addWithoutDuplicates($statement);

		$rdfGraph->saveAs($rdfGraphName, "rdf");

		$rdfGraph->close();
		
	}

	// ================================ getPredicate() ================================
	//
	// ajax odgovor
	// poziva je sa klijenta fja sendSubjectObject()
	//
	// Ulazni parametri: $sub     - subjekat iskaza	- stize kroz url u okvicu $_POST
	//                   $obj     - objekat iskaza	- stize kroz url u okvicu $_POST
	//					 $rdfGraphName - ime RDF fajla	- stize kroz url u okvicu $_POST
	//
	// Na osnovu subjekta i objekta koje je dobila, ova funkcija otvara RDF fajl,
	// izvlaci iz njega sve iskaze koji imaju taj subjekat i objekat (rezultat je lancana
	// lista RDF iskaza u promenljivoj tipa klase MemModel). Onda se prodje kroz ovu listu
	// iskaza, i iz svakog se izvuce predikat (veza). Prilikom svakog izvlacenja veze,
	// stampa (echo) subjekat, zelenu vezu (predikat) i objekat, nazad ka klijentu.
	function getPredicate()
	{
		$sub = $_POST['s'];
		$obj = $_POST['o'];
		$rdfGraphName = $_POST['rdfGraph'];

		$subject = new Resource ($sub);
		$object = new Literal ($obj);

		$rdfGraph = ModelFactory::getDefaultModel();


		$exists = file_exists($rdfGraphName);

		// ucitavanje RDF-a
		if($exists==true)
		{
			// ovde se prosledi ime RDF-a, tj putanja i ime
			$rdfGraph->load($rdfGraphName);
		}


		$m = $rdfGraph->find($subject, NULL, $object);

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

		$rdfGraph->close();

	}

	// ================================ getObjects() ================================
	//
	// ajax odgovor
	// poziva je sa klijenta fja sendSubject()
	//
	// Ulazni parametri: $sub     - subjekat iskaza	- stize kroz url u okvicu $_POST
	//					 $rdfGraphName - ime RDF fajla	- stize kroz url u okvicu $_POST
	//
	// Na osnovu subjekta koji je dobila, ova funkcija otvara RDF fajl,
	// izvlaci iz njega sve iskaze koji imaju taj subjekat (rezultat je lancana
	// lista RDF iskaza u promenljivoj tipa klase MemModel). Onda se prodje kroz ovu listu
	// iskaza, i iz svakog se izvuce objekat. Prilikom svakog izvlacenja objekta,
	// o se istovremeno i stampa (echo), nazad ka klijentu
	// (stampa se u obliku jQuery selektora " span:contains('OBJEKAT') ", kako bi te objekte pronasli u tekstu).
	//
	function getObjects()
	{
		$sub = $_POST['s'];
		$rdfGraphName = $_POST['rdfGraph'];

		$subject = new Resource ($sub);

		$rdfGraph = ModelFactory::getDefaultModel();

		$exists = file_exists($rdfGraphName);

		// ucitavanje modela
		if($exists==true)
		{
			// ovde se prosledi ime RDF-a, tj putanja i ime
			$rdfGraph->load($rdfGraphName);
		}

		$m = $rdfGraph->find($subject, NULL, NULL);

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
		
		$rdfGraph->close();

	}

	// ================================ getSubjectsObjects() ================================
	//
	// ajax odgovor
	// poziva je sa klijenta fja spanReadMode()
	//
	// Ulazni parametri: $rdfGraphName - ime RDF fajla	- stize kroz url u okvicu $_POST
	//
	// Na osnovu naziva rdf fajla koji je dobila, ova funkcija otvara RDF fajl,
	// izvlaci iz njega sve iskaze
	// (tj sve subjekte i objekte u obliku niza stringova pomocu fje getStringArraySO($rdfGraphName))
	// Ka klijentu se stampa (echo) niz subjekata i objekata u obliku jednog stringa gde su reci odvojene znakom "|"
	// kako bi se taj string iskoristio kao regular expression za pronalazenje tih reci u tekstu
	//
	function getSubjectsObjects()
	{
		$rdfGraphName = $_POST['rdfGraph'];
	
		$exists = file_exists($rdfGraphName);
	
		if ($exists == true)
		{
			$arraySO= $this->getStringArraySO($rdfGraphName);
				
			$subjectsObjects = "";
	
			foreach ($arraySO as $SubObj)
			{
				$subjectsObjects.=$SubObj."|";
			}
	
			$subjectsObjects = substr($subjectsObjects, 0, -2);
	
			echo $subjectsObjects;
	
		}
		else
		{
			echo "";
		}
	}
	
	// ================================ getStringArraySO($rdfGraphN) ================================
	//
	// poziva je fja getSubjectsObjects()
	//
	// Ulazni parametri: $rdfGraphN - ime RDF fajla
	//
	// Na osnovu naziva rdf fajla koji je dobila, ova funkcija otvara RDF fajl,
	// izvlaci iz njega sve iskaze (tj sve subjekte i objekte u obliku niza stringova)
	// ova fja vraca niz stringova (subjekte i objekte)
	//
	function getStringArraySO($rdfGraphN)
	{
	
		$subjectsObjects = "";
	
		$rdfGraphName = $rdfGraphN;
	
		$rdfGraph = ModelFactory::getDefaultModel();
	
		$exists = file_exists($rdfGraphName);
	
		// ucitavanje modela
		if($exists==true)
		{
			// ovde se prosledi ime modela, tj putanja i ime
			$rdfGraph->load($rdfGraphName);
		}
	
		$m = $rdfGraph->find(NULL, NULL, NULL);
	
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
		
		$rdfGraph->close();
	
		$arraySO = explode(' ', $subjectsObjects);
		$nizSubObj = array_unique($arraySO);
	
		return $nizSubObj;
	
	}

	// ================================ getText() ================================
	//
	// ajax odgovor
	// poziva je fja sa klijenta getTextFromServer(tFileName)
	//
	// Ulazni parametri: $textFileName - ime fajla sa tekstom - stize kroz url u okvicu $_POST
	//
	// Na osnovu naziva fajla sa tekstom koji je dobila, ova funkcija cita sadrzaj tog fajla i
	// stampa (echo) taj sadrzaj ka klijentu
	//
	function getText()
	{
		$textFileName = $_POST['textFile'];
		$textFromFile = $this->readText($textFileName);
		echo $textFromFile;
	}
	
	// ================================ readText($par) ================================
	//
	// poziva je fja getText()
	//
	// Ulazni parametri: $par - ime fajla sa tekstom
	//
	// Na osnovu naziva fajla sa tekstom koji je dobila, ova funkcija cita sadrzaj tog fajla i
	// vraca ga kao rezultat poziva fje
	//
	function readText($par)
	{
		$this->load->helper('file');
		$textFromFile = read_file("./textFiles/" . $par);
		return $textFromFile;
	}
	

}

?>