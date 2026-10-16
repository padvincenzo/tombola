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

if (isset($idutente)) {
    $result = mysqli_query(
        $dbh,
        "update " . PREFIX . "utente u inner join " . PREFIX . "server s on u.idserver = s.idserver set u.uscito = true where u.idutente = '$idutente' and s.terminato is false and s.offlimits is false;"
    );
    unset($_SESSION['idutente']);
    if (!$result) {
        inviaMessaggio("Logout fallito.", "./");
    }
}

mysqli_close($dbh);
redirect("./");
