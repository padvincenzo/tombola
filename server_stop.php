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
    if ($result = mysqli_query($dbh, "select accessibile, terminato from " . PREFIX . "server where idserver = '$idserver' and offlimits is false")) {
        if ($cicle = mysqli_fetch_array($result)) {
            if ((!$cicle["accessibile"]) && (!$cicle["terminato"])) {
                mysqli_query($dbh, "update " . PREFIX . "server set terminato = true where idserver = '$idserver';");
                inviaMessaggio("Hai terminato la partita.", "./server.php");
            } else {
                mysqli_query($dbh, "update " . PREFIX . "server set offlimits = true where idserver = '$idserver';");
            }
        }
    }
}

mysqli_close($dbh);
redirect("./");
