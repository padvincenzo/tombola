<?php
    include("connect.php");
    
    if(isset($idserver)) {
        
        // Ho un server valido?
        $query = mysqli_query($dbh, "select * from ".PREFIX."server where idserver = '$idserver' and accessibile = 1;");
        
        if(mysqli_num_rows($query) == 1) {
            
            $risposta = array("cartelleLibere" => 48,
                              "connessi" => 0,
                              "utente" => array());
            
            $query = "select u.nick, a.idcartella
                      from ".PREFIX."utente u left join ".PREFIX."avere a
                      on a.idutente = u.idutente
                      where u.idserver = $idserver
                      and u.uscito is null
                      and u.privato is null";
            
            $ris = mysqli_query($dbh, $query);
            
            $risposta["connessi"] = mysqli_num_rows($ris);
            
            for($i = 0; $i < $risposta["connessi"]; $i++) {
                $cicle = mysqli_fetch_array($ris);
                $nick = $cicle['nick'];
                $cartella = $cicle['idcartella'];
                
                $risposta["utente"][$i]["nick"] = $nick;
                if($cartella != null) {
                    $risposta["utente"][$i]["cartella"] = $cartella;
                    $risposta["cartelleLibere"] = $risposta["cartelleLibere"] - 1;
                } else {
                    $risposta["utente"][$i]["cartella"] = "-";
                }
            }
            
            echo urlencode(json_encode($risposta));
        }
    }
    
    mysqli_close($dbh);
?>