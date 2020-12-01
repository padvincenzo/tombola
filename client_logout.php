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
  $result = mysqli_query($dbh, "update ".PREFIX."utente u inner join ".PREFIX."server s on u.idserver = s.idserver set u.uscito = true where u.idutente = '$idutente' and s.terminato is null and s.offlimits is null;");
  unset($_SESSION['idutente']);
  if(!$result)
    inviaMessaggio("Logout fallito.", "./");
}

mysqli_close($dbh);
redirect("./");
?>
