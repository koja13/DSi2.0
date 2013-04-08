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
		
		var rdfController = config.site_url + "/" + "RdfController";
		////////////////////////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////    FUNKCIJE    ////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////

		
		/////////////////////////    DEO SA POSTAVLJANJEM LINKA ZA READ MOD     ////////////////////////////
		
		//postavljanje osnovne putanje za link READ MODE, posle se samo dodaju imeTXT fajla, rdfGraphName
		//document.getElementById('linkID').href = config.site_url + "/ReadController/index";

		
		//postavljanje osnovne putanje za link EDIT MODE, posle se samo dodaju imeTXT fajla, rdfGraphName
		document.getElementById('linkID').href = config.site_url + "/" + config.opposite_controller + "/index";
		
		
		// funkcija koja postavlja link ka Read modu, tj na vec napravljeni link dodaje string koji se prosledi
		function setLinkForOppositeMode(str)  
		{      
			document.getElementById('linkID').href = document.getElementById('linkID').href + str;
		}

		// poziv funkcije sa praznim stringom
		setLinkForOppositeMode("");



		
		
		// funkcija koja postavlja link ka rdf kako bi mogao da bude downloadovan klikom na dugme
		function setDownloadLinkForRdfGraph()
		{
			// kreiranje linka
			window.location.href = config.base_url + "/" + rdfGraphName;
			 
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

			
			 // dobijanje imena modela nakon klika na dugme upload
			 rdfGraphName = $('#fileRdfId').val();
			 var n = rdfGraphName.split("\\"); 
			 rdfGraphName = n[2];

			 // dobijanje imena modela za firefox, koristeci php funkciju
			// <?php $ua=getBrowser();if($ua['name']=="Mozilla Firefox" ) echo "rdfGraphName = document.getElementById('my_file').value"?>

		
			// dobijanje imena modela za firefox, koristeci javascript biblioteku
			if(BrowserDetect.browser=="Firefox")
			{
				rdfGraphName = document.getElementById('fileRdfId').value;
			}

			 // kreiranje linka za read mod
			 document.getElementById('linkID').href = config.site_url + "/" + config.opposite_controller + "/index";

			 if(textFileName=="")
			 {
				 // ukoliko tekstualni fajl ne postoji onda se na link dodaje samo naziv modela
				setLinkForOppositeMode("/rdfGraphName/" + rdfGraphName.split(".rdf")[0]);
			 }
			 else
			 {
				 // ukoliko tekstualni fajl postoji
				 
				 // uzima se ime fajla iz globalne promenljive
				 var str1 = textFileName; 

				 // trazi se .txt u nazivu
				 var n = str1.search(".txt");

				 // u lokalnoj promenljivoj se cuva naziv fajla sa tekstom
				 var textFileNameWithoutExtension = textFileName;
				 
				 if(n!=-1)
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

				// koji je tip fajla txt ili html
					 var strLink = "textFileType/" + textFileType + "/";
			
				 // kreiranje linka za read mod tako da se salje ime fajla sa tekstom, tip fajla sa tekstom, naziv modela, respektivno
				 setLinkForOppositeMode("/textFileName/" + textFileNameWithoutExtension + "/" + strLink + "rdfGraphName/" + rdfGraphName.split(".rdf")[0]); 
			 }
			 
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


					 // poziv ajax funkcije da ucita tekst iz uploadovanog fajla
					 textFileName = $('#filesTextId').val();

					 var n = textFileName .split("\\"); 
					 textFileName  = n[0];

					 // ovo je samo u slucaju firefoxa, zato sto na drugi nacin uzima naziv fajla
					// <?php $ua=getBrowser();if($ua['name']=="Mozilla Firefox" ) echo "textFileName = document.getElementById('my_fileText').value"?>

					// dobijanje imena fajla sa tekstom za firefox, koristeci javascript biblioteku
					if(BrowserDetect.browser=="Firefox")
					{
						textFileName = document.getElementById('filesTextId').value;
					}
					 
					 // postavljanje linka ka read modu
					 document.getElementById('linkID').href = config.site_url+ "/" + config.opposite_controller + "/index";


					 var str1 = textFileName; 
					 var n = str1.search(".txt");

					 var textFileNameWithoutExtension = textFileName;
					 
					 if(n!=-1)
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


					// koji je tip fajla txt ili html
					 var strLink = "textFileType/" + textFileType;

					 if(rdfGraphName=="")
					 {
						 // ukoliko ne postoji model onda se link kreira tako da se salje ime fajla sa tekstom, tip fajla sa tekstom, respektivno
						 setLinkForOppositeMode("/textFileName/" + textFileNameWithoutExtension + "/" + strLink);
					 }
					 else
					 {
						// kreiranje linka za read mod tako da se salje ime fajla sa tekstom, tip fajla sa tekstom, naziv modela, respektivno
						 setLinkForOppositeMode("/textFileName/" + textFileNameWithoutExtension + "/" + strLink + "/"+"rdfGraphName/" + rdfGraphName.split(".rdf")[0]); 
					 }

					 // pozivanje funkcije koja ucitava tekst iz fajla na serveru
					 loadTextFile(textFileName);

		}
		
		

	// funkcija koja ucitava tekst iz fajla sa servera na osnovu prosledjenog imena fajla
	function loadTextFile(fileName)
	{
					textFileName = fileName;
					
					// poziv ajax funkcije koja procita tekst sa servera na osnovu imena fajla koji joj se prosledi
	                getTextFromServer(fileName);

	             	// pozivanje funkcije koja recima daje drag & drop funkcionalnost
	              //  drag_drop_fja();
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

		document.getElementById("bottomDivRight").innerHTML ="  <form name='form'> " + s + " <input type='text' id='predicateId' name='predicate' /> " + o + " <br /><input type='button' onclick='sendSubjectObjectPredicate(this.parentNode); ' value='Sacuvaj' /> </form>";
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
			  data: { s: subject, rdfGraph: rdfGraphName }
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
					// obrise se sve iz donjeg levog diva
					$("#bottomDivLeft").empty();

					// u donji div levo se upisu sve veze koje vrati server
					document.getElementById("bottomDivLeft").innerHTML+=response;
		
				}
				else if (config.controller=="ReadController")
				{
				
					$("#bottomDiv").empty();
					document.getElementById("bottomDiv").innerHTML+=response;
		
				}
				
			});
	}
	
	
	// Ajax fja za slanje subjekta, objekta i predikta serveru
	
	function sendSubjectObjectPredicate(form)
	{
		predicate = form.predicate.value;
		 		
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
		    		 
			  		document.getElementById("bottomDivRight").innerHTML+=response;
	
			  		// nakon upisivanja veze na serveru poziva se fja koja ponovo salje zahtev serveru sa odgovarajucim subjektom i objektom
			  		// kako bi sve veze bile upisane u donji levi div
			  		writeToBottomDivLeft(subject,object);
	
			  		// posto je dodata nova veza u odgovarajucu promenljivu upisujemo TRUE
			 		rdfGraphIsChanged = true;
	
			 		// prikazuje se obavestenje da je moguce skinuti novu verziju modela
			 		downloadMessage();
	
					// kreiranje linka za read mod
					document.getElementById('linkID').href = config.site_url+"/" + config.opposite_controller + "/index";
	
					if(textFileName=="")
					{
						 // ukoliko ne postoji fajl sa tekstom onda se u link ka read modu upisuje samo ime modela
						 setLinkForOppositeMode("/rdfGraphName/" + rdfGraphName.split(".rdf")[0]);
					}
					else
					{
						// ukoliko postoji i fajl sa tekstom onda...
						
						 var str1 = textFileName; 
						 var n = str1.search(".txt");
	
						 var textFileNameWithoutExtension = textFileName;
						 
						 if(n!=-1)
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
	
						// koji je tip fajla txt ili html
						var strLink = "textFileType/" + textFileType + "/";
	
						 // kreiranje linka za read mod
						 setLinkForOppositeMode("/textFileName/" + textFileNameWithoutExtension + "/" + strLink +"rdfGraphName/" + rdfGraphName.split(".rdf")[0]); 
					}
		
		    	
					
				});
		
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

				document.getElementById("mainDiv").innerHTML=response;
				 
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
	
