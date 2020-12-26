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

$title = "Home";
include("page_header.php");
?>

<img id="logo" src="img/logo.svg" draggable="false" onclick="toggleFullscreen()">
<br>

<input id='pin' type='text' maxlength='5' autocomplete="off" placeholder="Game PIN¹" onkeyup="if(event.keyCode === 13) { event.preventDefault(); $('#btnGioca').click(); }">
<p id="w-help">¹Chi crea la partita ha il Game PIN</p>
<button id='btnGioca' onclick="checkPin();">Gioca!</button>
<button onclick='nuova_partita();'>Crea partita</button>

<?php
include("page_footer.php");
?>
