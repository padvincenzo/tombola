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

include_once __DIR__ . "/connect.php";

// Percorso della radice del sito, per le pagine nelle sottocartelle (es. "../")
$root = $root ?? "";
ob_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title><?php echo $title ?> - Tombola!</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- MaxCDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

    <link rel="icon" href="<?php echo $root; ?>assets/img/icon.svg">
    <link rel="stylesheet" type="text/css" href="<?php echo $root; ?>assets/css/style.css">
    <script src="<?php echo $root; ?>assets/js/code.js"></script>
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
    if (isset($_SESSION['messaggio'])) {
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
