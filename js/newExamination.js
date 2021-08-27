function set(){
    dataInput = document.getElementById("data");
    dataInput.onchange = function(){
        getRangeAndUsed(dataInput.value);
    }
}

function getRangeAndUsed(data){
    removeOld();
    if(data=="-") return;
    orario.disabled=false;
    var xhttp; 
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            rangeHours=[];
            result = JSON.parse(this.responseText);
            //Il primo elemento dell'array che ottengo mi permette di ricavare l'orario di apertura
            //e di chisura dell'ambulatorio del dato giorno. Inizio isolando e togliendo i secondi dal
            //formato
            rangeHours[0]=result[0]['orarioInizio'].slice(0, -3);
            rangeHours[1]=result[0]['orarioFine'].slice(0,-3);
            usedHoursString=[];
            //gli altri orari, che sono quelli già utilizzati, li metto in un altro array,
            //in modo da poterli sottrarre alla lista complessiva degli orari disponibili
            for(i=1; i<result.length; i++){
                usedHoursString[i-1]=result[i]['orarioInizio'].slice(0, -3)
            }
            changeHour(rangeHours,usedHoursString);
      }
    };
    xhttp.open("GET", "./../dbUtility/getRangeAndUsedTime.php?data="+data, true);
    xhttp.send();
}

function removeOld(){
    orario = document.getElementById("orario");
    while(orario.firstChild) orario.removeChild(orario.firstChild);
}
/*
    SPIEGAZIONE DEL FUNZIONAMENTO:
    poniamo che lo studio apra alle 15 e chiuda alle 17. Supponendo che una visita duri 15 minuti, i
    possibili orari per le prenotazioni sono:
    15:00; 15:15; 15:30; 15:45;
    16:00; 16:15; 16:30; 16:45;

    Se dal database ho ricavato che ci sono delle prenotazioni per le 15:30 e per le 16:15,
    allora dovrò mostrare solo gli orari

    15:00; 15:15; 15:45;
    16:00; 16:30; 16:45;

*/

function changeHour(rangeHours,usedHoursString){
    start = rangeHours[0].split(':');
    end = rangeHours[1].split(':');
    //ricavo, in forma numerica, l'inizio e la fine in termini di ore e minuti
    start[0] = Number(start[0]);
    start[1] = Number(start[1]);
    end[0] = Number(end[0]);
    end[1] = Number(end[1]);
    usedHours = [];
    //faccia la stessa cosa per tutti gli orari occupati
    for(i=0; i<usedHoursString.length; i++){
        usedHoursString[i] = usedHoursString[i].split(':');
        hours = Number(usedHoursString[i][0]);
        minutes = Number(usedHoursString[i][1]);
        usedHours.push([hours,minutes]);
    }
    possileHours = [];
    //genero tutti i possibili orari per come visto prima
    while(start[0]<end[0] || (start[0]==start[0] && start[1]<end[1])){
        possileHours.push([start[0],start[1]]);
        start[1]+=15;
        if(start[1]==60){
            start[1]=0;
            start[0]++;
        }
    }
    //effettuo la rimozione
    for(i=0; i<usedHours.length; i++){
        for(index=0; index < possileHours.length; index++){
            //elimino se sono uguali
            if(possileHours[index][0]==usedHours[i][0] && possileHours[index][1]==usedHours[i][1]){
                possileHours.splice(index,1);
                break;
            }
        }
    }
    for(i=0; i<possileHours.length; i++){
        option = document.createElement("option");
        if(possileHours[i][1]==0) possileHours[i][1]='00';
        value = possileHours[i][0]+":"+possileHours[i][1];
        option.value = value;
        option.innerText = value;
        orario.insertBefore(option,orario.firstChild);
    }
}


/*
    Lato server, ci sono delle funzionalità che verificano che la visita abbia come minuti
    00,15,30 o 45, e che l'orario dela visita, oltre a non collidere con un altro orario,
    sia nei termini dell'orario di ambulatorio
*/