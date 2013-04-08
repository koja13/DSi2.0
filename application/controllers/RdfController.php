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

	function getPredicate()
	{
		$sub = $_POST['s'];
		$obj = $_POST['o'];

		$rdfGraphName =/*"modeli/" .*/ $_POST['rdfGraph'];

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

		//$m->close();
		$rdfGraph->close();

	}

	function getObjects()
	{
		$sub = $_POST['s'];

		$rdfGraphName =/*"modeli/" .*/ $_POST['rdfGraph'];

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

		//$m->close();
		$rdfGraph->close();

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
		$uploaddir = './textFiles/';
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

	function getStringArraySO($iModela)
	{
	
		$subjectsObjects = "";
	
		$rdfGraphName =/*"./modeli/" . */$iModela;
	
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
	
		//$m->close();
		$rdfGraph->close();
	
		$arraySO = explode(' ', $subjectsObjects);
		$nizSubObj = array_unique($arraySO);
	
		return $nizSubObj;
	
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
	

	function getText()
	{
		$textFileName = $_POST['textFile'];
		$imeModela = $_POST['rdfGraph'];

		$this->load->model('ReadModel');
		$str = $this->ReadModel->readText($textFileName);

		echo $str;
	}

	function writeText($tekst, $imeFajla)
	{	
		$this->load->model('ReadModel');
		$this->ReadModel->writeText($tekst,$imeFajla);
	}
}

?>