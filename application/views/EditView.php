<? ob_start(); ?>
<html>
<head>
<title>Edit mode</title>

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
div#content
{
position:inherit;
left:inherit;
top:inherit;
}
</style>
</head>

<body class="bodyClass">

<!------------------------------------- jQuery biblioteke  ------------------------------------->

<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery-1.7.2.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery-ui.min.js');?>"></script>


<script type="text/javascript" src="<?php echo base_url('/assets/js/findAndReplaceDOMText.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('/assets/js/jsFunctions.js');?>"></script>

<!------------------------- topDiv, gornji div u kome su dva diva: gornji levi div i gornji desni div ------------------------->
<div id='topDiv'>

	<!------------------------- topDivLeft, gornji levi div u kome su forme za upload Text i Rdf fajlova ------------------------->
	<div id='topDivLeft'>

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

<?php   
        //$page = file_get_contents('http://en.wikipedia.org/wiki/Computer_science');
        
       // $pos1 = strpos($page, "<head>");
       // $pos2 = strpos($page, "</head>");
        
        //$rest1 = substr($page, $pos1, $pos2);
     //   echo $rest1;
        $this->load->library("simple_html_dom");
        
        $html = file_get_html("http://en.wikipedia.org/wiki/Computer_science");

     //echo  $html->getElementsByTagName("head")->innertext;
        
        
        /*
        
        $dom = new DOMDocument;
        $dom->loadHTML($html_content);
        
        function preg_replace_dom($regex, $replacement, DOMNode $dom, array $excludeParents = array()) {
        	if (!empty($dom->childNodes)) {
        		foreach ($dom->childNodes as $node) {
        			if ($node instanceof DOMText &&
        					!in_array($node->parentNode->nodeName, $excludeParents))
        			{
        				$node->nodeValue = preg_replace($regex, $replacement, $node->nodeValue);
        			}
        			else
        			{
        				preg_replace_dom($regex, $replacement, $node, $excludeParents);
        			}
        		}
        	}
        }
        
        preg_replace_dom('/match this text/i', 'IT WORKS', $dom->documentElement, array('div'));
        
        */
 
        foreach($html->find('link') as $element)
        {
        	echo $element->outertext . '<br>';
        }
        
       foreach($html->find('style') as $element)
       {
        	echo $element->outertext . '<br>';
       }
        //echo $html->getElementById("content");
        
        echo $html->getElementById("content");
      /*  $ht = $html->find('body',0);
       $h =  $ht->find('div[content]');
        echo $h-*/
        
      /*  foreach($ht->find('div[content]') as $element)
        {
        	echo $element->outertext . '<br>';
        }*/
        
      //  echo $h;
        
        // Find all images
     /*   foreach($html->find('img') as $element)
        	echo $element->src . '<br>';
        
        // Find all links
        foreach($html->find('a') as $element)
        	echo $element->href . '<br>';*/
        
        
        
        
        
        
       // $pos1 = strpos($page, "<!-- content -->");
      //  $pos2 = strpos($page, "<!-- /content -->");
        
      //  $rest = substr($page, $pos1, $pos2);
      //  echo $rest;
        
      echo "<script>
        
        span();
        makeDraggableDroppable();
        
        </script>";
?>

 <script>
 
	//span();
	//makeDraggableDroppable();
	
</script>
 
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