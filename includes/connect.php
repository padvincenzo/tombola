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

session_start();

// Legge la configurazione da un file .env (righe CHIAVE=valore, # per i commenti).
// Le variabili d'ambiente già impostate (es. da Docker) hanno la precedenza.
// Restituisce null se mancano sia il file sia le variabili d'ambiente.
function load_env($path) {
  $env = array();
  if(is_readable($path)) {
    foreach(file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
      $line = trim($line);
      if($line === "" || $line[0] === "#" || strpos($line, "=") === false) continue;
      list($key, $value) = array_map("trim", explode("=", $line, 2));
      if(strlen($value) >= 2 && ($value[0] === '"' || $value[0] === "'") && substr($value, -1) === $value[0]) {
        $value = substr($value, 1, -1);
      }
      $env[$key] = $value;
    }
  } else if(getenv("DB_HOST") === false) {
    return null;
  }
  foreach(array("DB_HOST", "DB_USER", "DB_PASSWORD", "DB_NAME", "ADMIN_PASSWORD") as $key) {
    if(getenv($key) !== false) $env[$key] = getenv($key);
  }
  return $env;
}

// Credenziali database e password di amministratore
$env = load_env(__DIR__ . "/../.env");
if($env === null) {
  die("Configurazione mancante: copia .env.example in .env.");
}

define("ADMIN_PW", $env["ADMIN_PASSWORD"] ?? "");
define("PREFIX", "tombola_k_");

$dbh = mysqli_connect($env["DB_HOST"] ?? "localhost", $env["DB_USER"] ?? "", $env["DB_PASSWORD"] ?? "", $env["DB_NAME"] ?? "");

if(!$dbh) {
  die("Errore di connessione al database.");
}

// Session
if(isset($_SESSION["idserver"])) $idserver = (int) $_SESSION["idserver"];
if(isset($_SESSION["idutente"])) $idutente = (int) $_SESSION["idutente"];

/* Cancella le partite abbandonate (non durante l'installazione, quando le tabelle possono mancare) */
if(!defined("SETUP")) {
  try {
    mysqli_query($dbh, "update ".PREFIX."server set offlimits = 1 where offlimits is false and datediff(now(), data) > 2;");
  } catch(mysqli_sql_exception $e) {
    if($e->getCode() == 1146) {   // Tabella inesistente
      die("Il database non è ancora installato: apri la pagina setup/ per installarlo.");
    }
    throw $e;
  }
}

function redirect($link) {
  header("Location: $link");
  die();
}

function inviaMessaggio($messaggio, $link, $content = null) {
  global $dbh;
  $data = urlencode(json_encode($content));
  $messaggio = rimuovi_apici($messaggio);
  $_SESSION['messaggio'] = array($messaggio, $data);
  mysqli_close($dbh);
  redirect($link);
}

// Esegue una query con parametri (prepared statement).
// $types segue la sintassi di mysqli_stmt_bind_param: "s" stringa, "i" intero.
function query_param($sql, $types, ...$params) {
  global $dbh;
  $stmt = mysqli_prepare($dbh, $sql);
  mysqli_stmt_bind_param($stmt, $types, ...$params);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result !== false ? $result : mysqli_stmt_errno($stmt) === 0;
}

function is_a_pin($pin = null) {
  return is_string($pin) && preg_match("/^[0-9]{5}$/", $pin);
}

function rimuovi_apici($data) {
  $data = str_replace("'", "&#039;", $data);
  $data = str_replace('"', "&quot;", $data);
  return $data;
}

function checkOnlyNumbers($number = null) {
  if(is_numeric($number) && strpos($number, ".") == false && $number > 0) return true;
  return false;
}

function is_a_username($username = null) {
  if($username == null || $username == "") return false;
  return preg_match("/^[a-zA-Z0-9_.-]{2,20}$/", $username);
}

function adminLogin($msg = "Password di amministratore") {
  // La password è stata inserita?
  if(! isset($_POST['admin_pw'])) {
    echo "<p style='padding-top:20vh;'>$msg</p>\n".
        "<form action='#' method='post'>\n".
        "<input type='password' name='admin_pw' placeholder='Inserire la password' /><br>\n".
        "<button type='button' onclick='window.location.href=\"./\";'>Home</button>\n".
        "<button type='submit'>Accedi</button>\n".
        "</form>\n";
    return false;
  }

  if(ADMIN_PW === "" || !hash_equals(ADMIN_PW, (string) $_POST['admin_pw'])) {
    echo "<p>Password errata.</p>\n".
        "<button onclick='window.location.href=\"./\";'>Home</button>\n";
    return false;
  }

  return true;
}
