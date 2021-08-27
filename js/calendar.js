var calendarBody;
var month;
var year;
var today;
var mesi = ["Gennaio","Febbraio","Marzo","Aprile","Maggio","Giugno","Luglio","Agosto","Settembre","Ottobre","Novembre","Dicembre"];
var orario = ["10:00","11:00","12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00"];
var usedDays = [];

/*
    Questa funzione viene chimata all'apertura della pagina, serve per costruire per la prima volta
    il calendario, a partire dal mese e dall'anno in cui ci si trova. Infatti, dopo averli recuperati, chiama
    la funzione buildCalendar
*/

function set(){
    calendarBody = document.getElementsByTagName("tbody")[0];
    today = new Date();
    year = today.getFullYear();
    month = today.getMonth();
    buildCalendar();
}

/*
    Questa funzione serve per costruire un calendario all'interno di una matrice,
    grazie alla quale si potrà poi fare la stampa
*/
function buildCalendar(){
    calendarMatrix = [[],[],[],[],[],[]];       //Matrice che conterrà il celendario

    //Recupero il primo giorno del mese
    firstOfMonth = new Date(year,month,1);

    //Inizializzo a 0 le 6 righe della matrice (6 è il numero massimo di righe che si possono avere in
    //un calendario mensile)
    for(i=0; i<6; i++){
        for(j=0; j<7; j++) calendarMatrix[i][j]=0;
    }

    //currentDay sarà la variabile con cui mi scorro i giorni dall'1 del mese in corso fino all'1 del mese successivo
    currentDay=firstOfMonth;
    firstDay=firstOfMonth.getDay();

    //L'oggetto date in JS fa si che la Domenica abbia come giorno della settimana 0.
    //Io voglio che il Lunedì sia 0, la Domenica 7, in modo da agevolare la costruzione della matrice
    firstDay--;
    if(firstDay==-1) firstDay=6;

    //Vado avanti con i giorni finché il mese è quello giusto
    while(currentDay.getMonth()==month){
        day = currentDay.getDay();
        date = currentDay.getDate();

        //Stessa considerazione fatta per firstDay
        day--;
        if(day==-1) day=6;

        //day mi individua la colonna in cui mi trovo (cioè quale giorno della settimana ho tra le mani)
        //(firstDay+date-1)/7) mi permette di trovae la riga, cioè in quale settimana mi trovo
        calendarMatrix[Math.floor((firstDay+date-1)/7)][day] = currentDay.getDate();
        currentDay.setDate(date+1);
    }

    //Rimuovo eventuali righe non utilizzate dalla matrice
    for(i=5; i>=0; i--){
        if(calendarMatrix[i][0]==0){
            calendarMatrix.splice(i,1);
        }
        else{
            break;
        }
    }

    //Prima di stampare, vado a prendere i giorni che sono di ambulatorio
    getUsedDates(calendarMatrix,month,year);
}

/*
    Si tratta di una richiesta al database che mi recupera i giorni segnati 'di ambulatorio'
    per il mese in corso, scartando quelli già trascorsi
*/
function getUsedDates(calendarMatrix) {
    var xhttp; 
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            usedDays = JSON.parse(this.responseText);
            printCalendar(calendarMatrix);
            return;
      }
    };
    xhttp.open("GET", "./../dbUtility/getUsedDates.php?month="+month+"&year="+year, true);
    xhttp.send();
}

/*
    A partire dalla matrice che fa da calendario e dai giorni che ho recuperato,vado a stamparlo
*/  
function printCalendar(calendarMatrix){
    while(calendarBody.children[0]){
        calendarBody.removeChild(calendarBody.children[0]);
    }
    deleteAppointments();
    title = document.getElementById("calendarTitle").getElementsByTagName("h2")[0];
    title.innerText = mesi[month]+" "+year      //Cambio il titolo

    //Ogni riga della matrice è un tr nella tabella in html
    for(i=0; i<calendarMatrix.length; i++){
        tr = document.createElement("tr");
        for(j=0; j<7; j++){
            td = document.createElement("td");

            //calendarMatrix[i][j]==0 vuol dire che l'elemento è vuoto, ossia che è uno di quelli che,
            //nella stessa settimana, appartengono ad un mese diverso
            if(calendarMatrix[i][j]!=0){
                td.innerText = calendarMatrix[i][j];

                //Verifico se il giorno è passato
                if  (today.getFullYear()>year ||
                    (today.getFullYear()==year && today.getMonth()>month) ||
                    (today.getFullYear()==year && today.getMonth()==month && today.getDate()>calendarMatrix[i][j]))td.setAttribute("class","past");
                
                //Se il giorno non è passato, gli assegno la funzione che da una parte mi stampa gli eventuali appuntamenti,
                //dall'altra mi permette di aggiungere il giorno di ambulatorio / modificarne lo stato
                else{
                    (function(d,m,y){
                        td.onclick = function(){getAppointments(d,m,y)}
                    })(calendarMatrix[i][j],month,year);
                }

                //Verifico se è già un giorno tra quelli di ambulatorio
                for(k=0; k<usedDays.length; k++){
                    usedDay= new Date(usedDays[k]['data']);
                    if(usedDay.getDate()==calendarMatrix[i][j]){
                        if(usedDays[k]['stato']==1){
                            td.setAttribute("class","accessibleDay");
                        }
                        else td.setAttribute("class","removedDay");
                        break;
                    }
                }
            }
            else{
                td.setAttribute("class","empty");
            }
            tr.appendChild(td);
        }
        calendarBody.appendChild(tr);
    }
}

