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

if(isset($idserver)) {
  mysqli_query($dbh, "update ".PREFIX."server set offlimits = 1, accessibile = 0, terminato = 1 where idserver = '$idserver'");
}

// Setto offlimits tutte le partite aperte da più di 2 giorni
mysqli_query($dbh, "update ".PREFIX."server set offlimits = 1 where datediff(NOW(), data) > 2;");

// Genero il PIN
$query = mysqli_query($dbh, "select c.codice from ".PREFIX."codice c left join ".PREFIX."server s on s.pin = c.codice where s.pin is null or s.offlimits is not null");

$n = mysqli_num_rows($query);
$codici = array();
while($cicle = mysqli_fetch_array($query)) {
  $codici[] = $cicle["codice"];
}

$pin = $codici[rand(0, $n)];

// Aggiungo il server al database
$query = "insert into ".PREFIX."server (pin, accessibile, data) values ('$pin', true, NOW());";
mysqli_query($dbh, $query);
$idserver = mysqli_insert_id($dbh)."";
$_SESSION["idserver"] = $idserver;

// Creo l'utente privato del server
mysqli_query($dbh, "insert into ".PREFIX."utente(nick, privato, idserver) values ('Tombolone', true, '$idserver');");

redirect("./server.php");
?>
