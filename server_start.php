<?php

/**
 * Tombola
 * Il classico gioco natalizio online.
 *
 * @package   Tombola
 * @author    Vincenzo Padula <padvincenzo@gmail.com>
 * @copyright 2020-2026 Vincenzo Padula
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://github.com/padvincenzo/tombola
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
