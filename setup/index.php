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

// Installazione e aggiornamento del database

define("SETUP", true);
$title = "Installazione";
$root = "../";
include_once __DIR__ . "/../includes/header.php";
include_once __DIR__ . "/../includes/migrazioni.php";

if (ADMIN_PW === "") {
    echo "<p>Per installare o aggiornare la tombola imposta <code>ADMIN_PASSWORD</code> nel file <code>.env</code>.</p>\n";
    include_once __DIR__ . "/../includes/footer.php";
    exit;
}

// Accesso e uscita
if (isset($_POST["admin_pw"])) {
    if (hash_equals(ADMIN_PW, (string) $_POST["admin_pw"])) {
        session_regenerate_id(true);
        $_SESSION["admin"] = true;
        $_SESSION["csrf"] = bin2hex(random_bytes(16));
        redirect("./");
    }
    $errore = "Password errata.";
}

if (isset($_POST["esci"])) {
    unset($_SESSION["admin"], $_SESSION["csrf"]);
    redirect("../");
}

if (empty($_SESSION["admin"])) {
    if (isset($errore)) echo "<p>$errore</p>\n";
    echo "<p>Installazione e aggiornamenti</p>\n" .
        "<form method='post'>\n" .
        "<input type='password' name='admin_pw' placeholder='Password di amministratore' required autofocus /><br>\n" .
        "<button type='button' onclick='window.location.href=\"../\";'>Home</button>\n" .
        "<button type='submit'>Accedi</button>\n" .
        "</form>\n";
    include_once __DIR__ . "/../includes/footer.php";
    exit;
}

// Applicazione delle migrazioni (il risultato viene mostrato dopo il redirect)
if (isset($_POST["applica"]) && hash_equals($_SESSION["csrf"], (string) ($_POST["csrf"] ?? ""))) {
    $_SESSION["esiti"] = applica_migrazioni();
    redirect("./");
}

if (isset($_SESSION["esiti"])) {
    foreach ($_SESSION["esiti"] as $esito) {
        $versione = htmlspecialchars($esito["versione"]);
        if ($esito["errore"] === null) {
            echo "<p>✔ $versione applicata.</p>\n";
        } else {
            echo "<p>✘ $versione non applicata: " . htmlspecialchars($esito["errore"]) . "</p>\n";
        }
    }
    unset($_SESSION["esiti"]);
}

// Stato del database
$applicate = migrazioni_applicate();
$disponibili = migrazioni_disponibili();
$mancanti = count(array_diff_key($disponibili, $applicate));

echo "<p>" . (count($applicate) == 0 ? "La tombola non è ancora installata." :
    ($mancanti == 0 ? "Il database è aggiornato." : "Ci sono aggiornamenti da applicare.")) . "</p>\n";

echo "<table id='stats'>\n";
foreach ($disponibili as $versione => $file) {
    $stato = isset($applicate[$versione]) ? "applicata il " . $applicate[$versione] : "da applicare";
    echo "<tr><td>" . htmlspecialchars($versione) . "</td><td>$stato</td></tr>\n";
}
echo "</table><br>\n";

echo "<form method='post' style='display:inline-block;'>\n";
if ($mancanti > 0) {
    $azione = count($applicate) == 0 ? "Installa" : "Applica $mancanti aggiornament" . ($mancanti == 1 ? "o" : "i");
    echo "<input type='hidden' name='csrf' value='" . $_SESSION["csrf"] . "'>\n" .
        "<button type='submit' name='applica' value='1'>$azione</button>\n";
}
echo "<button type='submit' name='esci' value='1'>Esci</button>\n" .
    "</form>\n";

include_once __DIR__ . "/../includes/footer.php";
