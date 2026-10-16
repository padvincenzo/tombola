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

$title = "Home";
include_once "./includes/header.php";
?>

<img id="logo" src="assets/img/logo.svg" draggable="false">
<br>

<input id='pin' type='text' maxlength='5' autocomplete="off" placeholder="Game PIN¹"
    onkeyup="if(event.keyCode === 13) { event.preventDefault(); $('#btnGioca').click(); }">
<p id="w-help">¹Chi crea la partita ha il Game PIN</p>
<button id='btnGioca' onclick="checkPin();">Gioca!</button>
<button onclick='nuova_partita();'>Crea partita</button>

<?php
include_once "./includes/footer.php";
?>
