<?php
    if(!isset($_SESSION['email'])){
        echo "ACCESSO NEGATO";
        return;
    }
    require("./../dbUtility/queryManager.php");
    $result = getDatesExaminations();
    if($result==false) echo"[]";
    $result = fromSQLtoArray($result,'ASSOC');
    for($i=0; $i<count($result);$i++){
        echo"<option value=\"{$result[$i]['data']}\">{$result[$i]['data']}</option>";
    }
?>