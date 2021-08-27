<div id="actionsContainer">
        <h2>Benvenut<?php
        if($_SESSION['codiceFiscale'][9]>=3 ||($_SESSION['codiceFiscale'][9]==3 && $_SESSION['codiceFiscale'][10]>1))
                echo 'a';
        else
                echo 'o';
        ?>, <?php echo $_SESSION['nome']?>!</h2>
        <ul>
        <li><a href="./userProfilePage.php">Prenotazioni</a></li>
        <li><a href="./newExaminationPage.php">Prenota una visita</a></li>
        <li><a href="./chatPage.php">Chat</a></li>
</ul>
</div>      