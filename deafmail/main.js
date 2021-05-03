        const button = document.getElementById("button");
        const main = document.getElementsByTagName("main")[0];
        var synth = window.speechSynthesis;
        var msg = new SpeechSynthesisUtterance();
        var number = {'zero' : 0,'one' : 1,'two' : 2,'three' : 3,'four' : 4,'five' : 5,'six' : 6,'seven' : 7,'eight' : 8,'nine' : 9,};
        var logininput= ['username','password','submit'];
        var loginmsg=["Please say your username in the phonetic alphabet, using dash to separate digits. press space to speak then press space to stop ",
        			"Please say your password in the phonetic alphabet, using dash to separate digits. press space to speak then press space to stop "
        	];
        var registerinput= ['username','password','gmail','gmailpsd','submit'];
        var registermsg=["Please say your username in the phonetic alphabet, using dash to separate digits. press space to speak then press space to stop ",
        				"Please say your password in the phonetic alphabet, using dash to separate digits. press space to speak then press space to stop ",
        				"Please say your gmail in the phonetic alphabet, using dash to separate digits, do not say @gmail.com press space to speak then press space to stop ",
        				"Please say your gmail passwordin the phonetic alphabet, using dash to separate digits. press space to speak then press space to stop "
        	];
        var homeinput= ['input','submit'];	
        var homemsg=["Which option do you want to use. Please say Profile, Inbox, Compose or exit." 				
        	];
       	var composeinput= ['recipient','header','body','submit'];
        var composemsg=["Please say your A gmail address in the phonetic alpabet. do not say @gmail.com. press space to speak then press space to stop  ",
        			"Please say your header. press space to speak then press space to stop ",
					"Please say your body. press space to speak then press space to stop "
        	];

        function phoneticToString(phonetic){
        	var value = phonetic.match(/\b(\w)/g);
        	value = value.join('');
        	return value.toLowerCase();
	   	};
	   	function speakmessage(textmsg,milliseconds) {
            msg.text =textmsg;
            synth.speak(msg);
 			const date = Date.now();
  			let currentDate = null;
  			do {
   				 currentDate = Date.now();
 			 } while (currentDate - date < milliseconds);
		}
		/** based on code by PHIL NASH https://www.twilio.com/blog/speech-recognition-browser-web-speech-api **/
        let step = 1;
        let confirmation = false;
        const SpeechRecognition =
        window.SpeechRecognition || window.webkitSpeechRecognition;

        if (typeof SpeechRecognition !== "undefined") {
          const recognition = new SpeechRecognition();

          //starts voice recognition 
          const stop = () => {
            main.classList.remove("speaking");
            recognition.stop();
            button1.value = "Start listening";
          };

          //ends voice recognition 
          const start = () => {
            main.classList.add("speaking");
            recognition.start();
            button1.value = "Stop listening";
          };

          //voice to string
          const onResult = event => {
          	console.log(pageinput[stage-1]);
            result.innerHTML = "";
            for (const res of event.results) {
              const text = document.createTextNode(res[0].transcript);
              const p = document.createElement("p");
              if (res.isFinal) {
                p.classList.add("final");
              }
              p.appendChild(text);
              //if it is an input then it stores the final result adding php
              if (pageinput[stage-1] == "input"){
              	result.value = p.innerHTML;
              	document.submit.action = result.value + ".php";
              	console.log("php")
              //if it is textbody it keeps adding sentences
              }else if((pageinput[stage-1] == "body")||(pageinput[stage-1] == "header")){
              	console.log("body")
              	result.value = (p.innerHTML);
              	console.log(result);
              //else use phonetic alphabet 
              }else{
              	result.value = phoneticToString(p.innerHTML);
              }
            }
          };
          
          //recognition settings
          recognition.continuous = true;
          recognition.interimResults = true;
          currenturl = document.URL;
          //sets values depending on page 
          switch(true){
          	case /login.html/.test(currenturl):
          		pageinput = logininput;
          		pagemsg= loginmsg; 		    		
          	break;
          	case /register.html/.test(currenturl):
	          	pageinput = registerinput;
	          	pagemsg= registermsg;
	         break;
	         case /home.php/.test(currenturl):
	          	pageinput = homeinput;
	          	pagemsg= homemsg;
	        break;
	        case /compose.php/.test(currenturl):
	      		pageinput = composeinput;
	      		pagemsg= composemsg;
	      	break;
          }
		//stage keeps track of which input box its on. final stage is the submision
          stage = 1; 
          recognition.addEventListener("result", onResult);
          		document.addEventListener('keyup', event=>{
          			if (event.code==='Space'){
          				
          				result = document.getElementById(pageinput[stage-1]);
          					//stage 1 speaks message, stage 2 and 3 sto and start voice record. step 4 asks for confirmation, step 5 checks confiration 
	          				switch(step){
	          				case 1:
	          					console.log(step);
	          					speakmessage(pagemsg[stage-1],5000);
	          					step++;
	          				break;
	          				case 2:
	          					console.log(step);
	          					 start();
	          					 step++;
	          				break;
	          				case 3:
	          					console.log(step);
	          					  stop()
	          					  step++;
	          					  speakmessage(result.value,0);
	          				break;
	          				case 4:
	          				console.log(step);
	          					if ((pageinput[stage-1]=='gmail')||(pageinput[stage-1]=='recipient')){
	          						result.value = (result.value +"@gmail.com")
	          					}
	          					speakmessage("Presss Y then space if this is what you wanted to input. Press just space to input value again ",4500);
	          					step++;
	          				break;
	          					
	          				case 5:
	          				console.log(step);
	          					if (confirmation){
	          						speakmessage("confirmed choice ",1000);
	 
	          						stage++;
	          						
	          					}else{
	          						speakmessage("confirmed decline ",1000);
	          						console.log('submit');

	          					}
	          					confirmation = false;	
	          					step = 1;      					
	          				break;
	          				
	          				}
	          				if (pageinput[stage-1]== 'submit') {
          					document.submit.submit();
          					} 
          			
	            		
          			} else if (event.code==='KeyY'){
          				console.log('KeyY');
          			confirmation = true;			
          			}
          		
          		})
          	
          

          

        } else {
          button.remove();
          const message = document.getElementById("message");
          message.removeAttribute("hidden");
          message.setAttribute("aria-hidden", "false");
        };