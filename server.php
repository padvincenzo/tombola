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

$title = "Tombolone";
include("page_header.php");

// Controllo il server
if(isset($idserver)) {

  // Ho un server valido?
  $query = mysqli_query($dbh, "select * from ".PREFIX."server where idserver = '$idserver' and offlimits is null");

  if(mysqli_num_rows($query) == 1) {
    $server = mysqli_fetch_array($query);
    $pin = $server['pin'];

    if($server['accessibile'] == '1') {                 // Server in ascolto

      echo "      <button id='btnIniziaPartita' onclick='chiedi(\"Iniziamo la partita?\",\"server_start.php\");'>Inizia partita</button>\n".
          "      <button onclick='termina_partita()'>Annulla</button>\n".

          "      <p style='font-size:5vw; line-height:6vw;'>Game PIN:</p><p style='font-size:12vw; margin-top:-12px; background-color:rgba(0,0,0,0.1); line-height:12vw;'>$pin</p>\n".

          "      <table id='informazioni'>\n".
          "          <tr>\n".
          "              <td>Utenti connessi:</td>\n".
          "              <td id='NoU'>0</td>\n".
          "          </tr>\n".
          "          <tr>\n".
          "              <td>Cartelle disponibili:</td>\n".
          "              <td id='NoC'>48</td>\n".
          "          </tr>\n".
          "      </table>\n".

          "      <div id='utenti'></div>\n".

          "      <script>checkServerChanges();</script>\n";

    } else if(!$server['terminato']) {  // Gioco in esecuzione

      // Partita ancora in corso

      printTombolone();
      echo "    <button id='estrazione' onclick='estrai_numero()'>Estrai</button>\n".
          "    <button id='termina' onclick='termina_partita()'>Abbandona</button>\n";
      printLastNumbers($dbh, $idserver);
      printWinners($dbh, $idserver);

    } else {

      // Partita conclusa

      printTombolone();
      echo "    <button onclick='window.location.href=\"./server_stop.php\"'>Home</button>\n".
          "    <button onclick='nuova_partita()'>Nuova partita</button>\n";
      printLastNumbers($dbh, $idserver);
      printWinners($dbh, $idserver);
    }

  } else {

    // Server non esistente
    unset($_SESSION['idserver'], $idserver, $pin);
    printTombolone();
    echo "    <button onclick='nuova_partita();'>Nuova partita</button>\n".
        "    <button onclick='window.location.href=\"./\"'>Home</button>\n";

  }

} else {

  // Nessun idserver
  printTombolone();
  echo "    <button onclick='nuova_partita();'>Crea partita</button>\n".
      "    <button onclick='window.location.href=\"./\"'>Home</button>\n";
}

function printTombolone() {
  global $dbh, $idserver, $pin;

  $query = "";
  if(isset($idserver)) {
    $query = "select n.idnumero, t1.estratto from ".PREFIX."numero n left join (select e.idnumero, count(e.idnumero) estratto from ".PREFIX."estrarre e where e.idserver = $idserver group by e.idnumero)t1 on t1.idnumero = n.idnumero order by n.cartella = 1 desc, n.cartella = 4 desc, n.cartella = 2 desc, n.cartella = 5 desc, n.cartella = 3 desc, n.cartella = 6 desc, n.riga, n.idnumero asc";
  } else {
    $query = "select idnumero, 0 estratto from ".PREFIX."numero order by cartella = 1 desc, cartella = 4 desc, cartella = 2 desc, cartella = 5 desc, cartella = 3 desc, cartella = 6 desc, riga, idnumero asc";
  }
  $ris = mysqli_query($dbh, $query);

  // Visualizzo il tombolone

  echo "  <div style='display:inline-block; float: left;'>\n".
      "    <table id='tombolone'>\n".
      "      <tr>\n".
      "        <td height='100px' colspan='2' class='titolo'>Tombolone $pin</td>\n".
      "      </tr>\n";

  for($srow = 0; $srow < 3; $srow++) {

    echo "     <tr>\n";
    for($scol = 0; $scol < 2; $scol++) {

      echo "        <td>\n".
          "         <table class='cartella'>\n";
      $cartella = $snum[($srow * 2) + $scol];

      for($i = 0; $i < 3; $i++) {

        echo "            <tr>\n";
        for($j = 0; $j < 5; $j++) {

          $cicle = mysqli_fetch_array($ris);
          $numero = $cicle["idnumero"];
          $estratto = $cicle["estratto"];

          if($estratto == "1")
            echo "             <td class='estratto' id='n$numero'>$numero</td>\n";
          else
            echo "             <td id='n$numero'>$numero</td>\n";

        }

        echo "            </tr>\n";
      }
      echo "         </table>\n".
          "        </td>\n";
    }
    echo "     </tr>\n";
  }
  echo "    </table>\n".
      "  </div>\n";
}

function printLastNumbers($dbh, $idserver) {
  // Visualizzo gli ultimi 5 numeri estratti
  $query = mysqli_query($dbh, "select idnumero from ".PREFIX."estrarre where idserver = '$idserver' order by idestrarre desc limit 0, 5");

  echo "    <br>\n".
      "    <div id='ultimi'>\n";
  for($i = 1; $i < 6; $i++) {

    // Ultimo estratto
    $n = ($cicle = mysqli_fetch_array($query)) ? $cicle['idnumero'] : "";

    echo "     <div id='u$i'>$n</div>\n";
  }
  echo "    </div>\n".
      "    <br>\n";
}

function printWinners($dbh, $idserver) {
  // Visualizzo i vincitori
  $query = mysqli_query($dbh, "select u.nick, u.privato, p.testo from ".PREFIX."utente u, ".PREFIX."vincere v, ".PREFIX."premio p where v.idpremio = p.idpremio and v.idutente = u.idutente and u.idserver = '$idserver'");

  echo "  <div style='display:inline-block'>\n".
      "    <table id='vincitori'>\n".
      "     <tr>\n".
      "        <td class='premio privato'>Premio</td>\n".
      "        <td class='privato'>Utente</td>\n".
      "     </tr>\n";

  if(mysqli_num_rows($query) > 0) {

    while($cicle = mysqli_fetch_array($query)) {
      $nick = $cicle['nick'];
      $premio = $cicle['testo'];
      echo "     <tr>\n".
          "        <td class='premio'>$premio</td>\n".
          "        <td>$nick</td>\n".
          "     </tr>\n";
    }
  }

  echo "    </table>\n".
      "  </div>\n";
}

include("page_footer.php");
?>
