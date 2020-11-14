<?php
    include("connect.php");
    
    if(isset($idutente)) {
        
        // Ho un idutente valido?
        $query = mysqli_query($dbh, "select s.idserver, s.accessibile, s.terminato
                                     from ".PREFIX."utente u inner join ".PREFIX."server s
                                     on u.idserver = s.idserver
                                     where u.idutente = '$idutente'
                                     and s.offlimits is null
                                     and u.uscito is null;");
        
        if(mysqli_num_rows($query) == 1) {
            
            $risposta = array("stato" => "ok",
                              "nn" => 0,
                              "numero" => array(),
                              "np" => 0,
                              "premio" => array());
            $cicle = mysqli_fetch_array($query);
            if($cicle["terminato"]) {
                $risposta["stato"] = "terminato";
            } else if($cicle["accessibile"]) {
                $risposta["stato"] = "accessibile";
            } else {
                $risposta["stato"] = "iniziato";
            }
            $idserver = $cicle["idserver"];
            
            $query = "select e.idnumero
                      from ".PREFIX."estrarre e
                      where e.idserver = $idserver
                      order by e.idestrarre desc;";
            $ris = mysqli_query($dbh, $query);
            $risposta["nn"] = mysqli_num_rows($ris);
            for($i = 0; $i < $risposta["nn"]; $i++) {
                $cicle = mysqli_fetch_array($ris);
                $risposta["numero"][$i] = $cicle["idnumero"];
            }
            
            $query = "select u.nick, p.testo
                      from ".PREFIX."utente u, ".PREFIX."vincere v, ".PREFIX."premio p
                      where v.idutente = u.idutente
                      and v.idpremio = p.idpremio
                      and u.idserver = $idserver
                      order by p.idpremio, u.nick";
            $ris = mysqli_query($dbh, $query);
            $risposta["np"] = mysqli_num_rows($ris);
            for($i = 0; $i < $risposta["np"]; $i++) {
                $cicle = mysqli_fetch_array($ris);
                $risposta["premio"][$i]["nick"] = $cicle["nick"];
                $risposta["premio"][$i]["premio"] = $cicle["testo"];
            }
            
            echo urlencode(json_encode($risposta));
        }
    }
    
    mysqli_close($dbh);
?>