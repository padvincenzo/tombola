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

$title = "Statistiche";
include("page_header.php");

function getStat($dbh, $query) {
  $result = mysqli_query($dbh, $query);
  if($result)
    return (mysqli_fetch_array($result))["n"];
  return "-";
}

function printStat($dbh, $name, $query) {
  $n = getStat($dbh, $query);
  echo "<tr><td>$name:</td><td>$n</td></tr>\n";
}

if(adminLogin("Statistiche di gioco")) {

  echo "<table style='display:inline-block; text-align:left;'>\n";

  printStat($dbh, "Partite non ancora iniziate", "select count(*) n from ".PREFIX."server where accessibile is true");
  printStat($dbh, "Partite in corso", "select count(*) n from ".PREFIX."server where accessibile is false and terminato is null and offlimits is null");
  printStat($dbh, "Partite terminate", "select count(*) n from ".PREFIX."server where terminato is not null;");
  printStat($dbh, "Partite mai giocate", "select count(*) n from ".PREFIX."server where terminato is null and offlimits is not null;");
  printStat($dbh, "Media utenti per partita", "select format(avg(n), 1) n from (select count(u.idserver) n from ".PREFIX."server s inner join ".PREFIX."utente u on u.idserver = s.idserver where s.terminato is not null and u.privato is null group by u.idserver) ns;");

  echo "</table><br>\n<button onclick='window.location.href=\"./\";'>Home</button>";

}

include("page_footer.php");
?>
