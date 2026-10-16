<?php

/**
 * Tombola
 * Il classico gioco natalizio online.
 *
 * Copyright (C) 2025-2026 Vincenzo Padula <padvincenzo@gmail.com>
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
 *
 * @package   Tombola
 * @author    Vincenzo Padula <padvincenzo@gmail.com>
 * @copyright 2025-2026 Vincenzo Padula
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

if (in_array("--stato", $argv)) exit(0);

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
