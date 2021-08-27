var prevMessages = [];
var mittente;
var firstRequest = true;
var year;
var month; 
var day;

//All'apertura della pagina, recupero tutti i messaggi della chat, e imposto un interval
function loadMessages(){
    messages=getMessages("all");
    setInterval(
        function(){
            getMessages(10);
        },500);
    //Imposta l'evento alla pressione del tasto di invio di un messaggio
    document.getElementById("sendButton").onclick=sendMessage;
}

//Richiedo howMany messaggi dal database: se l'ingresso è all, allora
//il server me li invia tutti
function getMessages(howMany){
    var xhttp; 
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            messages = JSON.parse(this.responseText);
            compareMessages(messages);
        }
    };
    xhttp.open("GET", "./../dbUtility/getMessages.php?howMany="+howMany, true);
    xhttp.send();
}

//Serve per capire quali messaggi devo inviare:
//infatti, ricevendo 10 messagggi dal server ogni 500ms, molto spesso non vi saranno nuovi
//messaggi da mostrare, oppure uno solo di questi sarà nuvoo 
function compareMessages(messages){
    messagesToPrint = [];
    messages.reverse();
    //Nel caso in cui sia la prima volta che effettuo la richiesta di stampa di messaggi, e l'array di messsaggi
    //che ho è vuoto, allora non devo mostrare alcun messaggio, ma ci sarà un testo che segnala l'assenza.
    if(firstRequest && messages.length==0){
        emptyMessage();
    }
    firstRequest=false;
    //Rimuovo se necessario il testo per l'assenza di messaggi
    if(messages.length!=0) removeEmpty();
    //Tengo salvato ogni volta l'array di messaggi ricevuti precedentemente. Ci saranno alcuni che dovrò
    //Stampare, altri che ho già stampato. Con questo ciclo, li distinguo.
    for(i=0; i<messages.length; i++){
        index = -1;
        for(k=0;k<prevMessages.length; k++){
            if(prevMessages[k]['codiceMessaggio']==messages[i]['codiceMessaggio']){
                index=0;
                break;
            }
        }
        //Vuol dire che ho trovato ALMENO UN MESSAGGIO che devo inviare, quindi procendo alla stampa
        if(index==-1){
            messagesToPrint.push(messages[i]);
        }
    }
    prevMessages=messages;
    printMessages(messagesToPrint);
}

function emptyMessage(){
    emptyMessage = document.createElement("div");
    emptyMessage.innerText="Non ci sono ancora messaggi!";
    emptyMessage.setAttribute("id","emptyMessage");
    document.getElementById("messagesContainer").appendChild(emptyMessage);
}

function printMessages(messages){
    messagesContainer = document.getElementById("messagesContainer");
    for(i=0; i<messages.length; i++){
        thatDate = new Date(messages[i]['timestampInvio']);
        //day, month e year salvano l'ultima data dei messaggi presenti. Se ci sono dei messaggi che non
        //rispodnono a tale data, allora devo mostrare un banner con la data dei messaggi in questione
        if(thatDate.getDate()!=day || thatDate.getMonth()!=month || thatDate.getFullYear()!=year){
            day=thatDate.getDate();
            month=thatDate.getMonth();
            year=thatDate.getFullYear();
            date = document.createElement("div");
            date.innerText=day+"-"+(month+1)+"-"+year;
            date.setAttribute("class","dateChat");
            messagesContainer.appendChild(date);
        }
        message = document.createElement("div");
        //Distinguo il caso che il messaggio sia inviato oppure ricevuto
        if(messages[i]['mittente']==messages[i]['codiceUtente']) message.setAttribute("class","message myMessage");
        else message.setAttribute("class","message reply");
        message.innerText = messages[i]['testo'];
        messagesContainer.appendChild(message);
        //Con questa funzione il div che contiene la chat sarà sempre verso il basso, quindi sul fondo avrò
        //i messaggi più recenti come avviene nei servizi di messaggistica
        updateScroll()
    }
}

function updateScroll(){
    var element = document.getElementById("messagesContainer");
    element.scrollTop = element.scrollHeight;
}



//Funzione che sfruttando AJAX invia il messaggio al database
function sendMessage(){
    testo = document.getElementById("inputChat").value;
    testo = testo.replace(/[\n]/g,"%0A");
    if(testo=="") return;
    document.getElementById("inputChat").value="";
    var xhttp; 
    xhttp = new XMLHttpRequest();
    xhttp.open("GET", "./../dbUtility/sendMessage.php?testo="+testo, true);
    xhttp.send();
}

function removeEmpty(){
    toRemove =document.getElementById("emptyMessage");
    if(toRemove!=null){
        document.getElementById("messagesContainer").removeChild(toRemove);
    }
}

