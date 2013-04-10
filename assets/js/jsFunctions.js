		////////////////////////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////    PROMENLJIVE    /////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////
		
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
		
		
		
		////////////////////////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////    FUNKCIJE    ////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////

		
		/////////////////////////    DEO SA POSTAVLJANJEM LINKA ZA OPPOSITE MOD     ////////////////////////////

		
		// funkcija koja postavlja link ka Read modu, tj na vec napravljeni link dodaje string koji se prosledi
		function setLinkForOppositeMode(str)  
		{
			//postavljanje osnovne putanje za link EDIT MODE, posle se samo dodaju imeTXT fajla, rdfGraphName
			$("#linkID").attr("href", config.site_url + "/" + config.opposite_controller + "/index" + str);
		}

		// poziv funkcije sa praznim stringom
		setLinkForOppositeMode("");
	
		
		// funkcija koja postavlja link ka rdf kako bi mogao da bude downloadovan klikom na dugme
		function setDownloadLinkForRdfGraph()
		{
			// kreiranje linka
			$(location).attr('href', config.base_url + rdfGraphName);
			 
			// skrivanje obavestenja o downloadu, bice prikazano tek nakon izmene
			$("#downloadMessageSpanId").hide();
		}

	
		
		////////////////////////////////////////////////////////////////////////////////////////////////////
		///////////////////////////////     FUNKCIJE ZA UPLOAD FAJLOVA   ///////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////
		
		// funkcija za upload RDF fajla
		function uploadRdfGraph()
		{
			document.getElementById('formRdfUploadId').target = 'iFrameRdf'; //'iFrameRdf' is the name of the iframe
			document.getElementById('formRdfUploadId').submit();
			
			rdfGraphName = $('#fileRdfId').val().split('\\').pop();

			addFileNamesToLink();
			 
			 if(config.controller=="ReadController")
			 {
				span();
				
				$("#downloadSpanId").show();
			 }
		}
		
		
		// funkcija za upload txt fajla
		function uploadTextFile()
		{ 
			document.getElementById('formTextUploadId').target = 'iFrameText'; //'iFrameText' is the name of the iframe
			document.getElementById('formTextUploadId').submit();

			textFileName = $('#filesTextId').val().split('\\').pop();

			addFileNamesToLink();
			
			// pozivanje funkcije koja ucitava tekst iz fajla na serveru
			getTextFromServer(textFileName);
		}
		
		
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

	
	////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////     FUNKCIJA ZA DRAG & DROP      ///////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////

	// funkcija koja recima daje drag & drop funkcionalnost
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
		  	var s = ui.draggable.html();
		  	var o = $(this).html();
		//	alert("Subjekat = " + s + " i Objekat = " + o);
			
			if(config.controller=="EditController")
			{
				// dodavanje forme za upis nove veze
				writeToBottomDivRight(s,o);
				
				// ispisivanje postojecih veza
				writeToBottomDivLeft(s,o);

				// alert("Spustio sam " + s + " na " + o);
			}
			else if (config.controller=="ReadController")
			{
				// ispisivanje postojecih veza
				writeToBottomDiv(s,o);
			}
			
			
	  	  }
	}
	
	
	////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////     FUNKCIJE ZA INTERAKCIJU SA SERVEROM     ////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////
	
	// funkcija koja dodaje formu za upis nove veze u donji desni div
	function writeToBottomDivRight(s,o)
	{
		subject=s;
		object=o;

		$("#bottomDivRight").html("<form name='form'> "
									+ s + " <input type='text' id='predicateId' name='predicate' /> " + o + " <br />" +
											"<input type='button' onclick='sendSubjectObjectPredicate(this.parentNode); ' value='Sacuvaj' />" +
								 "</form>");
	}

	// funkcija koja salje subjekat i objekat serveru, i rezultat od servera upise u donji div levo
	function writeToBottomDivLeft(s,o)
	{
		subject=s;
		object=o;

		// slanje subjekta i objekta serveru ajax funkcijom, i upisivanja rezultata koje vrati u donji div levo
		sendSubjectObject();
	}

	// SAMO ZA READ MODE
	function writeToBottomDiv(s,o)
	{
		subject=s;
		object=o;

		// slanje subjekta i objekta serveru
		sendSubjectObject();
	}

	///////////////////////////////     AJAX FUNKCIJE     ///////////////////////////////////////////////
	
	// Ajax fja za slanje subjekta serveru, kako bi dobili sve objekte za koje je vezan!!!!!
	function sendSubject()
	{
		$.ajax({
			  type: "POST",
			  url: rdfController + "/getObjects",
			  data: {	
				  		s: subject,
						rdfGraph: rdfGraphName
			  		}
			}).done(function( response ) {

			  $(response).css("background-color", "yellow");
				
			});
	}

// Ajax fja za slanje subjekta i objekta serveru, kako bi dobili postojece veze izmedju njih
	
	function sendSubjectObject()
	{
		
		$.ajax({
			  type: "POST",
			  url: rdfController + "/getPredicate",
			  data: { 	s: subject,
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
				
					$("#bottomDiv").html(response);
				}
				
			});
	}
	
	
	// Ajax fja za slanje subjekta, objekta i predikta serveru
	
	function sendSubjectObjectPredicate(form)
	{
		predicate = form.predicate.value;
		
 		setRdfGraphName();
 		
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
					// ovde se obradjuje odgovor na zahtev koji se salje Ajaxom!
		    		 
			  		$("#bottomDivRight").html($("#bottomDivRight").html() + response);
	
			  		// nakon upisivanja veze na serveru poziva se fja koja ponovo salje zahtev serveru sa odgovarajucim subjektom i objektom
			  		// kako bi sve veze bile upisane u donji levi div
			  		writeToBottomDivLeft(subject,object);
	
			  		// posto je dodata nova veza u odgovarajucu promenljivu upisujemo TRUE
			 		rdfGraphIsChanged = true;
	
			 		// prikazuje se obavestenje da je moguce skinuti novu verziju modela
			 		downloadMessage();

			 		addFileNamesToLink();
					
				});
		
		}
	}
	
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
	
	// Ajax fja za citanje teksta iz fajla na serveru
	function getTextFromServer(tFileName)
	{
		$.ajax({
			  type: "POST",
			  url: rdfController + "/getText",
			  data: { 	textFile: tFileName,
						rdfGraph: rdfGraphName
			  		}
		
			}).done(function( response ) {

				$("#mainDiv").html(response);
				 
				span();
				
			});
	}
	
	
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
	
	// Ajax fja za citanje subjekata i objekata sa servera, a zatim spanovanje svih subjekata i objekata u tekstu
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
			  
				makeDraggableDroppable();
				
			});
	}

	
	// funkcija koja prikazuje obavestenje o downloadu rdf modela kao i dugme za download
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
	
