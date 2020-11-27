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

if(isset($idutente)) {

  // Ho un idutente valido?
  $query = mysqli_query($dbh, "select s.idserver, s.accessibile, s.terminato from ".PREFIX."utente u inner join ".PREFIX."server s on u.idserver = s.idserver where u.idutente = '$idutente' and s.offlimits is null and u.uscito is null;");

  if(mysqli_num_rows($query) == 1) {

    $risposta = array(
      "stato" => "ok",
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

    $query = "select e.idnumero from ".PREFIX."estrarre e where e.idserver = $idserver order by e.idestrarre desc;";
    $ris = mysqli_query($dbh, $query);
    $risposta["nn"] = mysqli_num_rows($ris);
    for($i = 0; $i < $risposta["nn"]; $i++) {
      $cicle = mysqli_fetch_array($ris);
      $risposta["numero"][$i] = $cicle["idnumero"];
    }

    $query = "select u.nick, p.testo from ".PREFIX."utente u, ".PREFIX."vincere v, ".PREFIX."premio p where v.idutente = u.idutente and v.idpremio = p.idpremio and u.idserver = $idserver order by p.idpremio, u.nick";
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
