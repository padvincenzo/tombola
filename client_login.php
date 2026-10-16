<?php

/**
 * Tombola
 * Il classico gioco natalizio online.
 *
 * Copyright (C) 2020-2026 Vincenzo Padula <padvincenzo@gmail.com>
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
 * @copyright 2020-2026 Vincenzo Padula
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://github.com/padvincenzo/tombola
 */

$title = "Unisciti alla partita";
include_once "./includes/header.php";

// PIN
$pin = "";
if(isset($content["pin"]))
$pin = $content["pin"];
else if(isset($_POST['pin']))
$pin = $_POST['pin'];
else if(isset($_POST['id']))
$pin = $_POST['id'];

// Nick
$nick = "";
if(isset($_POST['nick']))
$nick = $_POST['nick'];

// Ordine:        PIN    -->    Nick    -->    Cartella

// Verifico presenza PIN

if($pin != "") {

  // Verifico validità PIN

  if(!is_a_pin($pin)) {
    inviaMessaggio("Il PIN che hai inserito non è valido", "./");
  }

  $query = query_param("select * from ".PREFIX."server where pin = ? and accessibile = true;", "s", $pin);

  if(mysqli_num_rows($query) == 1) {

    $server = mysqli_fetch_array($query);

    // Verifico presenza nick

    if($nick != "") {

      // Verifico validità Nick

      if(!is_a_username($nick)) {
        inviaMessaggio("Il nick deve avere da 2 a 20 caratteri tra lettere, numeri e . _ -", "client_login.php", array("pin" => $pin));
      }

      $idserver = (int) $server['idserver'];
      $query = query_param("select nick from ".PREFIX."utente where nick = ? and idserver = ? and uscito is null", "si", $nick, $idserver);

      if(mysqli_num_rows($query) == 0) {

        // Inserisco l'utente nel database
        query_param("insert into ".PREFIX."utente (nick, idserver) values (?, ?);", "si", $nick, $idserver);
        $_SESSION["idutente"] = mysqli_insert_id($dbh)."";

        inviaMessaggio("Benvenuto $nick,<br>scegli una cartella con cui giocare.", "select_cartella.php");

      } else {

        // Nick non valido
        inviaMessaggio("Spiacente, nick già in uso", "client_login.php", array("pin" => $pin));
      }
    }

  } else {

    // PIN non valido
    inviaMessaggio("Il PIN che hai inserito non è valido", "./");
  }

} else {

  // PIN non presente
  redirect("./");
}

?>

<p class="titolo">Game PIN: <?php echo $pin; ?></p>
<p>Inserisci un nickname</p>
<form name='f1' action='client_login.php' method='post'>
  <input name='nick' type='text' maxlength='30' required />
  <input type='hidden' name='pin' value='<?php echo $pin; ?>'>
  <br>
  <button type='submit'>Avanti</button>
</form>
<br>
<a href="./">Torna indietro</a>

<?php
include_once "./includes/footer.php";
?>
