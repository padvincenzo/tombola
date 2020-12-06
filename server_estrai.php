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

include("connect.php");

// Controllo il server

if(isset($idserver)) {

  // Il server è ancora attivo?
  $query = mysqli_query($dbh, "select * from ".PREFIX."server where idserver = '$idserver' and terminato is false and offlimits = false;");

  if(mysqli_num_rows($query) == 1) {

    // Nessun giocatore --> Partita terminata
    $query = mysqli_query($dbh, "select count(*) n from ".PREFIX."utente where privato is null and uscito is null and idserver = '$idserver';");
    if((mysqli_fetch_array($query))["n"] == "0") {

      mysqli_query($dbh, "update ".PREFIX."server set offlimits = true where idserver = '$idserver';");
      echo "noGiocatori";

    } else {

      $risposta = array(
        "numero" => 0,
        "stato" => "ok",
        "vincite" => array("n" => 0,
        "premio" => "",
        "user" => array()));

      // Quali numeri non sono ancora stati estratti?
      $query = mysqli_query($dbh, "select n.idnumero from ".PREFIX."numero n left join (select e.idnumero from ".PREFIX."estrarre e where e.idserver = '$idserver')t1 on n.idnumero = t1.idnumero where t1.idnumero is null;");
      $numeri = array();

      $n = mysqli_num_rows($query) - 1;

      if($n > 0) {
        while($cicle = mysqli_fetch_array($query)) {
          $numeri[] = $cicle['idnumero'];
        }

        // Ne scelgo uno random
        $numero_estratto = $numeri[rand(0, $n)];

        mysqli_query($dbh, "insert into ".PREFIX."estrarre (idserver, idnumero) values ('$idserver', '$numero_estratto');");

        $risposta["numero"] = $numero_estratto;
      }

      if($n == 0) {
        mysqli_query($dbh, "update ".PREFIX."server set terminato = true where idserver = '$idserver';");
        $risposta["stato"] = "terminata";
      }

      /* CONTROLLA LE VITTORIE */

      // Il prossimo premio è ..?
      $query = mysqli_query($dbh, "select p.idpremio, p.testo from ".PREFIX."premio p left join (select v.idpremio from ".PREFIX."utente u, ".PREFIX."server s, ".PREFIX."vincere v where u.idserver = s.idserver and v.idutente = u.idutente and s.idserver = $idserver group by v.idpremio)t1 on t1.idpremio = p.idpremio where t1.idpremio is null order by p.idpremio asc limit 1;");

      $next = 2;
      $risposta["vincite"]["premio"] = "Ambo";

      if($cicle = mysqli_fetch_array($query)) {
        $next = $cicle['idpremio'] + 1;
        $risposta["vincite"]["premio"] = $cicle['testo'];
      }

      if($next <= 5) {    // Fino alla cinquina

        // Il tombolone ha vinto il premio?
        if($query = mysqli_fetch_array(mysqli_query($dbh, "select u.idutente, n.cartella, n.riga from ".PREFIX."utente u, ".PREFIX."server s, ".PREFIX."estrarre e, ".PREFIX."numero n where u.idserver = s.idserver and e.idserver = s.idserver and e.idnumero = n.idnumero and u.nick = 'Tombolone' and s.idserver = $idserver group by n.cartella, n.riga having count(n.idnumero) = $next;"))) {

          $idutente = $query['idutente'];
          mysqli_query($dbh, "insert into ".PREFIX."vincere (idutente, idpremio) values ($idutente, ".($next-1).");");
          $risposta["vincite"]["n"] = 1;
          $risposta["vincite"]["user"][] = "Tombolone";
        }

        // Qualcuno ha vinto il premio?
        $query = mysqli_query($dbh, "select u.idutente, u.nick from ".PREFIX."utente u, ".PREFIX."server s, ".PREFIX."avere a, ".PREFIX."cartella c, ".PREFIX."comporre k, ".PREFIX."numero n, ".PREFIX."estrarre e where u.idserver = s.idserver and a.idutente = u.idutente and a.idcartella = c.idcartella and k.idcartella = c.idcartella and k.idnumero = n.idnumero and e.idnumero = n.idnumero and e.idserver = s.idserver and u.uscito is null and s.idserver = $idserver group by u.idutente, k.riga having count(n.idnumero) = $next");

        $risposta["vincite"]["n"] = $risposta["vincite"]["n"] + mysqli_num_rows($query);
        while($win = mysqli_fetch_array($query)) {
          $next--;
          $idutente = $win['idutente'];
          mysqli_query($dbh, "insert into ".PREFIX."vincere (idutente, idpremio) values ('$idutente', '$next');");
          $risposta["vincite"]["user"][] = $win['nick'];
        }

      } else {    // Per la tombola

        $tombola = false;

        // Il tombolone ha fatto tombola?
        if($query = mysqli_fetch_array(mysqli_query($dbh, "select u.idutente, n.cartella, n.riga from ".PREFIX."utente u, ".PREFIX."server s, ".PREFIX."estrarre e, ".PREFIX."numero n where u.idserver = s.idserver and e.idserver = s.idserver and e.idnumero = n.idnumero and u.nick = 'Tombolone' and s.idserver = $idserver group by n.cartella having count(n.idnumero) = 15;"))) {

          $idutente = $query['idutente'];
          mysqli_query($dbh, "insert into ".PREFIX."vincere (idutente, idpremio) values ($idutente, 5);");
          $risposta["vincite"]["n"] = 1;
          $risposta["vincite"]["user"][] = "Tombolone";
          $tombola = true;
        }

        // Qualcuno ha fatto tombola?
        $query = mysqli_query($dbh, "select u.idutente, u.nick from ".PREFIX."utente u, ".PREFIX."server s, ".PREFIX."avere a, ".PREFIX."cartella c, ".PREFIX."comporre k, ".PREFIX."numero n, ".PREFIX."estrarre e where u.idserver = s.idserver and a.idutente = u.idutente and a.idcartella = c.idcartella and k.idcartella = c.idcartella and k.idnumero = n.idnumero and e.idnumero = n.idnumero and e.idserver = s.idserver and u.uscito is null and s.idserver = $idserver group by u.idutente having count(n.idnumero) = 15;");

        $risposta["vincite"]["n"] = $risposta["vincite"]["n"] + mysqli_num_rows($query);
        while($win = mysqli_fetch_array($query)) {
          $idutente = $win['idutente'];
          mysqli_query($dbh, "insert into ".PREFIX."vincere (idutente, idpremio) values ('$idutente', 5);");
          $risposta["vincite"]["user"][] = $win["nick"];
          $tombola = true;
        }

        if($tombola) {

          // Qualcuno ha fatto tombola, la partita termina
          mysqli_query($dbh, "update ".PREFIX."server set terminato = true where idserver = '$idserver';");
          $risposta["stato"] = "terminata";
        }

      }

      echo urlencode(json_encode($risposta));
    }

  } else {
    echo "terminata";
  }
}

mysqli_close($dbh);
?>
