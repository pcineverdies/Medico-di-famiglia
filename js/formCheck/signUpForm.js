function checkFormSignUp(){
    myForm = document.getElementById("signUp");
    /*
        la funzione verifica che i valori immessi nei campi 'password' e 'conferma password' siano uguali,
        eventualmente marcandoli come invalid, cosa che, oltre ad essere segnalata a video, evita l'invio della form.
    */
    chkPass(myForm);
    pass = true;
    for(i=0; i<myForm.getElementsByTagName("input").length;i++){
        //Per ciascun campo, viene controllata la validità. Se uno non è valido, la form non viene inviata.
        if(myForm.getElementsByTagName("input")[i].checkValidity()!=true){
            checkVal(myForm.getElementsByTagName("input")[i]);
            pass = false;
        }
    }
    if(pass) myForm.submit();
}
/*
    Questa funzione viene chiamata a seguito del evento onblur dei campi delle form.
    Se il valore immesso rende la form valida, non succede nulla, altrimenti il bordo diventa di colore rosso
*/
function checkVal(obj){
    if(obj.checkValidity()!=true){
        obj.style.borderColor="red";
    }
    else{
        obj.style.borderColor="#eee";
    }
}
/*
    Semplica funzione che controlla i due campi, verificando che siano uguali ed eventualmente
    mostrando un errore tramite un alert.
*/

function chkPass(myForm){
    if(myForm.getElementsByTagName("input")[5].value!=myForm.getElementsByTagName("input")[6].value){
        myForm.getElementsByTagName("input")[5].setCustomValidity("Invalid field.")
        myForm.getElementsByTagName("input")[6].setCustomValidity("Invalid field.")
        alert("Le due password non sono uguali!");
    }
    else{
        myForm.getElementsByTagName("input")[5].setCustomValidity("")
        myForm.getElementsByTagName("input")[6].setCustomValidity("")
    }
}
