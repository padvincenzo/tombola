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

// Installa o aggiorna il database da riga di comando:
//   php setup/migrate.php           applica le migrazioni mancanti
//   php setup/migrate.php --stato   mostra lo stato senza modificare nulla

if (PHP_SAPI !== "cli") {
    http_response_code(403);
    die("Usa la pagina setup/ dal browser.");
}

define("SETUP", true);
include_once __DIR__ . "/../includes/connect.php";
include_once __DIR__ . "/../includes/migrazioni.php";

$applicate = migrazioni_applicate();
foreach (migrazioni_disponibili() as $versione => $file) {
    echo (isset($applicate[$versione]) ? "  applicata    " : "  da applicare ") . "$versione\n";
}

if (in_array("--stato", $argv)) {
    exit(0);
}

$esiti = applica_migrazioni();
if (count($esiti) == 0) {
    echo "Il database è aggiornato.\n";
}
foreach ($esiti as $esito) {
    if ($esito["errore"] === null) {
        echo "Applicata $esito[versione]\n";
    } else {
        fwrite(STDERR, "Errore in $esito[versione]: $esito[errore]\n");
        exit(1);
    }
}