/*
    Funzioni per andare al mese successivo/precedente
*/
function next(){
    month++;
    if(month==12){
        month=0;
        year++;
    }
    buildCalendar();
}
function prev(){
    month--;
    if(month==-1){
        month=11;
        year--;
    }
    buildCalendar();
}

/*  
    Prmia di effettuare la stampa, devo richiedere dal database gli appuntamenti del giorno
*/
function getAppointments(day,month,year){
    var xhttp; 
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            result = JSON.parse(this.responseText);
            printAppointments(result,day,month,year);
            return;
      }
    };
    xhttp.open("GET", "./../dbUtility/getAppointments.php?day="+day+"&month="+(month+1)+"&year="+year, true);
    xhttp.send();
}

function printAppointments(apponintments,day,month,year){
    container = document.getElementById("examinationsContainer");
    deleteAppointments();
    document.getElementById("examinationsTitle").getElementsByTagName("h2")[0].innerText = year + '-' + (month+1) + '-' + day;
    //Come per gli articoli, volendo usare appendChild devo partire dall'ultimo, quindi scorro all'indietro

        /*
            Ho reso globale l'array che contiene i giorni di ambulatorio per il mese corrente.
            Grazie a questo, posso recuperare immediatamaente se il giorno selezionato è tra quelli
            già presenti oppure no, e modificare di conseguenza le azioni disponibili.
        */
    var prevDay=-1;
    for(k=0; k<usedDays.length; k++){
        usedDay= new Date(usedDays[k]['data']);
        if(usedDay.getDate()==day){
            prevDay=k;
            break;
        }
    }
    if(prevDay!=-1){
        orarioAmbulatorio = document.createElement("div");
        orarioAmbulatorio.setAttribute("class","infoCalendar");
        orarioAmbulatorio.innerText ="Orario ambulatorio: " + usedDays[k]['orarioInizio'].slice(0, -3) + " - " + usedDays[k]['orarioFine'].slice(0, -3);
        container.appendChild(orarioAmbulatorio);
    }
    for(i=apponintments.length-1; i>=0; i--){
        //Creo il contenitore dell'appuntamento
        //A seconda dello stato dell'appuntamento (che è anche lo stato del giorno), imposto un colore piuttosto che un altro
        apponintment = document.createElement("div");
        if(apponintments[i]['stato']==1) apponintment.setAttribute("class","appointmentAdminPage futureExamination");
        else apponintment.setAttribute("class","appointmentAdminPage deletedExamination");

        //Elemento che contiene Nome e codice Fiscale
        nameUser = document.createElement("h3");
        nameUser.innerText = apponintments[i]['nome'] + " "+ apponintments[i]['cognome'] ;
        nameUser.setAttribute("class","nameAppointmentAdminPage");
        CFUser = document.createElement("h3");
        CFUser.innerText = apponintments[i]['codiceFiscale'] ;
        CFUser.setAttribute("class","nameAppointmentAdminPage");

        //Elemento che contiene l'ora della vistia
        time = document.createElement("h4");
        time.innerText = "Orario Visita: "+apponintments[i]['fasciaOraria'].slice(0, -3);
        time.setAttribute("class","timeAppointmentAdminPage");
        apponintment.appendChild(nameUser);
        apponintment.appendChild(CFUser);
        apponintment.appendChild(time);

        //Nel caso in cui ci siano note, le mostro, altrimenti no
        if(apponintments[i]['note']!=""){
            note = document.createElement("p");
            note.innerText = apponintments[i]['note'];
            note.setAttribute("class","noteAppointmentAdminPage");
            apponintment.appendChild(note);
        }
        container.insertBefore(apponintment,container.children[2]);
    }

    changeButton = document.createElement("button");
    //Caso in cui debba aggiungere il giorno di ambulatorio
    if(prevDay==-1){
        addRange();
        changeButton.innerText="Aggiungi giorno di ambulatorio";
        (function(date){
            changeButton.onclick = function(){
                answer = confirm("Vuoi aggiungere il giorno di ambulatorio?");
                if(answer){
                    checkValuesNewDay(date);
                }
            }
        })(year+'-'+(month+1)+'-'+day);
    }
    //Caso in cui debba modificare lo stato del giorno di ambulatorio
    else{
        if(usedDays[prevDay]['stato']==1) changeButton.innerText="Rimuovi giorno di ambulatorio";
        else changeButton.innerText="Abilita giorno di ambulatorio";
        (function(prevDay){
            changeButton.onclick = function(){
                answer = confirm("Vuoi modificare lo stato del giorno di ambulatorio?");
                if(answer){
                    window.location = "./../dbUtility/changeStatusDay.php?date="+usedDays[prevDay]['data'];
                }
            }
        })(prevDay);
    }
    changeButton.setAttribute("class","deleteButtonCalendarPage")
    container.appendChild(changeButton);
}

