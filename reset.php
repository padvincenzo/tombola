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

$title = "Reset";
include("page_header.php");

if(adminLogin("Reset del database")) {

  // Reset delle tabelle
  $query = "TRUNCATE ".PREFIX."avere; TRUNCATE ".PREFIX."estrarre; TRUNCATE ".PREFIX."server; TRUNCATE ".PREFIX."utente; TRUNCATE ".PREFIX."vincere;";
  $result = mysqli_multi_query($dbh, $query);
  if($result) {
    echo "<p>Il database è ora vuoto.</p>";
  } else {
    echo "<p>La query ha restituito un errore.</p>";
  }

  echo "<button onclick='window.location.href=\"./\";'>Home</button>";
}

include("page_footer.php");
?>
