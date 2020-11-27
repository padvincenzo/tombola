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

// Load script
$db_file = fopen("./mysql/initial_db.sql", "r") or die("Impossibile aprire il file.");
$script = fread($db_file, filesize("./mysql/initial_db.sql"));
fclose($db_file);

// Create DB
$result = mysqli_multi_query($dbh, $script);
if($result) {
  unlink("install.php");
  inviaMessaggio("Tombola installata!", "./");
} else {
  echo "Errore nell'eseguire lo script: " . mysqli_error($result);
  die();
}

?>