//Per eliminare i precedenti appuntamenti dal DOM
function deleteAppointments(){
    container = document.getElementById("examinationsContainer");
    prevAppointments = container.getElementsByClassName("appointmentAdminPage");
    while(prevAppointments[0]){
        container.removeChild(prevAppointments[0]);
    }
    //elimino anche i bottoni
    document.getElementById("examinationsTitle").getElementsByTagName("h2")[0].innerText = "Non hai ancora selezionato un giorno";
    deleteButtons = container.getElementsByClassName("deleteButtonCalendarPage");
    while(deleteButtons[0]){
        container.removeChild(deleteButtons[0]);
    }
    range = container.getElementsByClassName("selectCalendarPage");
    while(range[0]){
        container.removeChild(range[0]);
    }
    orarioDiv = container.getElementsByClassName("infoCalendar");
    while(orarioDiv[0]){
        container.removeChild(orarioDiv[0]);
    }
}

function addRange(){
    inizioDiv = document.createElement("div");
    inizioDiv.setAttribute("class","infoCalendar");
    inizioDiv.innerText ="Orario di apertura";
    fineDiv = document.createElement("div");
    fineDiv.setAttribute("class","infoCalendar");
    fineDiv.innerText ="Orario di chiusura";
    selectStart = document.createElement("select");
    selectEnd = document.createElement("select");
    container = document.getElementById("examinationsContainer");
    option = document.createElement("option");
    option.value = "-";
    option.innerText="-";
    selectStart.appendChild(option);
    for(i=0; i<orario.length-1; i++){
        option = document.createElement("option");
        option.value = i;
        option.innerText=orario[i];
        selectStart.appendChild(option);
    }
    selectStart.onchange = changeEnd;
    selectStart.setAttribute("class","selectCalendarPage");
    selectEnd.setAttribute("class","selectCalendarPage");
    selectEnd.disabled=true;
    selectEnd.value="-";
    container.appendChild(inizioDiv);
    container.appendChild(selectStart);
    container.appendChild(fineDiv);
    container.appendChild(selectEnd);
}

function changeEnd(){
    selectStart = document.getElementsByClassName("selectCalendarPage")[0];
    selectEnd = document.getElementsByClassName("selectCalendarPage")[1];
    options = selectEnd.getElementsByTagName("option");
    while(options[0]) selectEnd.removeChild(options[0]);
    selectEnd.disabled=false;
    for(i=Number(selectStart.value)+1; i<orario.length; i++){
        option = document.createElement("option");
        option.value = i;
        option.innerText=orario[i];
        console.log(i);
        selectEnd.appendChild(option);
    }
}

function checkValuesNewDay(date){
    selectStart = document.getElementsByClassName("selectCalendarPage")[0];
    selectEnd = document.getElementsByClassName("selectCalendarPage")[1];
    if(selectEnd.value=='-' || selectStart.value>=selectEnd.value || selectStart.value=='-'){
        alert("Si controlli di aver inserito correttamente l'orario di ambulatorio");
        return;
    }
    window.location = "./../dbUtility/newDay.php?date="+date+"&start="+orario[selectStart.value]+"&end="+orario[selectEnd.value];
}

/*
    GLI STESSI CONTROLLI PER LA CORRETTEZZA DEGLI ORARI PRIMA DELL'INSERIMENTO SONO FATTI
    ANCHE LATO SERVER
*/