var articles = [];

function set(){
    var xhttp; 
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            articles = JSON.parse(this.responseText);
            addArticles();
      }
    };
    //All'interno della richiesta inserisco gli estremi delle pagine da ricercare (ad esempio, dalla pagina 1 alla pagina 3)
    //Avendo howmany=-1, li prendo tutti
    xhttp.open("GET", "./../dbUtility/getArticles.php?start="+0+"&howMany="+(-1), true);
    xhttp.send();
}

function addArticles(){
    select = document.getElementById("selectArticle");
    for(i=0; i<articles.length; i++){
        option = document.createElement("option");
        option.value = articles[i]['codiceArticolo'];
        option.innerText = articles[i]['titolo'];
        select.appendChild(option);
    }
    select.onchange=displayArticle;
}

function displayArticle(){
    if(document.getElementById("selectArticle").value=="Nuovo articolo"){
        document.getElementById("inputTitleArticle").value = "";
        document.getElementById("inputTextArticle").value = "";
        document.getElementById("submitArticle").value="Pubblica";
        return;
    }
    element = 0;
    for(;element<articles.length; element++){
        if(articles[element]['codiceArticolo']==document.getElementById("selectArticle").value) break;
    }
    document.getElementById("inputTitleArticle").value = articles[element]['titolo'];
    document.getElementById("inputTextArticle").value = articles[element]['testo'];
    document.getElementById("submitArticle").value="Aggiorna";
}