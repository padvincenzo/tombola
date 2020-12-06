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
  mysqli_query($dbh, "update ".PREFIX."server set offlimits = 1 where idserver = '$idserver'");
}

// Genero il PIN
$query = "select lpad(e*10000+d*1000+c*100+b*10+a,5,\"0\") n from
        (select 0 a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) t1,
        (select 0 b union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) t2,
        (select 0 c union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) t3,
        (select 0 d union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) t4,
        (select 0 e union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) t5
        where lpad(e*10000+d*1000+c*100+b*10+a,5,\"0\") not in (select pin from ".PREFIX."server where offlimits is false)
        order by 1";

$result = mysqli_query($dbh, $query);

$n = mysqli_num_rows($result);

if($n == 0)
  die("mm");

$codici = array();
while($cicle = mysqli_fetch_array($result)) {
  $codici[] = $cicle["n"];
}

$pin = $codici[rand(0, $n)];

// Aggiungo il server al database
$query = "insert into ".PREFIX."server (pin, data, accessibile, terminato, offlimits) values ('$pin', now(), true, false, false);";
mysqli_query($dbh, $query);
$idserver = mysqli_insert_id($dbh)."";
$_SESSION["idserver"] = $idserver;

// Creo l'utente privato del server
mysqli_query($dbh, "insert into ".PREFIX."utente(nick, privato, idserver) values ('Tombolone', true, '$idserver');");

redirect("./server.php");
?>
