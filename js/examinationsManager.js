var page=0;                     //A quale pagina mi trovo
var lastContent=false;          //Sono finiti i valori da prendere 
var examinationsForPage=5;      //Numero di examinations per pagina

function showExaminations(next) {
    //Posso andare avanti solo nel caso in cui non sia arrivato, precedentemente, all'ultima pagina
    if(next==1){
        if(lastContent==true) return;
        page++;
    }
    //Posso andare indietro solo se non sono sulla prima pagina
    if(next==0){
        if(page==0) return
        page--;
        lastContent=false;
    }
    var xhttp; 
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            elementsToPrint = JSON.parse(this.responseText);
            //Se l'array ricevuto dal server è vuoto, allora non ho altri articoli e sono arrivato in fondo.
            if(elementsToPrint.length==0){
                lastContent=true;
                page--;
                return;
            }
            printElements(elementsToPrint);
      }
    };
    xhttp.open("GET", "./../dbUtility/getExaminations.php?start="+(page*examinationsForPage)+"&howMany="+examinationsForPage, true);
    xhttp.send();
}

function printElements(elementsToPrint){
    //Devo eliminare i precendenti esami nel DOM
    container = document.getElementById("examinationsContainer");
    examinations = container.getElementsByClassName("examinationUserPage");
    
    while(examinations[0]){
        container.removeChild(examinations[0]);
    }
    
    //Se non sono alla prima pagina, allora ci sono anche i bottoni di scorrimento, che devo eliminare ed inserire nuovamente in fondo
    //(in questo modo, se non ci sono esami, non vengono visualizzati i bottoni di scorrimento)
    if(document.getElementById("buttonExaminationsContainer"))
        container.removeChild(document.getElementById("buttonExaminationsContainer"));
   
    //Come per gli articoli, volendo usare appendChild devo partire dall'ultimo, quindi scorro all'indietro
    for(i=elementsToPrint.length-1; i>=0; i--){
        //Creo il contenitore dell'esame
        examination = document.createElement("div");
        thatDay = new Date(elementsToPrint[i]['data']);
        today= new Date();
        
        //Se lo stato vale 1, allora l'esame è valido
        if(elementsToPrint[i]['stato']==1){
            examination.setAttribute("class","examinationUserPage futureExamination");
        }

        //Se lo stato è 0, allora l'esame è stato cancellato dal dottore
        if(elementsToPrint[i]['stato']==0){
            examination.setAttribute("class","examinationUserPage deletedExamination");
        }

        //Elemento che contiene la data
        data = document.createElement("h3");
        data.innerText = "Data Visita: "+elementsToPrint[i]['data'];
        data.setAttribute("class","dataExaminationUserPage");

        //Elemento che contiene l'ora della vistia
        time = document.createElement("h4");
        time.innerText = "Orario Visita: "+elementsToPrint[i]['fasciaOraria'].slice(0, -3);
        time.setAttribute("class","timeExaminationUserPage");
        examination.appendChild(data);
        examination.appendChild(time);

        //Nel caso in cui ci siano note, le mostro, altrimenti no
        if(elementsToPrint[i]['note']!=""){
            note = document.createElement("p");
            note.innerText = elementsToPrint[i]['note'];
            note.setAttribute("class","noteExaminationUserPage");
            examination.appendChild(note);
        }

        //qualunque sia lo stato della visita, se è passata la mostro in grigio
        if(thatDay<=today){
            examination.setAttribute("class","examinationUserPage pastExamination");
        }
        //Altrienti, devo anche inserire il bottone per l'eliminazioe dell'examination
        //solo nel caso in cui lo stato sia 1 (giorno non eliminato)
        else if(elementsToPrint[i]['stato']!=0){
            deleteButton = document.createElement("button");
            (function(p){
            deleteButton.onclick = function(){
                    answer = confirm("Una volta eliminata la visita, questa non potrà più essere recuperata. Sei sicuro di vole continuare?")
                    if(answer){
                        
                        deleteElement(p['data'],p['codiceUtente']);
                    }

                }
            })(elementsToPrint[i]);
            deleteButton.innerText="╳";
            deleteButton.setAttribute("class","deleteButtonUserPage")
            examination.appendChild(deleteButton);
        }
        //Vado a mettere l'articolo tra il titolo e il primo figlio, se esiste.
        container.insertBefore(examination,container.children[1]);
    }

    //Creo i bottoni per scorrere le pagine
    buttonsContainer = document.createElement("div");
    buttonsContainer.setAttribute("id","buttonExaminationsContainer");

    firstButton = document.createElement("button");
    firstButton.onclick = function(){showExaminations(0)};
    firstButton.innerText="Pagina precedente";

    secondButton = document.createElement("button");
    secondButton.onclick = function(){showExaminations(1)};
    secondButton.innerText="Pagina successiva";

    buttonsContainer.appendChild(firstButton);
    buttonsContainer.appendChild(secondButton);
    container.appendChild(buttonsContainer);
    window.scrollTo(0, 0);
}

//Funzione ajax per effettuare l'eliminazione dell'esame.
function deleteElement(data,user){
    console.log(data,user);
    var xhttp; 
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            if(this.responseText=="successo"){
                location.reload();
            }
            else{
                alert("ATTENZIONE: non è stato possibile effettuare l'eliminazione!");
            }
            
      }
    };
    xhttp.open("GET", "./../dbUtility/deleteExamination.php?data="+data+"&user="+user, true);
    xhttp.send();
}