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

/*
 * Migrazioni del database.
 *
 * Ogni file setup/migrations/NNN_descrizione.sql è una migrazione, applicata
 * una sola volta in ordine di nome. Nei file {prefix} viene sostituito con
 * PREFIX. Le migrazioni applicate sono registrate nella tabella "migrazioni".
 */

define("MIGRAZIONI_DIR", __DIR__ . "/../setup/migrations");
define("SCHEMA_INIZIALE", "000_schema_iniziale");

// Elenco delle migrazioni disponibili: versione => percorso del file.
function migrazioni_disponibili() {
  $files = glob(MIGRAZIONI_DIR . "/*.sql");
  sort($files);
  $migrazioni = array();
  foreach($files as $file) {
    $migrazioni[basename($file, ".sql")] = $file;
  }
  return $migrazioni;
}

function tabella_esiste($nome) {
  return mysqli_num_rows(query_param("select 1 from information_schema.tables where table_schema = database() and table_name = ?", "s", $nome)) > 0;
}

// Migrazioni già applicate: versione => data. Crea la tabella se manca.
function migrazioni_applicate() {
  global $dbh;

  if(!tabella_esiste(PREFIX . "migrazioni")) {
    mysqli_query($dbh, "create table " . PREFIX . "migrazioni (
      versione varchar(100) not null primary key,
      applicata_il datetime not null
    );");

    // Installazione precedente al sistema di migrazioni: lo schema c'è già
    if(tabella_esiste(PREFIX . "server")) {
      query_param("insert into " . PREFIX . "migrazioni (versione, applicata_il) values (?, now());", "s", SCHEMA_INIZIALE);
    }
  }

  $applicate = array();
  $result = mysqli_query($dbh, "select versione, applicata_il from " . PREFIX . "migrazioni order by versione;");
  while($row = mysqli_fetch_array($result)) {
    $applicate[$row["versione"]] = $row["applicata_il"];
  }
  return $applicate;
}

function migrazioni_da_applicare() {
  return array_diff_key(migrazioni_disponibili(), migrazioni_applicate());
}

// Esegue tutte le istruzioni di un file SQL. Restituisce null o il messaggio d'errore.
function esegui_sql($sql) {
  global $dbh;
  try {
    mysqli_multi_query($dbh, $sql);
    do {
      if($result = mysqli_store_result($dbh)) mysqli_free_result($result);
    } while(mysqli_more_results($dbh) && mysqli_next_result($dbh));
  } catch(mysqli_sql_exception $e) {
    return $e->getMessage();
  }
  return mysqli_errno($dbh) ? mysqli_error($dbh) : null;
}

// Applica in ordine le migrazioni mancanti, fermandosi al primo errore.
// Restituisce un elenco di array("versione" => ..., "errore" => null|messaggio).
function applica_migrazioni() {
  global $dbh;

  // Evita che due richieste applichino le stesse migrazioni insieme
  $lock = mysqli_fetch_array(mysqli_query($dbh, "select get_lock('" . PREFIX . "migrazioni', 10) ok;"));
  if($lock["ok"] != 1) {
    return array(array("versione" => "-", "errore" => "Un altro aggiornamento è in corso, riprova tra poco."));
  }

  $esiti = array();
  foreach(migrazioni_da_applicare() as $versione => $file) {
    $sql = str_replace("{prefix}", PREFIX, file_get_contents($file));
    $errore = esegui_sql($sql);
    $esiti[] = array("versione" => $versione, "errore" => $errore);
    if($errore !== null) break;
    query_param("insert into " . PREFIX . "migrazioni (versione, applicata_il) values (?, now());", "s", $versione);
  }

  mysqli_query($dbh, "select release_lock('" . PREFIX . "migrazioni');");
  return $esiti;
}
