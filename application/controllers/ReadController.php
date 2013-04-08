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
			$this->load->view('ReadView', $data);
		}
		else
		{
			$this->load->view('ReadView', array('error' => ' ' ));
		}
	}

}

?>