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

$title = "Scegli una cartella";
include("page_header.php");

// L'utente è ancora valido?
$query = mysqli_query($dbh, "select * from ".PREFIX."utente where idutente = '$idutente' and uscito is not true;");

if(mysqli_num_rows($query) == 1) {

  $user = mysqli_fetch_array($query);
  $idserver = $user['idserver'];

  // L'utente ha già una cartella?
  $query = mysqli_query($dbh, "select * from ".PREFIX."avere where idutente = '$idutente';");

  // Si, ce l'ha
  if(mysqli_num_rows($query) == 1) {
    redirect("./client.php");

    // No, non ce l'ha
  } else {

    // L'utente ha richiesto una cartella?
    if(isset($_POST['cartella'])) {

      // Quella cartella è ancora disponibile?
      $cartella = $_POST['cartella'];
      $query = mysqli_query($dbh, "select a.idcartella from ".PREFIX."avere a left join ".PREFIX."utente u on a.idutente = u.idutente where u.idserver = '$idserver' and a.idcartella = '$cartella';");

      // Si, assegno la cartella all'utente
      if(mysqli_num_rows($query) == 0) {

        mysqli_query($dbh, "insert into ".PREFIX."avere (idutente, idcartella) values ('$idutente', '$cartella');");
        redirect("client.php");

        // No, lo avviso e ricarico la pagina
      } else {

        inviaMessaggio("Spiacente, la cartella è già in uso da un altro utente", "select_cartella.php");
      }

      // Se non ha richiesto una cartella ne carico una random
    } else {

      // Qual è il PIN del server?
      $cicle = mysqli_fetch_array(mysqli_query($dbh, "select pin from ".PREFIX."server where idserver = '$idserver';"));
      $pin = $cicle['pin'];

      // Quali sono le cartelle disponibili?
      $query = mysqli_query($dbh, "select idcartella from ".PREFIX."cartella where idcartella not in ( select a.idcartella from ".PREFIX."avere a inner join ".PREFIX."utente u on a.idutente = u.idutente where u.idserver = '$idserver' and u.uscito is null);");

      $n = mysqli_num_rows($query);

      if($n == 0) {
        inviaMessaggio("Spiacente, non ci sono più cartelle disponibili", "./");

      } else {

        $array = array();

        while(($cicle = mysqli_fetch_array($query))){
          $array[] = $cicle['idcartella'];
        }

        // Ne scelgo una random
        $cartella = $array[rand(0, $n - 1)];

        // Da quali numeri è composta?
        $query = mysqli_query($dbh, "select idnumero, riga from ".PREFIX."comporre where idcartella = '$cartella';");

        $scheda = array();
        $scheda[0] = array();
        $scheda[1] = array();
        $scheda[2] = array();

        // Li metto in una matrice
        while($cicle = mysqli_fetch_array($query)){
          $n = $cicle['idnumero'];
          $r = $cicle['riga'];

          if($n == 90)
          $c = 8;
          else
          $c = $n / 10;

          $c = floor($c);
          $scheda[$r-1][$c] = $n;
        }

        echo "  <div class='titolo'>Game PIN: $pin</div>".
            "  <div id='client_cartella'>\n".
            "    <table class='cartella'>\n".
            "     <tr>\n".
            "        <td colspan='9' id='client_nome'>Cartella nº $cartella</td>\n".
            "     </tr>\n";

        for($i = 0; $i < 3; $i++){
          echo "     <tr>\n";
          for($j = 0; $j < 9; $j++){
            if(isset($scheda[$i][$j]))
            echo "        <td id='n".$scheda[$i][$j]."'>".$scheda[$i][$j]."</td>\n";
            else
            echo "        <td>·</td>\n";
          }
          echo "     </tr>\n";
        }
        echo "    </table>\n".
            "  </div>\n".
            "  <br>\n";

      }
    }
  }

} else {
  redirect("./client_login.php");
}
?>

<form action='select_cartella.php' method='post' style="display:inline-block;">
  <input type='hidden' name='cartella' value='<?php echo $cartella; ?>'>
  <button type='submit'>Scegli</button>
</form>
<button onclick="location.href='select_cartella.php'">Cambia</button>
<br><br>
<a href="client_logout.php">Esci dalla partita</a>

<?php
include("page_footer.php");
?>
