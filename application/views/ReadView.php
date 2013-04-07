<? ob_start(); ?>
<html>
<head>
<title>Read mode</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/css/main.css');?>" />

<style type="text/css">

.bodyClass {
	background-image:url('<?php echo base_url();?>bg.jpg');
}


</style>

<script type="text/javascript" >

 var config = {
     base_url: "<?php echo base_url(); ?>",
     site_url: "<?php echo site_url(); ?>",
     controller: "ReadController",
     opposite_controller: "EditController"
 };
 
//alert(config.base_url);
</script>

</head>
<body class="bodyClass">

<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery-1.7.2.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery-ui.min.js');?>"></script>

<script type="text/javascript" src="<?php echo base_url('/assets/js/findAndReplaceDOMText.js');?>"></script>

	
<script type="text/javascript" src="<?php echo base_url('/assets/js/Funkcije.js');?>"></script>

<script type="text/javascript" src="<?php echo base_url('/assets/js/browserdetect.js');?>"></script>


<div id='topDiv'>

	<div id='topDivLeft'>

			
			<!-- FORMA ZA UPLOAD TXT FAJLA -->
			
			
			<form id="formTextUploadId" name="formText" action="<?php echo site_url('/ReadController/uploadTextFile');?>" method="POST" enctype="multipart/form-data" >
			
			<div id="divTextUploadId">
			Dodajte tekst: 
			<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
			<input name="filesText" id="filesTextId" size="27" type="file" />
			<input type="button" name="action" value="Upload" onclick='uploadTextFile();'/> 
			<iframe id='iFrameTextId' name='iFrameText' src="" style="display:none;" >
			</iframe>
			</div>
			
			</form>
			
			<!-- FORMA ZA UPLOAD RDF FAJLA -->
			
			
			<form id="formRdfUploadId" name="form" action="<?php echo site_url('/ReadController/uploadRdfGraph');?>" method="POST" enctype="multipart/form-data" >
			
			<div id="divRdfUploadId">
			Dodajte rdf model:
			<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
			<input name="filesRdf" id="fileRdfId" size="27" type="file" />
			<input type="button" name="action" value="Upload" onclick='uploadRdfGraph();'/> 
			<iframe id='iFrameRdfId' name='iFrameRdf' src=""  style="display:none;">
			</iframe>
			</div>
			
			</form>
	
	</div>
	
	<div id='topDivRight'>
	
			<a id="linkID" href="<?php echo site_url('/EditController/index');?>" > EDIT MODE </a>
			
			
			<form>
			
			<span id="downloadSpanId" style="display: none;">
			<input id="downloadButtonId" type="button" value="Download RDF" onClick="downloadRDF();">
			</span>
			
			</form>
	</div>


</div>

<div id='mainDiv'></div>

<div id='bottomDiv'></div>

	
	<?php 

		// pri promeni iz Edit u Read mod, ovo se radi pri ucitavanju Read pogleda
	
			if(isset ($rdfGraphName))
			{
				print ("<script> rdfGraphName =\"". $rdfGraphName . ".rdf\"; $(\"#downloadSpanId\").show(); </script>");
				
				if(isset ($textFileName))
				{
					
					print("<script> document.getElementById('linkID').href = \"" . site_url('/EditController/index') . "\";</script>");
					
					
					print ("<script> textFileName =\"". $textFileName . "." . $textFileType . "\"; </script>");
					
					print("<script> var str1 = textFileName;
			
					var n = str1.search(\".txt\");
			
					var textFileNameWithoutExtension = textFileName; ");
						
					print("
					if(n!=-1)
					{
						// jeste txt fajl
						textFileNameWithoutExtension = textFileName.split(\".txt\")[0];
						textFileType = \"txt\";
					}
					else
					{
						// jeste html fajl
						textFileNameWithoutExtension = textFileName.split(\".html\")[0];
						textFileType = \"html\";
					}");
						
					// koji je tip fajla txt ili html
					print("var strLink = \"textFileType/\" + textFileType + \"/\";");
					

					print("setLinkForOppositeMode(\"/textFileName/\" + textFileNameWithoutExtension + \"/\" + strLink +\"rdfGraphName/\" + rdfGraphName.split(\".rdf\")[0]); </script>");
				}
				else
				{
					print("<script> document.getElementById('linkID').href = \"" . site_url('/EditController/index') . "\";</script>");
					print("<script> setLinkForOppositeMode(\"/rdfGraphName/\" + rdfGraphName.split(\".rdf\")[0]);</script>");	
				}
			}
			
			if(isset ($textFileName))
			{
				print ("<script> textFileName =\"". $textFileName . "." . $textFileType . "\"; loadTextFile(\"". $textFileName . "." . $textFileType . "\"); </script>");
				
				
				if(isset ($rdfGraphName))
				{
					print("<script> document.getElementById('linkID').href = \"" . site_url('/EditController/index') . "\";</script>");
					
					print("<script> var str1 = textFileName;
					
					var n = str1.search(\".txt\");
					
					var textFileNameWithoutExtension = textFileName; ");
					
					print("
					if(n!=-1)
					{
						// jeste txt fajl
						textFileNameWithoutExtension = textFileName.split(\".txt\")[0];
						textFileType = \"txt\";
					}
					else
					{
						// jeste html fajl
						textFileNameWithoutExtension = textFileName.split(\".html\")[0];
						textFileType = \"html\";
					}");

					// koji je tip fajla txt ili html
					print("var strLink = \"textFileType/\" + textFileType + \"/\";");
					
					print("setLinkForOppositeMode(\"/textFileName/\" + textFileNameWithoutExtension + \"/\" + strLink + \"rdfGraphName/\" + rdfGraphName.split(\".rdf\")[0]);</script>");

				}
				else
				{
					print("<script> document.getElementById('linkID').href = \"" . site_url('/EditController/index') . "\";</script>");
					
					print("<script> var str1 = textFileName;
			
					var n = str1.search(\".txt\");
			
					var textFileNameWithoutExtension = textFileName; ");
						
					print("
					if(n!=-1)
					{
						// jeste txt fajl
						textFileNameWithoutExtension = textFileName.split(\".txt\")[0];
						textFileType = \"txt\";
					}
					else
					{
						// jeste html fajl
						textFileNameWithoutExtension = textFileName.split(\".html\")[0];
						textFileType = \"html\";
					}");
						
					// koji je tip fajla txt ili html
					print("var strLink = \"textFileType/\" + textFileType;");

					print("setLinkForOppositeMode(\"/textFileName/\" + textFileNameWithoutExtension + \"/\" + strLink);</script>");
				}
			}

	?>
		
</body>
</html>
<? ob_flush(); ?>