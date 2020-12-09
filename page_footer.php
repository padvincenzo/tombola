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
?>
    </div>

    <footer>
      <a href="https://github.com/padvincenzo/tombola" target="_blank" title="Visita la pagina GitHub del progetto">Pagina GitHub del progetto</a>
      <a href="stats.php" title="Visualizza le statistiche del gioco">Statistiche del gioco</a>
      <a href="https://www.paypal.com/paypalme/VincenzoPadula" target="_blank" title="Donazione tramite PayPal">Offrimi un caffé</a>
      <a href="https://www.linkedin.com/in/vincenzo-padula/" target="_blank" title="Visita il mio profilo su LinkedIn">Chi sono</a>
    </footer>
  </body>
</html>
<?php
mysqli_close($dbh);
ob_end_flush();
?>
