<?php

/**
 * Tombola
 * Il classico gioco natalizio online.
 *
 * Copyright (C) 2025 Vincenzo Padula <padvincenzo@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

include_once "./includes/connect.php";

if (isset($idserver)) {
    if ($result = mysqli_query($dbh, "select count(u.idserver) n from " . PREFIX . "utente u inner join " . PREFIX . "avere a on a.idutente = u.idutente where u.idserver = $idserver and u.uscito is null")) {
        $userNumber = (mysqli_fetch_array($result))["n"];
        if ($userNumber > 0) {
            mysqli_query($dbh, "update " . PREFIX . "server set accessibile = false where idserver = '$idserver';");
        } else {
            inviaMessaggio("La partita non può iniziare senza giocatori.", "./server.php");
        }
    }
}

mysqli_close($dbh);
redirect("./server.php");
