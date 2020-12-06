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
  if($result = mysqli_query($dbh, "select accessibile, terminato from ".PREFIX."server where idserver = '$idserver' and offlimits is false")) {
    if($cicle = mysqli_fetch_array($result)) {
      if((!$cicle["accessibile"]) && (!$cicle["terminato"])) {
        mysqli_query($dbh, "update ".PREFIX."server set terminato = true where idserver = '$idserver';");
        inviaMessaggio("Hai terminato la partita.", "./server.php");
      } else {
        mysqli_query($dbh, "update ".PREFIX."server set offlimits = true where idserver = '$idserver';");
      }
    }
  }
}

mysqli_close($dbh);
redirect("./");
?>
