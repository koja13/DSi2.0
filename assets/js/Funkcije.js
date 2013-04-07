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

		
		////////////////////////////////////////////////////////////////////////////////////////////////////
		///////////////////////////////     FUNKCIJE ZA UPLOAD FAJLOVA   ///////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////
		
		

		// funkcija za upload txt fajla
		function uploadTextFile()
		{ 
			 document.getElementById('formTextUploadId').target = 'iFrameText'; //'iFrameText' is the name of the iframe
			 document.getElementById('formTextUploadId').submit();


			 // poziv ajax funkcije da ucita tekst iz uploadovanog fajla
			 textFileName = $('#filesTextId').val();

			 var n = textFileName .split("\\"); 
			 textFileName  = n[2];

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

		
		
		// SAMO U READ MODU SE KORISTI, zbog toga sto kad se ucita rdf graf mora da se ucita tekst ponovo <<<<<<<<<<<<<<<<<<<----------------------------
		
		// fukcija koja se poziva klikom na dugme za upload rdf modela

		/*function upRDF()
		{
			// poziva fju koja uplaoduje rdf model
			redirect();

			// ukoliko postoji vec ucitan fajl sa tekstom 
			if(imeTXTfajla!="")
			{
				// ucitaj ponovo tekst iz fajl sa tekstom na serveru
				ucitajTxt(imeTXTfajla);
			}
		}*/


////////////////////////// MOZDA NECE DA TREBA OVA FJA, PROVERI <<<<<<<<<<<<<<<<<-----------------------	
	/*
	// funkcija koja se poziva na klik dugmeta Upload, procita fajl i posalje sadrzinu serveru
	function procitajTxt()
	{
		if (files) 
	    {
	        
	        for (var i=0, f; f=files[i]; i++) 
	        {
		          var r = new FileReader();
		          
	        r.onload = (function(f)
	        {
	            return function(e)
	            {

	            	// uzminje imena fajla
	            	imeTXTfajla = $('#files').val();
					var n =imeTXTfajla.split("\\"); 
					imeTXTfajla = n[2];

					// provera koji je tip fajla txt ili html pa zatim postavljanje linka ka read modu u zavisnosti od toga
					 var str1 = imeTXTfajla; 
					 var n = str1.search(".txt");
		
					 var imeFajlaSaTekstom = imeTXTfajla;
					 
					 if(n!=-1)
					 {
						// jeste txt fajl
						 imeFajlaSaTekstom = imeTXTfajla.split(".txt")[0];
						 tipTxtFajla = "txt";
					 }
					 else
					 {
						 // jeste html fajl
						 imeFajlaSaTekstom = imeTXTfajla.split(".html")[0];
						 tipTxtFajla = "html";
					 }
		
		
					// koji je tip fajla txt ili html
					 var strLink = "textFileType/" + tipTxtFajla + "/";


					postaviLinkZaReadMode("/textFileName/" + imeFajlaSaTekstom + "/" + strLink);
					

					// ukoliko nije uploadovan model, daj mu ime txt fajla
	            	if(rdfGraphName=="")
	            	{
		            	//n = imeFajlaSaTekstom;
		            	rdfGraphName = imeFajlaSaTekstom + ".rdf";

		            	//postaviLinkZaReadMode("rdfGraphName/" + rdfGraphName.split(".rdf")[0] + "/");
		            	
		            	//alert(rdfGraphName);
		            }

	            	// preuzimanje sadrzine fajla u promenljivu
	                var contents = e.target.result;

	                // slanje textra serveru ajax funkcijom
	                posaljiTextServeru(contents);

	                // pozivanje funkcije koja recima daje drag & drop funkcionalnost
	                drag_drop_fja();

	            };
	        })(f);

	        	r.readAsText(f);
	    	}   
		}
	    else {
		      alert("Failed to load files"); 
			 }
	}
*/
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
		
	var xmlhttp;
	
		if (window.XMLHttpRequest)
		{
			  // code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp=new XMLHttpRequest();
		}
		else
		{	
			// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
	  
	  	xmlhttp.onreadystatechange=function()
	  	{
	 	 if (xmlhttp.readyState==4 && xmlhttp.status==200)
	    	{
	      		// ovde se obradjuje odgovor na zahtev koji se salje Ajaxom!

		  		// postavljanje zute boje kao pozadinske boje za sve reci koje vrati server kao objekte u vezi sa poslatim subjektom
		 		$(xmlhttp.responseText).css("background-color", "yellow");
	
	    	}
	  };
	
	  // slanje POST zahteva serveru, specificiran je url kontrolera + naziv fje u kontroleru koja treba da obradi zahtev
	  	xmlhttp.open("POST",config.site_url+"/"+config.controller+"/getObjects",true);
	
	  	// podesavanje headera zahteva
		xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

		// var sub=subject;
		// var iModela = rdfGraphName;
		 
		//slanje subjekta i naziva modela serveru
		xmlhttp.send("s="+subject+"&rdfGraph="+rdfGraphName);
	}

// Ajax fja za slanje subjekta i objekta serveru, kako bi dobili postojece veze izmedju njih
	
	function sendSubjectObject()
	{
		
	var xmlhttp;
	
		if (window.XMLHttpRequest)
		{
			  // code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp=new XMLHttpRequest();
		}
		else
		{	
			// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
	  
	  	xmlhttp.onreadystatechange=function()
	  	{
	 	 if (xmlhttp.readyState==4 && xmlhttp.status==200)
	    	{
	      		// ovde se obradjuje odgovor na zahtev koji se salje Ajaxom!

				if(config.controller=="EditController")
				{
					// obrise se sve iz donjeg levog diva
					$("#bottomDivLeft").empty();

					// u donji div levo se upisu sve veze koje vrati server
					document.getElementById("bottomDivLeft").innerHTML+=xmlhttp.responseText;
		
				}
				else if (config.controller=="ReadController")
				{
				
					$("#bottomDiv").empty();
					document.getElementById("bottomDiv").innerHTML+=xmlhttp.responseText;
		
				}
			
	      		
	    	}
	  };
	
	  // slanje POST zahteva serveru, specificiran je url kontrolera + naziv fje u kontroleru koja treba da obradi zahtev
	  	xmlhttp.open("POST",config.site_url+"/"+config.controller+"/getPredicate",true);
	
	  	// podesavanje headera zahteva
		xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

		// var sub=subject;
		// var obj=object;
		// var iModela = rdfGraphName;
		
		//slanje subjekta, objekta i naziva modela serveru
		xmlhttp.send("s="+subject+"&o="+object+"&rdfGraph="+rdfGraphName);
	}
	
	
	// Ajax fja za slanje subjekta, objekta i predikta serveru
	
	function sendSubjectObjectPredicate(form)
	{
		
	var xmlhttp;
	
		if (window.XMLHttpRequest)
		{
			  // code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp=new XMLHttpRequest();
		}
		else
		{	
			// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
	  
	  	xmlhttp.onreadystatechange=function()
	  	{
	 	 if (xmlhttp.readyState==4 && xmlhttp.status==200)
	    	{
	      		// ovde se obradjuje odgovor na zahtev koji se salje Ajaxom!
	    		 
		  		document.getElementById("bottomDivRight").innerHTML+=xmlhttp.responseText;

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
	
	    	}
	  };
	
	  // slanje POST zahteva serveru, specificiran je url kontrolera + naziv fje u kontroleru koja treba da obradi zahtev
	  	xmlhttp.open("POST",config.site_url+"/" + config.controller + "/writeStatement",true);
	
	  	// podesavanje headera zahteva
		xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

		// var sub=subject;
		// var obj=object;

		 // uzimanje predikta iz forme za unos nove veze
		 predicate = form.predicate.value;

		 // upisivanje predikta u lokalnu promenljivu
		 //var pred=predicate;

		 // upisivanje imena modela u lokalnu promenljivu
		// var iModela = rdfGraphName;

		 // ukoliko je nesto uneseno u polje za unos nove veze
		if(predicate!="")
		{
			//slanje teksta kontroleru
			xmlhttp.send("s="+subject+"&o="+object+"&p="+predicate+"&rdfGraph="+rdfGraphName);
		}
	}

	
	////////////////////////// MOZDA NI OVA FJA NECE DA TREBA OVA FJA, PROVERI <<<<<<<<<<<<<<<<<-----------------------	

	/*
	// Ajax fja za slanje teksta serveru, nakon citanja teksta iz lokalnoh txt ili html fajla
	function posaljiTextServeru(p)
	{
		
	var xmlhttp;
	
		if (window.XMLHttpRequest)
		{
			  // code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp=new XMLHttpRequest();
		}
		else
		{	
			// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
	  
	  	xmlhttp.onreadystatechange=function()
	  	{
	 	 if (xmlhttp.readyState==4 && xmlhttp.status==200)
	    	{
	      		// ovde se obradjuje odgovor na zahtev koji se salje Ajaxom!
	    		 
				 if(config.controller=="upload")
				{
				 // tekst koji je poslat serveru je stavljen u spanove, pa se upisuje i srednji div
		  		document.getElementById("srednjidiv").innerHTML=xmlhttp.responseText;

///////////////////////
		  		findAndReplaceDOMText(
		  				/\w+/g,
		  				srednjidiv,
		  				function(fill, matchIndex) {
		  					var el = document.createElement('span');
		  					el.setAttribute("class", "dragdrop");
		  					el.innerHTML = fill;
		  					return el;
		  				}
		  			);
//////////////////////

				}
				else if (config.controller=="ReadController")
				{
				  document.getElementById("srednjidiv").innerHTML=xmlhttp.responseText;

				}
				 
	      		// recima u tekstu se daje drag & drop funkcionalnost
				drag_drop_fja();
	    	}
	  };
	
	  // slanje POST zahteva serveru, specificiran je url kontrolera + naziv fje u kontroleru koja treba da obradi zahtev
	  	xmlhttp.open("POST",config.site_url+"/"+config.controller+"/getText",true);
	
	  	// podesavanje headera zahteva
		xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	
		// postavljanje teksta u promenljivu
		var str = p;

		// upisivanje imena fajla sa tekstom u lokalnu promenljivu
		var imeFajla = imeTXTfajla;
		
		//slanje teksta kontroleru, i slanje imena fajla sa tekstom
		xmlhttp.send("str="+str+"&textFile="+imeFajla);
	}

	
	
	*/

	// Ajax fja za citanje teksta iz fajla na serveru
	function getTextFromServer(tFileName)
	{
		
	var xmlhttp;
	
		if (window.XMLHttpRequest)
		{
			  // code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp=new XMLHttpRequest();
		}
		else
		{	
			// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
	  
	  	xmlhttp.onreadystatechange=function()
	  	{
	 	 if (xmlhttp.readyState==4 && xmlhttp.status==200)
	    	{
	      		// ovde se obradjuje odgovor na zahtev koji se salje Ajaxom!
				 
				// procitani tekst se upisuje u srednji div
				
				document.getElementById("mainDiv").innerHTML=xmlhttp.responseText;
			 
				span();
	    	}
	  };
	
	  // slanje POST zahteva serveru, specificiran je url kontrolera + naziv fje u kontroleru koja treba da obradi zahtev
	  	xmlhttp.open("POST",config.site_url+"/"+config.controller+"/getText",true);
	
	  	// podesavanje headera zahteva
		xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

		// upisivanje imena fajla sa tekstom u lokalnu promenljivu
		//var imeFajla = tFileName;

		// upisivanje naziva rdf modela u lokalnu promenljivu
		//var imeMod = rdfGraphName;
		
		//slanje imena fajla sa tekstom i naziva modela serveru
		xmlhttp.send("textFile="+tFileName+"&rdfGraph="+rdfGraphName);
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
		
	var xmlhttp;
	
		if (window.XMLHttpRequest)
		{
			  // code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp=new XMLHttpRequest();
		}
		else
		{	
			// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
	  
	  	xmlhttp.onreadystatechange=function()
	  	{
	 	 if (xmlhttp.readyState==4 && xmlhttp.status==200)
	    	{
	      		// ovde se obradjuje odgovor na zahtev koji se salje Ajaxom!

				var regex = new RegExp(xmlhttp.responseText, 'gi');

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

	    	}
	  };
	
	  // slanje POST zahteva serveru, specificiran je url kontrolera + naziv fje u kontroleru koja treba da obradi zahtev
	  	xmlhttp.open("POST",config.site_url+"/"+config.controller+"/getSubjectsObjects",true);
	
	  	// podesavanje headera zahteva
		xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

		//var imeMod = rdfGraphName;
		
		xmlhttp.send("rdfGraph="+rdfGraphName);

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
	
