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
				// pa se dodaju nazivi fajlova u link ka opposite modu
				print ("<script>
							rdfGraphName =\"". $rdfGraphName . ".rdf\";
						</script>");
			}

			if(isset ($textFileName))
			{
				// ukoliko postoji fajl sa tekstom ucitan na serveru
				
				// upisivanje imena fajla sa tekstom u globalnu promenljivu, i ucitavanje tog teksta iz fajla na serveru
				print ("<script>
							textFileName =\"". $textFileName . "." . $textFileType . "\";
							getTextFromServer(\"". $textFileName . "." . $textFileType . "\");
						</script>");
			}
			
			print ("<script>
						addFileNamesToLink();
					</script>");
?>

</body>
</html>
<? ob_flush(); ?>