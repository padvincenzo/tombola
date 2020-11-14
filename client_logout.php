<?php
    include("connect.php");
    if(isset($idutente)) {
        mysqli_query($dbh, "update ".PREFIX."utente set uscito = true where idutente = '$idutente';");
        unset($_SESSION['idutente']);
    }
    
    mysqli_close($dbh);
    redirect(WEBSITE);
?>