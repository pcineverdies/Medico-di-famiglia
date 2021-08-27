<?php
if(!isset($_SESSION['email'])){
    require("./../layout/disclaimerLogin.php");
    require("./../layout/contatti.php");
}
else if($_SESSION['stato']!=-1){
    require("./../layout/actions.php");
    require("./../layout/contatti.php");
    ?>
<?php
}
else{
    require("./../layout/actionsAdmin.php");
}
?>
