<? ob_start(); ?>
<html>
<head>
<title>Edit mode</title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">

<!------------------------- main.css, ucitavanje stilova iz eksternog css fajla ------------------------->
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

</script>
<style>

#toogleSlideTopDiv{
 position:absolute;
 top:0;
 right:0;
}

#toogleSlideTopDiv{
 position:absolute;
 top:0;
 right:0;
}

</style>
</head>

<body class="bodyClass">

<!------------------------------------- jQuery biblioteke  ------------------------------------->

<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery-1.7.2.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery-ui.min.js');?>"></script>


<script type="text/javascript" src="<?php echo base_url('/assets/js/findAndReplaceDOMText.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('/assets/js/jsFunctions.js');?>"></script>
<!-- 
<button id="toogleSlideTopDiv" name="toogleSlideTopDiv"> :) </button>
--> 


<!------------------------- topDiv, gornji div u kome su dva diva: gornji levi div i gornji desni div ------------------------->
<div id='topDiv'>

<!-- 
	<a id="linkNovi" href="" > READ MODE novi link </a>
-->
	
	<!------------------------- topDivLeft, gornji levi div u kome su forme za upload Text i Rdf fajlova ------------------------->
	<div id='topDivLeft'>
	
			URL strane sa tekstom: 
			<input id="textURL" type="text" name="textURL" value="URL">
			<button id="textUrlButton" name="textUrlButton">Get text</button>
			<br>
			
			<!------------------------- FORMA ZA UPLOAD TXT FAJLA ------------------------->
			
			<form id="formTextUploadId" name="formText" action="<?php echo site_url('/RdfController/uploadTextFile');?>" method="POST" enctype="multipart/form-data" >
			
			<div id="divTextUploadId">
			Dodajte tekst: 
			<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
			<input name="filesText" id="filesTextId" size="27" type="file" />
			<input id="textUploadId" type="button" name="action" value="Upload"/> 
			<iframe id='iFrameTextId' name='iFrameText' src="" style="display:none;">
			</iframe>
			</div>
			
			</form>
			
			<!------------------------- FORMA ZA UPLOAD RDF FAJLA ------------------------->
			
			<form id="formRdfUploadId" name="form" action="<?php echo site_url('/RdfController/uploadRdfGraph');?>" method="POST" enctype="multipart/form-data" >
			
			<div id="divRdfUploadId">
			Dodajte rdf model:
			<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
			<input name="filesRdf" id="fileRdfId" size="27" type="file" />
			<input id="rdfUploadId" type="button" name="action" value="Upload"/> 
			<iframe id='iFrameRdfId' name='iFrameRdf' src="" style="display:none;" >
			</iframe>
			</div>
			
			</form>

	
	</div>

	<!------------------------- topDivRight, gornji desni div u kome su link ka Read modu i dugme za download rdf grafa ------------------------->
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

<!------------------------- mainDiv, centralni div u koji se ucitava tekst ------------------------->

<div id='mainDiv'>
 
</div>
<!------------------------- bottomDiv, donji div u kome su dva diva: donji levi div i donji desni div ------------------------->
<div id='bottomDiv'>

	<!------------------------- bottomDivLeft, donji levi div u kome se prikazuju veze izmedju reci u tekstu ------------------------->
	<div id='bottomDivLeft'>
	
	</div>
	
	<!------------------------- bottomDivRight, donji desni div u kome se prikazuje forma za unos nove veze ------------------------->
	<div id='bottomDivRight'>
	
	</div>

</div>

<!--  
<button id="toogleSlideBottomDiv" name="toogleSlideBottomDiv"> :) </button>
-->
<?php 

			// ovo je bitno zbog prelaza iz Read moda u Edit mod
			// ovo se radi pri ucitavanju EditView pogleda
			
			// ukoliko postoji rdf fajl ucitan na serveru
			if(isset ($rdfGraphName))
			{
				// ucita se ime modela u globalnu promenljivu
				print ("<script>
							rdfGraphName =\"". $rdfGraphName . ".rdf\";
						</script>");
			}
			
			// ukoliko postoji fajl sa tekstom ucitan na serveru
			if(isset ($textFileName))
			{
				// upisivanje imena fajla sa tekstom u globalnu promenljivu, i ucitavanje tog teksta iz fajla na serveru
				print ("<script>
							textFileName =\"". $textFileName . "." . $textFileType . "\";
							getTextFromServer(\"". $textFileName . "." . $textFileType . "\");
						</script>");
			}
			
			// dodaju se nazivi fajlova u link ka Read modu
			print ("<script>
						addFileNamesToLink();
					</script>");
?>

</body>
</html>
<? ob_flush(); ?>