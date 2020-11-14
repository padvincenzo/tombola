<?php
    include("connect.php");
    
    if(isset($idserver)) {
        $cicle = mysqli_fetch_array(mysqli_query($dbh, "select count(u.idserver) n
                                                        from ".PREFIX."utente u inner join ".PREFIX."avere a
                                                        on a.idutente = u.idutente
                                                        where u.idserver = $idserver
                                                        and u.uscito is null"));
        if($cicle['n'] > 0) {
            mysqli_query($dbh, "update ".PREFIX."server set accessibile = false where idserver = '$idserver';");
        } else {
            inviaMessaggio("Non puoi giocare da solo :/", WEBSITE."server.php");
        }
    }
    
    mysql_close($dbh);
    redirect(WEBSITE.'server.php');
?>