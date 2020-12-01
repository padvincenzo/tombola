<?php
/*
Tombola
Il classico gioco natalizio online.

Copyright (C) 2020  Vincenzo Padula

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

session_start();

// Credenziali database
$host = 'localhost';
$user = 'vincenzopadula';
$database = 'my_vincenzopadula';
$psw = '';

// Password di amministratore
define("ADMIN_PW", "laMiaTombola");

define("PREFIX", "tombola_k_");

$dbh = mysqli_connect($host, $user, $psw, $database);

if(!$dbh) {
  die("Errore di connessione al database.");
}

// Session
if(isset($_SESSION["idserver"])) $idserver = $_SESSION["idserver"];
if(isset($_SESSION["idutente"])) $idutente = $_SESSION["idutente"];

/* Cancella le partite abbandonate */
$query = "update ".PREFIX."server set offlimits = 1 where offlimits is not true and data < '".date('Y-m-d', strtotime("-10 days"))."';";

function redirect($link) {
  header("Location: $link");
  die();
}

function inviaMessaggio($messaggio, $link, $content = null) {
  $data = urlencode(json_encode($content));
  $messaggio = rimuovi_apici($messaggio);
  $_SESSION['messaggio'] = array($messaggio, $data);
  redirect($link);
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
        "<form action='".$_SERVER['REQUEST_URI']."' method='post'>\n".
        "<input type='password' name='admin_pw' placeholder='Inserire la password' /><br>\n".
        "<button type='submit'>Accedi</button>\n".
        "<button type='button' onclick='window.location.href=\"./\";'>Home</button>\n".
        "</form>\n";
    return false;
  }

  if($_POST['admin_pw'] != ADMIN_PW) {
    echo "<p>Password errata.</p>\n".
        "<button onclick='window.location.href=\"./\";'>Home</button>\n";
    return false;
  }

  return true;
}
?>
