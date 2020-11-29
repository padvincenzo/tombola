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

if(adminLogin("Statistiche di gioco")) {

  // Partite non ancora iniziate
  $query = "select count(*) n from ".PREFIX."server where accessibile is true";
  $result = mysqli_query($dbh, $query);
  if(mysqli_num_rows($result) == 1) {
    $notYetStarted = (mysqli_fetch_array($result))["n"];
  } else die("Errore query.");

  // Partite in corso
  $query = "select count(*) n from ".PREFIX."server where accessibile is false and terminato is null and offlimits is null";
  $result = mysqli_query($dbh, $query);
  if(mysqli_num_rows($result) == 1) {
    $running = (mysqli_fetch_array($result))["n"];
  } else die("Errore query.");

  // Partite terminate
  $query = "select count(*) n from ".PREFIX."server where terminato is not null;";
  $result = mysqli_query($dbh, $query);
  if(mysqli_num_rows($result) == 1) {
    $ended = (mysqli_fetch_array($result))["n"];
  } else die("Errore query.");

  // Partite mai giocate
  $query = "select count(*) n from ".PREFIX."server where terminato is null and offlimits is not null;";
  $result = mysqli_query($dbh, $query);
  if(mysqli_num_rows($result) == 1) {
    $neverPlayed = (mysqli_fetch_array($result))["n"];
  } else die("Errore query.");

  echo "<table style='display:inline-block; text-align:left;'>".
      "<tr><td>Partite non ancora iniziate:</td><td>$notYetStarted</td></tr>".
      "<tr><td>Partite in corso:</td><td>$running</td></tr>".
      "<tr><td>Partite terminate:</td><td>$ended</td></tr>".
      "<tr><td>Partite mai giocate:</td><td>$neverPlayed</td></tr>".
      "</table><br>".
      "<button onclick='window.location.href=\"./\";'>Home</button>";
}

include("page_footer.php");
?>
