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

$title = "Reset";
$root = "../";
include_once "../includes/header.php";

if (adminLogin("Reset del database")) {
    // Reset delle tabelle
    $query = "SET FOREIGN_KEY_CHECKS = 0; TRUNCATE " . PREFIX . "avere; TRUNCATE " . PREFIX . "estrarre; TRUNCATE " . PREFIX . "server; TRUNCATE " . PREFIX . "utente; TRUNCATE " . PREFIX . "vincere; SET FOREIGN_KEY_CHECKS = 1;";
    $result = mysqli_multi_query($dbh, $query);
    if ($result) {
        echo "<p>Il database è ora vuoto.</p>";
    } else {
        echo "<p>La query ha restituito un errore.</p>";
    }

    echo "<button onclick='window.location.href=\"./\";'>Home</button>";
}

include_once "../includes/footer.php";
