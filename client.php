<?php
    $title = "La tua cartella";
    include("page_header.php");
    
    if(isset($idutente)) {
        
        $utente = mysqli_fetch_array(mysqli_query($dbh, "select * from ".PREFIX."utente where idutente = '$idutente'"));
        if($utente != '' && !$utente['uscito']){                                             // Utente valido
            
            $nick = $utente['nick'];
            $idserver = $utente['idserver'];
            
            $server = mysqli_fetch_array(mysqli_query($dbh, "select * from ".PREFIX."server where idserver = '$idserver'"));
            
            if(!$server['offlimits']) {
                $pin = $server['pin'];
                
                // Prelevo la cartella
                $query = mysqli_query($dbh, "select co.idnumero, co.riga
                                             from ".PREFIX."utente u, ".PREFIX."avere a, ".PREFIX."cartella c, ".PREFIX."comporre co
                                             where a.idutente = u.idutente
                                             and a.idcartella = c.idcartella
                                             and co.idcartella = c.idcartella
                                             and u.idutente='".$utente['idutente']."';");
                
                $scheda = array();
                $scheda[0] = array();
                $scheda[1] = array();
                $scheda[2] = array();
                
                while($cicle = mysqli_fetch_array($query)){
                    
                    $numero = $cicle['idnumero'];
                    $riga = $cicle['riga'];
                    
                    if($numero == 90)
                        $colonna = 8;
                    else
                        $colonna = $numero / 10;
                    
                    $colonna = floor($colonna);
                    $scheda[$riga-1][$colonna] = $numero;
                    
                }
                
                echo "  <div class='titolo'>Game PIN: $pin</div>".
                     "  <div id='client_cartella'>\n".
                     "    <table class='cartella'>\n".
                     "     <tr>\n".
                     "        <td colspan='9' height='100px' id='client_nome'>Cartella di $nick</td>\n".
                     "     </tr>\n";
                for($i = 0; $i < 3; $i++){
                    
                    echo "     <tr>\n";
                    for($j = 0; $j < 9; $j++){
                        if(isset($scheda[$i][$j])){
                            
                            $numero = $scheda[$i][$j];
                            // Se il numero è stato estratto
                            $query = mysqli_query($dbh, "select e.idnumero
                                                         from ".PREFIX."estrarre e inner join ".PREFIX."server s
                                                         on e.idserver = s.idserver
                                                         where s.idserver = '$idserver'
                                                         and e.idnumero = '$numero'");
                            if(mysqli_fetch_array($query) != '')
                                echo "        <td id='n$numero' class='estratto'>$numero</td>\n";
                            
                            else
                                echo "        <td id='n$numero'>$numero</td>\n";
                            
                        } else
                            
                            echo "        <td>·</td>\n";
                    }
                    
                    echo "     </tr>\n";
                    
                }
                echo "    </table>\n".
                     "  </div>\n";
                
                if($server['terminato']) {
                    echo "      <script>mostraMessaggio('Partita terminata');</script>\n".
                         "      <button onclick='window.location.href=\"".WEBSITE."\";'>Home</button>\n";
                } else {
                    echo "      <button onclick='logout();'>Abbandona</button>\n";
                }
                
                echo "  <br>\n".
                     "  <div id='ultimi'>\n".
                     "  </div>\n".
                     "  <br>\n".
                     "  <div style='display:inline-block;'>\n".
                     "      <table id='vincitori'>\n".
                     "          <tr>\n".
                     "              <td class='premio privato'>Premio</td>\n".
                     "              <td class='privato'>Utente</td>\n".
                     "          </tr>\n".
                     "      </table>\n".
                     "  </div>\n".
                     
                     "  <script>checkClientChanges();</script>\n";
                
            } else {
                
                // Partita conclusa e offlimits
                redirect(WEBSITE);
            }
                
        } else{
            
            // Utente non più valido
            redirect(WEBSITE);
        }
        
    } else {
        
        // Utente non loggato
        redirect(WEBSITE);
    }
    
    include("page_footer.php");
?>