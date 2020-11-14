<?php
    include("connect.php");
    
    if(isset($idserver)) {
        $query = mysqli_query($dbh, "select accessibile, terminato from ".PREFIX."server where idserver = '$idserver' and offlimits is null");
        if($cicle = mysqli_fetch_array($query)) {
            if($cicle["accessibile"]) {
                mysqli_query($dbh, "update ".PREFIX."server set accessibile = false, offlimits = true where idserver = '$idserver';");
            } else if(!$cicle["terminato"]) {
                mysqli_query($dbh, "update ".PREFIX."server set terminato = true where idserver = '$idserver';");
                inviaMessaggio("Hai terminato la partita.", WEBSITE."server.php");
            } else {
                mysqli_query($dbh, "update ".PREFIX."server set offlimits = true where idserver = '$idserver';");
            }
        }
    }
    
    mysqli_close($dbh);
    redirect(WEBSITE);
?>