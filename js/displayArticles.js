var page=0;                 //Mi considera la pagina a cui sono
var lastContent=false;      //Mi segnala se ho raggiunto la fine dei contenuti, in modo da non inviare richieste a vuoto
var articlesForPage=3;      //Quanti articoli vogli per pagina

/*
    I primi due if verificano se sono alla prima pagina (nel qual caso, non posso tornare indietro)
    o se sono all'ultima pagina (non posso andare avanti). next infatti è un parametro che vale 1 se si vuole andare avanti,
    0 se si vuole andare indietro. Dopo viene fatta una richesta via AJAX. Il file json che PHP restituisce viene passato
    alla funzione printElements per la costruzione della pagina.
*/

function showArticles(next) {
    if(next==1){
        if(lastContent==true) return;
        page++;
    }
    if(next==0){
        if(page==0) return
        page--;
        lastContent=false;
    }
    var xhttp; 
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if(this.responseText=="[]"){
                lastContent=true;
                page--;
                return;
            }
            elementsToPrint = JSON.parse(this.responseText);
            printElements(elementsToPrint);
      }
    };
    //All'interno della richiesta inserisco gli estremi delle pagine da ricercare (ad esempio, dalla pagina 1 alla pagina 3)
    xhttp.open("GET", "./../dbUtility/getArticles.php?start="+(page*articlesForPage)+"&howMany="+articlesForPage, true);
    xhttp.send();
}

function printElements(elementsToPrint){
    container = document.getElementById("articlesContainer");

    //Devo cancellare tutti gli articoli già presenti. Questo modo mi evita di usare .innerHTML
    articles = container.getElementsByClassName("articleMainPage");
    while(articles[0]){
        container.removeChild(articles[0]);
    }

    /*
    Per ciascun elemento:
    (devo parire dal fondo perché voglio usare la funzione insertBefore applicata al contenitore. Perché l'ordine sia corretto,
    devo partire dall'articolo meno recente, per arrivare poi a quello più recente)
    */
    for(i=elementsToPrint.length-1; i>=0; i--){
        //Creo il contenitore dell'articolo
        article = document.createElement("div");
        article.setAttribute("class","articleMainPage");
        //Creo il titolo dell'articolo
        title = document.createElement("h3");
        title.innerText = elementsToPrint[i]['titolo']; 
        title.setAttribute("class","titleArticleMainPage");
        //Creo il testo dell'articolo
        text = document.createElement("p");
        text.setAttribute("class","textArticleMainPage");
        text.innerText = elementsToPrint[i]['testo'];
        //Creo il campo per la data e l'ora di pubblicazione
        time = document.createElement("div");
        time.setAttribute("class","timeArticleMainPage");
        time.innerText="Articolo pubblicato il: "+ elementsToPrint[i]['timestampPubblicazione'].slice(0, -3); //In questo modo non visualizzo i secondi
        if(elementsToPrint[i]['modificato']==1) time.innerText+=" (Modificato)"
        article.appendChild(title);
        article.appendChild(text);
        article.appendChild(time);
        container.insertBefore(article,container.firstChild);
    }
    //Torno in cima alla pagina
    window.scrollTo(0, 0);
}