<?php

	class ReadModel extends CI_Model
	{
		function readText($par)
		{
			$this->load->helper('file');
			$tekstIzFajla = read_file("./tekstovi/" . $par);
			return $tekstIzFajla;
		}
		
		function writeText($tekst, $imeFajla)
		{
			
			$this->load->helper('file');
			$data = $tekst;
			
			if ( ! write_file('./tekstovi/' . $imeFajla, $data))
			{
				echo 'Unable to save file to the server!';
			}
			else
			{
				//echo 'File written!';
			}
			
		}
		/*
		function saveText($str)
		{
			
			$sql = "INSERT INTO Text (text)
			VALUES ('".$str."')";
			
			$this->db->query($sql);
			//$this->load->helper('file');
			//$tekstIzFajla = read_file($par);
			//return $tekstIzFajla;
			
			
		}*/
	}

?>