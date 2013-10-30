<?php
define("RDFAPI_INCLUDE_DIR", "././rdfapi-php/api/");
include(RDFAPI_INCLUDE_DIR . "RDFAPI.php");
include_once(RDFAPI_INCLUDE_DIR . "resModel/ResModel.php");
include_once(RDFAPI_INCLUDE_DIR . "ontModel/OntModelP.php");
include_once(RDFAPI_INCLUDE_DIR . "vocabulary/RDF_RES.php");
include_once(RDFAPI_INCLUDE_DIR . "ontModel/RdfsVocabulary.php");

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
		
		
		$ontModel = ModelFactory::getOntModel(MEMMODEL,RDFS_VOCABULARY);
		
		//Create two classes – Department and Courses
		
		$clsDepartment = $ontModel->createOntClass("http://www.geovista.psu.edu#Department");
		$clsCourses = $ontModel->createOntClass("http://www.geovista.psu.edu#Courses");
		
		//Create Some Properties and specify domain and Range
		// Domain tells to which class this property should be assigned
		// Range tells what are acceptable values for this property
		$credit = $ontModel->createOntProperty("http://www.geovista.psu.edu#credit&#8217");
		$credit->addRange(RDF_RES::LITERAL());
		$credit->addDomain($clsCourses);
		
		$statisfy = $ontModel->createOntProperty("http://www.geovista.psu.edu#statisfy&#8217");
		$statisfy->addRange(RDF_RES::LITERAL());
		$statisfy->addDomain($clsCourses);
		
		$requires = $ontModel->createOntProperty("http://www.geovista.psu.edu#requires&#8217");
		$requires->addRange($clsCourses);
		$requires->addDomain($clsCourses);
		
		//Create a sublcass “Geog10″  and Geog410
		$geog10 = $ontModel->createOntClass("http://www.geovista.psu.edu#geog10&#8242");
		$clsCourses->addSubClass($geog10);
		$geog10->setPropertyValue($credit, new ResLiteral("3", "en"));
		$clsCourses->addSubClass($geog10);         //Add Geog10 as subclass of “Courses”
		
		
		$geog410 = $ontModel->createOntClass("http://www.geovista.psu.edu#geog410&#8242");
		$geog410->setPropertyValue($credit, new ResLiteral("3", "en"));
		$geog410->setPropertyValue($requires, $geog10);
		$clsCourses->addSubClass($geog410);
				
		$ontModel->saveAs("mojaOntologija.owl", "rdf");

	}
	
}

?>