<?php
    if(!isset($_SESSION['email'])) session_start();
    if(!isset($_SESSION['email'])||$_SESSION['stato']!=-1){
        echo"ACCESSO NEGATO";
        return;
    }
    require("./../dbUtility/queryManager.php");
    $result = getUsersAndUnread();
    for($i=0; $i<count($result);$i++){
        echo        "
                <div class=\"user\">
                    <div class=\"infoUser\">{$result[$i]['nome']} {$result[$i]['cognome']}</div>
                    <div class=\"infoUser\">{$result[$i]['codiceFiscale']}</div>
                    ";
        if($result[$i]['quantiNonLetti']!=0){
            echo "<div class=\"unreadNotification\"></div>";
            /*
            if($result[$i]['quantiNonLetti']==1)
                echo "<div class=\"infoUser unread\">C'è un messaggio non letto</div>";
            else
                echo "<div class=\"infoUser unread\">Ci sono {$result[$i]['quantiNonLetti']} messaggi non letti</div>";
                */
        }
        echo        "
                    <button onclick=\"redirectToChat({$result[$i]['codiceUtente']})\" class=\"chatButton\">CHAT</button>
                </div>
                    ";
    }
?>