<?php 
function displayMsg($type, $string){
    echo " <div class='alert alert-{$type}' role='alert'>
                <p>{$string}</p>
            </div>
        ";
}

?>