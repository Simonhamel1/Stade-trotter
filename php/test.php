<?php
    session_start();

    function recursiveTab($t) {
        foreach($t as $i => $e){
            if(is_array($e)){
                recursiveTab($e);
            } else {
                echo $i . "=>" . $e;
                echo "<br>";
            }
        }
    }
    
    recursiveTab($_SESSION["voyage"]);
?>