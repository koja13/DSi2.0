<?php

	class ReadModel extends CI_Model
	{
		function readText($par)
		{
			$this->load->helper('file');
			$tekstIzFajla = read_file("./textFiles/" . $par);
			return $tekstIzFajla;
		}
		
		function writeText($tekst, $imeFajla)
		{
			
			$this->load->helper('file');
			$data = $tekst;
			
			if ( ! write_file('./textFiles/' . $imeFajla, $data))
			{
				echo 'Unable to save file to the server!';
			}
			else
			{
				//echo 'File written!';
			}
			
		}

	}

?>