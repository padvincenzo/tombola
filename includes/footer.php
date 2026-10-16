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

?>
    </div>

    <footer>
      <a href="https://github.com/padvincenzo/tombola" target="_blank" title="Visita la pagina GitHub del progetto">Pagina GitHub del progetto</a>
      <a href="https://github.com/padvincenzo/tombola/discussions" target="_blank" title="Discuti su questo progetto">Discuti su questo progetto</a>
      <a href="https://github.com/padvincenzo/tombola/issues" target="_blank" title="Segnala un bug">Segnala un bug</a>
      <a href="<?php echo $root; ?>stats.php" title="Visualizza le statistiche del gioco">Statistiche del gioco</a>
      <a href="https://www.paypal.com/paypalme/VincenzoPadula" target="_blank" title="Donazione tramite PayPal">Offrimi un caffé</a>
      <a href="https://www.linkedin.com/in/vincenzo-padula/" target="_blank" title="Visita il mio profilo su LinkedIn">Chi sono</a>
    </footer>
  </body>
</html>
<?php
mysqli_close($dbh);
ob_end_flush();
?>
