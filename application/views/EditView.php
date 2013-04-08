<? ob_start(); ?>
<html>
<head>
<title>Edit mode</title>

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
     controller: "EditController",
     opposite_controller: "ReadController"
 };
 
//alert(config.controller);
</script>

</head>

<body class="bodyClass">

							<!-- jQuery biblioteke -->
<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery-1.7.2.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery-ui.min.js');?>"></script>


<script type="text/javascript" src="<?php echo base_url('/assets/js/findAndReplaceDOMText.js');?>"></script>

<script type="text/javascript" src="<?php echo base_url('/assets/js/jsFunctions.js');?>"></script>

<script type="text/javascript" src="<?php echo base_url('/assets/js/browserdetect.js');?>"></script>


<div id='topDiv'>

	<div id='topDivLeft'>
	
			
			<!-- FORMA ZA UPLOAD TXT FAJLA, ovo je za sve ostale pretrazivace osim Chroma, ukoliko se ustanovi da je Chrome pretrazivac u kome je pokrenuta app onda se ova forma ne prikazuje -->
			
			<form id="formTextUploadId" name="formText" action="<?php echo site_url('/RdfController/uploadTextFile');?>" method="POST" enctype="multipart/form-data" >
			
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
			
			<form id="formRdfUploadId" name="form" action="<?php echo site_url('/RdfController/uploadRdfGraph');?>" method="POST" enctype="multipart/form-data" >
			
			<div id="divRdfUploadId">
			Dodajte rdf model:
			<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
			<input name="filesRdf" id="fileRdfId" size="27" type="file" />
			<input type="button" name="action" value="Upload" onclick='uploadRdfGraph();'/> 
			<iframe id='iFrameRdfId' name='iFrameRdf' src="" style="display:none;" >
			</iframe>
			</div>
			
			</form>

	
	</div>
	
	<div id='topDivRight'>
	
			<!-- link ka Read Modu -->
			<a id="linkID" href="<?php echo site_url('/ReadController/index');?>" > READ MODE </a>
			
			
			
			<!-- Dugme za download rdf modela -->
			<form>
			
			<span id="downloadSpanId" style="display: none;">
			<span id="downloadMessageSpanId" style="color:red; font-weight:bold;"> Nova verzija RDF-a ---->  </span>
			<input id="downloadButtonId" type="button" value="Download RDF" onClick="setDownloadLinkForRdfGraph();">
			</span>
			
			</form>
	</div>

</div>

<div id='mainDiv'></div>

<div id='bottomDiv'>

	<div id='bottomDivLeft'>
	
	</div>
	
	<div id='bottomDivRight'>
	
	</div>

</div>

<?php 

	// pri promeni iz Read moda u Edit mod, ovo se radi pri ucitavanju EditView pogleda
	
			if(isset ($rdfGraphName))
			{
				// ucita se ime modela u globalnu promenljivu
				print ("<script> rdfGraphName =\"". $rdfGraphName . ".rdf\"; </script>");
				
				if(isset ($textFileName))
				{
					// kreiranje linka ka read modu
					print("<script> document.getElementById('linkID').href = \"" . site_url('/ReadController/index') . "\";</script>");
					
					// upisivanje imena fajla sa tekstom u globalnu promenljivu, i ucitavanje tog teksta iz fajla na serveru
					print ("<script> textFileName =\"". $textFileName . "." . $textFileType . "\"; </script>");

					// deo sa kreiranjem linka ka read modu
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
					// ukoliko postoji samo ucitan model a ne i fajl sa tekstom
					
					// kreiranje linka ka read modu
					print("<script> document.getElementById('linkID').href = \"" . site_url('/ReadController/index') . "\";</script>");
					
					// dodavanje imena modela u link ka read modu
					print("<script> setLinkForOppositeMode(\"/rdfGraphName/\" + rdfGraphName.split(\".rdf\")[0]);</script>");	

				}
			}
			
			if(isset ($textFileName))
			{
				// ukoliko postoji fajl sa tekstom ucitan na serveru
				
				// upisivanje imena fajla sa tekstom u globalnu promenljivu, i ucitavanje tog teksta iz fajla na serveru
				print ("<script> textFileName =\"". $textFileName . "." . $textFileType . "\"; loadTextFile(\"". $textFileName . "." . $textFileType . "\"); </script>");
				
				if(isset ($rdfGraphName))
				{
					// ukoliko postoje ucitani i fajl sa tekstom i rdf model
					
					// kreiranje linka ka read modu
					print("<script> document.getElementById('linkID').href = \"" . site_url('/ReadController/index') . "\";</script>");
					
					// deo sa kreiranjem linka ka read modu
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
					// ukoliko je ucitan samo fajl sa tekstom a nije ucitan rdf model
					
					// kreiranje linka ka read modu
					print("<script> document.getElementById('linkID').href = \"" . site_url('/ReadController/index') . "\";</script>");
						
					// deo sa kreiranje linka ka read modu
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
		

					// dodavanje imena fajla i tipa fajla u link ka read modu
					print("setLinkForOppositeMode(\"/textFileName/\" + textFileNameWithoutExtension + \"/\" + strLink);</script>");

				}
			}

	?>

</body>
</html>
<? ob_flush(); ?>