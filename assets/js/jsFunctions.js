		// GLOBALNE  PROMENLJIVE

		// promenljive subjekat, objekat i predikat
		var subject;
		var object;
		var predicate;

		// bool promenljiva koja sluzi sa proveru da li je rdf fajl izmenjen, tj da li su dodate nove veze, ukoliko jesu potrebno je izbaciti obavestenje o novoj verziji fajla
		var rdfGraphIsChanged = false;
		
		// promenljiva koja cuva ime fajla sa tekstom, inicijalno je prazan string
		var textFileName = "";
		
		// promenljiva koja cuva tip fajla sa tekstom (txt ili html), inicijalno je prazan string
		var textFileType = "";
		
		// promenljiva koja cuva ime rdf modela, inicijalno je prazan string
		var rdfGraphName = "";
		
		// promenljiva koja cuva link ka rdf kontroleru, koristi se kod ajax poziva
		var rdfController = config.site_url + "/RdfController";
		

		// FUNKCIJE
		
		
		// ========================= setLinkForOppositeMode(str) ========================
		//
		// funkcija koja postavlja link ka Edit/Read modu, tj na vec napravljeni link dodaje string koji se prosledi
		// poziva se prilikom ucitavanja js fajla
		// poziva je funkcija addFileNamesToLink(), prilikom dodavanja naziva fajlova u link ka opposite modu
		// ulazni parametar je: str - string koji se dodaje na link
		//
		function setLinkForOppositeMode(str)  
		{
			//postavljanje osnovne putanje za link ka Edit/Read modu, i dodavanje prosledjenog stringa tom linku
			$("#linkID").attr("href", config.site_url + "/" + config.opposite_controller + "/index" + str);
		}
		// poziv funkcije sa praznim stringom, zbog kreiranja linka prilikom ucitavanja strane
		setLinkForOppositeMode("");
	
	
		// ========================= setDownloadLinkForRdfGraph() ========================
		//
		// funkcija koja postavlja link ka rdf kako bi mogao da bude downloadovan klikom na dugme
		// aktivira je onClick event button-a za download
		// 
		function setDownloadLinkForRdfGraph()
		{
			// kreiranje linka
			$(location).attr('href', config.base_url + rdfGraphName);
			 
			// skrivanje obavestenja o downloadu, bice prikazano tek nakon izmene
			$("#downloadMessageSpanId").hide();
		}

	
		
		//
		// FUNKCIJE ZA UPLOAD FAJLOVA
		//
		
		// ========================= uploadRdfGraph() ========================
		//
		// funkcija za upload RDF fajla
		// poziva se aktiviranjem onclick eventa input file polja za upload rdf fajla
		//
		function uploadRdfGraph()
		{
			// koriscenje iframe za uplaod fajla, kako ne bi doslo do refreshovanja stranice
			document.getElementById('formRdfUploadId').target = 'iFrameRdf'; //'iFrameRdf' is the name of the iframe
			document.getElementById('formRdfUploadId').submit();
			
			// ucitavanje naziva rdf fajla u globalnu promenljivu
			rdfGraphName = $('#fileRdfId').val().split('\\').pop();

			// dodavanje naziva fajlova u link ka opposite modu
			addFileNamesToLink();
			
			// prikazuje dugme za download ukoliko je
			 if(config.controller=="ReadController")
			 {
				$("#downloadSpanId").show();
			 }
		}
		
		// ========================= uploadTextFile() ========================
		//
		// funkcija za upload fajla sa tekstom
		// poziva se aktiviranjem onclick eventa input file polja za upload fajla za tekstom
		//
		function uploadTextFile()
		{ 
			// koriscenje iframe za uplaod fajla, kako ne bi doslo do refreshovanja stranice
			 document.getElementById('formTextUploadId').target = 'iFrameText'; //'iFrameText' is the name of the iframe
			 document.getElementById('formTextUploadId').submit();

			// ucitavanje naziva fajla sa tekstom u globalnu promenljivu
			textFileName = $('#filesTextId').val().split('\\').pop();

			// dodavanje naziva fajlova u link ka opposite modu
			addFileNamesToLink();
		}

		// postavljanje handler-a za onClick event input polja za upload text fajla
		$(document).ready(function() {
		    $("#textUploadId").click(function() {
		    	uploadTextFile();
		    });
		});

		// postavljanje handler-a za onClick event input polja za upload rdf fajla
		$(document).ready(function() {
		    $("#rdfUploadId").click(function() {
		    	uploadRdfGraph();
		    });
		});
		
		
		// ========================= addFileNamesToLink() ========================
		//
		// funkcija koja dodaje nazive fajla sa tekstom, ekstenzije fajla sa tekstom i naziva rdf fajla na link za opposite mod
		// pozivaju je funkcije uploadTextFile() i uploadTextFile() prilikom uploadovanja fajlova
		// poziva se i nakon odgovora servera na ajax zahtev sendSubjectObjectPredicate(form)
		// zbog kreiranja novog rdf fajla sa istim imenom kao fajl sa tekstom ukoliko rdf fajl prethodno nije ucitan a uneta je nova veza
		//
		function addFileNamesToLink()
		{ 
			if(textFileName=="")
			 {
				if(rdfGraphName!="")
				{
					 // ukoliko tekstualni fajl ne postoji onda se na link dodaje samo naziv modela
					setLinkForOppositeMode("/rdfGraphName/" + rdfGraphName.split(".rdf")[0]);	
				}
			 }
			 else
			 {
				// ukoliko tekstualni fajl postoji

				// trazi se .txt u nazivu
				var isTxtFile = textFileName.search(".txt");

				// u lokalnoj promenljivoj se cuva naziv fajla sa tekstom
				var textFileNameWithoutExtension = "";
				 
				if(isTxtFile!=-1)
				{
					// jeste txt fajl
					textFileNameWithoutExtension = textFileName.split(".txt")[0];
					textFileType = "txt";
				}
				else
				{
					// jeste html fajl
					textFileNameWithoutExtension = textFileName.split(".html")[0];
					textFileType = "html";
				}

				if(rdfGraphName=="")
				{
					// ukoliko ne postoji model onda se link kreira tako da se salje ime fajla sa tekstom, tip fajla sa tekstom, respektivno
					setLinkForOppositeMode("/textFileName/" + textFileNameWithoutExtension + "/textFileType/" + textFileType + "/");
				}
				else
				{
					// kreiranje linka za read mod tako da se salje ime fajla sa tekstom, tip fajla sa tekstom, naziv modela, respektivno
					setLinkForOppositeMode("/textFileName/" + textFileNameWithoutExtension + "/textFileType/" + textFileType + "/rdfGraphName/" + rdfGraphName.split(".rdf")[0]); 
				}

			}
		}

	//
	// FUNKCIJA ZA DRAG & DROP
	//
		
	// ========================= makeDraggableDroppable() ========================
	//
	// funkcija koja recima daje drag & drop funkcionalnost
	// pozivaju je funkcije spanEditMode() i spanReadMode(), nakon stavljanja reci u spanove daje im se drag&drop funkcionalnost
	// ova funkcija takodje kreira event handlere za hover, za pocetak prevlacenja i za kraj prevlacenja reci
	//
	function makeDraggableDroppable()
	{
		  // podesavanje promene kursora kad stane iznad reci koja moze da se prenese
		  $(".dragdrop").hover(function() {
			
			$(this).css('cursor','move');
			
			}, function() {
			
			$(this).css('cursor','auto');
			
			});
		  
		  // drag-drop deo 
	      $(".dragdrop").draggable( 
	              {
	                  containment: '#content',
	                  cursor: 'move',
	                  snap: '#content',
					  revert: true,
					  start: HandleDragStart,
					  stop: HandleDragStop
	       		 } );
	
	      $(".dragdrop").droppable( 
	              {
	  	    		drop: handleDropEvent
	  	  		 } );
				 
		

	      // handler za pocetak prevlacenja
	      function HandleDragStart( event, ui )
	      {
	    	  // potrebno je dobiti sve reci sa kojima je u vezi podignuta rec
	    	  
	    	  // rec koju smo podigli
	    	  var s = $(this).html();

	    	  subject = s;
	    	  
	    	  // slanje subjekta serveru ajax funkcijom
	    	  sendSubject();
		  }

	 	  // handler za kraj prevlacenja
	      function HandleDragStop( event, ui )
	      {
	    	  $("span").css("background-color", "transparent");
		  }

	      // handler za spustanje reci
	  	  function handleDropEvent( event, ui )
	  	  {
		  	var s = ui.draggable.html(); // pokupi rec koja je stigla, to ce biti recenicni subjekat
		  	var o = $(this).html(); // pokupi rec na koju je spusteno (to je this), to je recenicni objekat

		  	// alert("Subjekat = " + s + " i Objekat = " + o);
			
			if(config.controller=="EditController")
			{
				// dodavanje forme za upis nove veze
				writeToBottomDivRight(s,o);
				
				// ispisivanje postojecih veza
				writeToBottomDivLeft(s,o);
			}
			else if (config.controller=="ReadController")
			{
				// ispisivanje postojecih veza
				writeToBottomDiv(s,o);
			}
	  	  }
	}
	
	
	//
	// FUNKCIJE ZA INTERAKCIJU SA SERVEROM
	//
	
	// ========================= writeToBottomDivRight(s,o) ========================
	//
	// funkcija koja dodaje formu za upis nove veze u donji desni div
	// poziva je event handler za spustanje reci na rec handleDropEvent( event, ui )
	// ulazni parametri su: s - subjekat, podignuta rec
	//						o - objekat, rec na koju je podignuta rec spustena
	// 
	function writeToBottomDivRight(s,o)
	{
		subject=s;
		object=o;

		$("#bottomDivRight").html("<form name='form'> "
									+ s + " <input type='text' id='predicateId' name='predicate' /> " + o + " <br />" +
											"<input type='button' onclick='sendSubjectObjectPredicate(this.parentNode); ' value='Sacuvaj' />" +
								 "</form>");
	}

	// ========================= writeToBottomDivLeft(s,o) ========================
	//
	// funkcija koja salje subjekat i objekat serveru, i rezultat od servera upise u donji div levo
	// poziva je event handler za spustanje reci na rec handleDropEvent( event, ui )
	// ulazni parametri su: s - subjekat, podignuta rec
	//						o - objekat, rec na koju je podignuta rec spustena
	// 
	function writeToBottomDivLeft(s,o)
	{
		subject=s;
		object=o;

		// slanje subjekta i objekta serveru ajax funkcijom, i upisivanja rezultata koje vrati u donji div levo
		sendSubjectObject();
	}

	// ========================= writeToBottomDiv(s,o) ========================
	//
	// funkcija koja salje subjekat i objekat serveru, i rezultat od servera upise u donji div
	// poziva je event handler za spustanje reci na rec handleDropEvent( event, ui ), ukoliko je u pitanju Read mode
	// ulazni parametri su: s - subjekat, podignuta rec
	//						o - objekat, rec na koju je podignuta rec spustena
	// 
	function writeToBottomDiv(s,o)
	{
		subject=s;
		object=o;

		// slanje subjekta i objekta serveru
		sendSubjectObject();
	}

	//
	// AJAX FUNKCIJE
	//
	
	// ========================= sendSubject() ========================
	//
	// Ajax fja za slanje subjekta serveru, kako bi dobili sve objekte za koje je vezan
	// poziva je event handler za pocetak prenosenja reci handleDragStart( event, ui )
	// 
	function sendSubject()
	{
		$.ajax({
			// post zahtev je u pitanju
			  type: "POST",
			  // link ka kome se upucuje zahtev, getObjects predstavlja metod na serveru koji ce da odgovori na zahtev
			  url: rdfController + "/getObjects",
			  // podaci koji se salju, nazivi subjekta, rdf grafa
			  data: {	
				  		s: subject,
						rdfGraph: rdfGraphName
			  		}
			}).done(function( response ) {

				// obrada odgovora na zahtev, postavljanje pozadinske boje svim objektima koje dobijemo kao odgovor
			  $(response).css("background-color", "yellow");
				
			});
	}

	
	// ========================= sendSubjectObject() ========================
	//
	// Ajax fja za slanje subjekta i objekta serveru, kako bi dobili postojece veze izmedju njih
	// pozivaju je funkcije writeToBottomDivLeft(s,o) i writeToBottomDiv(s,o)
	// 
	function sendSubjectObject()
	{
		
		$.ajax({
			  type: "POST",
			  url: rdfController + "/getPredicate",
			  data: {
				  		s: subject,
						o: object ,
						rdfGraph: rdfGraphName
			  		}
		
			}).done(function( response ) {

				if(config.controller=="EditController")
				{
					// u donji div levo se upisu sve veze koje vrati server
					$("#bottomDivLeft").html(response);
		
				}
				else if (config.controller=="ReadController")
				{
					// u donji div se upisu sve veze koje vrati server
					$("#bottomDiv").html(response);
				}
				
			});
	}
	
	
	// ========================= sendSubjectObjectPredicate(form) ========================
	//
	// Ajax fja za slanje subjekta, objekta i predikta serveru, kako bi nova veza bila upisana u rdf graf
	// poziva se na onClick event dugmeta "Sacuvaj" (za cuvanje nove veze)
	// ulazni parametar je: form - referenca na formu za cuvanje nove veze
	//
	function sendSubjectObjectPredicate(form)
	{
		// uzimanje unesenog predikta u polju za unos nove veze
		predicate = form.predicate.value;
		
		// postavljanje naziva rdf grafa ukoliko vec nije uploadovan na server
 		setRdfGraphName();
 		
 		// ukoliko nista nije uneseno u polje za novu vezu onda ne radi nista
		if(predicate!="")
		{
			$.ajax({
				  type: "POST",
				  url: rdfController + "/writeStatement",
				  data: { 	s: subject,
							o: object ,
							p: predicate,
							rdfGraph: rdfGraphName
				  		}
			
				}).done(function( response ) {
			
					// ovde se obradjuje odgovor na zahtev koji se salje Ajaxom
		    		 
			  		$("#bottomDivRight").html($("#bottomDivRight").html() + response);
	
			  		// nakon upisivanja veze na serveru poziva se fja koja ponovo salje zahtev serveru sa odgovarajucim subjektom i objektom
			  		// kako bi sve veze bile upisane u donji levi div
			  		writeToBottomDivLeft(subject,object);
	
			  		// posto je dodata nova veza u odgovarajucu promenljivu upisujemo TRUE
			 		rdfGraphIsChanged = true;
	
			 		// prikazuje se obavestenje da je moguce skinuti novu verziju modela
			 		downloadMessage();

			 		// dodavanje imena fajlova u link ka opposite modu
			 		addFileNamesToLink();
					
				});
		
		}
	}

	// ========================= getTextFromServer(tFileName) ========================
	//
	// Ajax fja za citanje teksta iz fajla na serveru (i spanovanje tog teksta)
	// poziva je funkcija uploadTextFile, takodje se poziva prilikom ucitavanja stranice ukoliko je tekst ucitan na server
	// ulazni parametar je: tFileName - naziv fajla sa tekstom na serveru
	//
	function getTextFromServer(tFileName)
	{
		$.ajax({
			  type: "POST",
			  url: rdfController + "/getText",
			  data: { 	
				  		textFile: tFileName
			  		}
		
			}).done(function( response ) {

				// upisivanje procitanog teksta u mainDiv
				$("#mainDiv").html(response);
				 
				// spanovanje teksta
				span();
				
			});
	}
	
	// ========================= setRdfGraphName() ========================
	//
	// Funkcija za postavljanje naziva rdf grafa ukoliko neki graf nije vec ucitan
	// poziva je funkcija sendSubjectObjectPredicate(form) pre nego sto se uputi zahtev serveru
	//
	function setRdfGraphName()
	{
		if(rdfGraphName=="")
 		{
			// trazi se .txt u nazivu
			var isTxtFile = textFileName.search(".txt");
			
			if(isTxtFile!=-1)
			{
				// jeste txt fajl
				textFileNameWithoutExtension = textFileName.split(".txt")[0];
			}
			else
			{
				// jeste html fajl
				textFileNameWithoutExtension = textFileName.split(".html")[0];
			}
			
			rdfGraphName = textFileNameWithoutExtension + ".rdf";
 		}
	}
	
	// ========================= span() ========================
	//
	// Funkcija za spanovanje teksta, u zavisnosti od moda poziva odgovarajucu funkciju za spanovanje
	// poziva je funkcija getTextFromServer(tFileName), i funkcija uploadRdfGraph() samo u slucaju da je u pitanju Read mode
	//
	function span()
	{
		if(config.controller=="EditController")
		{
			spanEditMode();
		
		}
		else if (config.controller=="ReadController")
		{
			spanReadMode();
		}
	}
	
	// ========================= spanEditMode() ========================
	//
	// Funkcija za spanovanje teksta u Edit modu
	// poziva je funkcija span()
	// koristi funkciju findAndReplaceDOMText, definisanu u eksternoj js biblioteci
	// funkcija findAndReplaceDOMText pronalazi reci u tekstu i stavlja ih u html span elemente kojima se daje klasa dragdrop, 
	// \w+/g predsatvlja regular expresion kojim se biraju sve reci u tekstu, mainDiv predstavlja id diva koji u kome se traze reci
	//
	function spanEditMode()
	{
		findAndReplaceDOMText(
			/\w+/g,
			mainDiv,
			function(fill, matchIndex) {
			var el = document.createElement('span');
			el.setAttribute("class", "dragdrop");
			el.innerHTML = fill;
			return el;
			}
		);

		// recima u tekstu se daje drag & drop funkcionalnost
		makeDraggableDroppable();
	}
	
	// ========================= spanReadMode() ========================
	//
	// Ajax funkcija za citanje subjekata i objekata sa servera, pa zatim spanovanje teksta u Read modu
	// poziva je funkcija span()
	// koristi funkciju findAndReplaceDOMText, definisanu u eksternoj js biblioteci
	// funkcija findAndReplaceDOMText pronalazi reci u tekstu i stavlja ih u html span elemente kojima se daje klasa dragdrop, 
	// regular expresion kojim se biraju reci u tekstu dobija se putem ajax zahteva serveru, ajax zahtevom traze se svi subjekti i objekti koje taj rdf fajl sadrzi
	// mainDiv predstavlja id diva koji u kome se traze reci
	//
	function spanReadMode()
	{
			$.ajax({
			  type: "POST",
			  url: rdfController + "/getSubjectsObjects",
			  data: { 	
				  		rdfGraph: rdfGraphName
			  		}
		
			}).done(function( response ) {

				var regex = new RegExp(response, 'gi');

				findAndReplaceDOMText(
					regex,
					mainDiv,
					function(fill, matchIndex) {
					var el = document.createElement('span');
					el.setAttribute("class", "dragdrop");
					el.setAttribute("style", "color:grey");
					el.innerHTML = fill;
					return el;
					}
				);
				
				// recima u tekstu se daje drag & drop funkcionalnost
				makeDraggableDroppable();
				
			});
	}
	
	// ========================= downloadMessage() ========================
	//
	// funkcija koja prikazuje obavestenje o downloadu rdf modela kao i dugme za download
	// poziva je funkcija sendSubjectObjectPredicate(form)
	// nakon unosa nove veze korisniku se ispise obavestenje da je rdf graf izmenjen i dugme za download postanje vidljivo
	//
	function downloadMessage()
	{
		// ukoliko je rdf model izmenjen, tj dodate nove veze, prikazi obavestenje i dugme za download
		if(rdfGraphIsChanged==true)
		{
			$("#downloadSpanId").show();
			
			if(config.controller=="EditController")
			{
				$("#downloadMessageSpanId").show();
			}			
		}	
	}
	
