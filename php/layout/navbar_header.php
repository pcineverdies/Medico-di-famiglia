<header>
    <h1 id="pageTitle">Il tuo medico di famiglia!</h1>
    <h2 id="subTitle">L'innovazione, a servizio della salute</h2>
    <ul id="navbar">
        <li><a href="./homePage.php">Home</a></li>
        <li><a href="./../../html/manuale.html">Manuale</a></li>
        <?php
            if(isset($_SESSION["codiceFiscale"])){
                echo "<li><a href='./../loginManager/logout.php'>Logout</a></li>";
            }
            else{
                echo "<li><a href='./loginPage.php'>Accedi</a></li>
                    <li><a href='./signupPage.php'>Registrati</a></li>";
            }
        ?>

    </ul>      
</header>  
