<?php
/*
Tombola
Il classico gioco natalizio online.

Copyright (C) 2020  Vincenzo Padula

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

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
      $query = mysqli_query($dbh, "select co.idnumero, co.riga from ".PREFIX."utente u, ".PREFIX."avere a, ".PREFIX."cartella c, ".PREFIX."comporre co where a.idutente = u.idutente and a.idcartella = c.idcartella and co.idcartella = c.idcartella and u.idutente='".$utente['idutente']."';");

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
            $query = mysqli_query($dbh, "select e.idnumero from ".PREFIX."estrarre e inner join ".PREFIX."server s on e.idserver = s.idserver where s.idserver = '$idserver' and e.idnumero = '$numero'");
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
            "      <button id='uscita' onclick='window.location.href=\"./\";'>Home</button>\n";
      } else {
        echo "      <button id='uscita' onclick='logout();'>Abbandona</button>\n";
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
      redirect("./");
    }

  } else{

    // Utente non più valido
    redirect("./");
  }

} else {

  // Utente non loggato
  redirect("./");
}

include("page_footer.php");
?>
