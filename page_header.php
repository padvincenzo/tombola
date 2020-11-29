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

include("connect.php");
ob_start( );
?>
<!DOCTYPE html>
<html>
<head>
  <title><?php echo $title ?> - Tombola!</title>
  <meta charset="UTF-8">

  <!-- MaxCDN -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script>
  <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

  <link rel="icon" href="img/icon.png">
  <link rel="stylesheet" type="text/css" href="style.css">
  <script src="code.js"></script>
</head>

<body>

  <div id="box_b" style="display:none">
    <div id="box">
      <div id="messaggio"></div>
      <br><br>
      <button id="annulla" class="small-button" style="display:none;">Annulla</button>
      <button id="ok" class="small-button">OK</button>
    </div>
  </div>

  <?php

  // Se c'è un messaggio o una notifica
  if(isset($_SESSION['messaggio'])) {
    $messaggio = $_SESSION['messaggio'][0];
    $content = json_decode(urldecode($_SESSION['messaggio'][1]), true);
    echo "<script>mostraMessaggio('$messaggio')</script>\n";
    unset($_SESSION['messaggio']);
  }

  ?>

  <form id="f1" method="POST" action="">
    <input type="hidden" id="id" value="" name="id" />
    <input type="hidden" id="id2" value="" name="id2" />
  </form>

  <div id="container">
