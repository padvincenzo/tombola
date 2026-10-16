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

$title = "Statistiche";
include_once "./includes/header.php";

function getStat($query)
{
    global $dbh;
    $result = mysqli_query($dbh, $query);
    if ($result) {
        $n = (mysqli_fetch_array($result))["n"];
        return ($n != null) ? $n : "-";
    }
    return "-";
}

function printStat($name, $query)
{
    $n = getStat($query);
    echo "<tr><td>$name:</td><td>$n</td></tr>\n";
}

echo "<p>Statistiche</p>\n<table id='stats'>\n";
printStat("Partite concluse", "select count(*) n from " . PREFIX . "server where accessibile is false and terminato is true;");
printStat("Partite in corso", "select count(*) n from " . PREFIX . "server where accessibile is false and terminato is false and offlimits is false");
printStat("Partite in attesa", "select count(*) n from " . PREFIX . "server where accessibile is true and offlimits is false");

printStat("Partite mai iniziate", "select count(*) n from " . PREFIX . "server where accessibile is true and offlimits is true;");
printStat("Partite mai finite", "select count(*) n from " . PREFIX . "server where accessibile is false and terminato is false and offlimits is true;");

$queryPartiteConcluse = "select idserver from " . PREFIX . "server where accessibile is false and terminato is true";
$queryGiocatori = "select count(idserver) n from " . PREFIX . "utente where privato is null and uscito is null and idserver in ($queryPartiteConcluse) group by idserver";
$queryAbbandoni = "select count(idserver) n from " . PREFIX . "utente where privato is null and uscito is not null and idserver in ($queryPartiteConcluse) group by idserver";

printStat("Media giocatori per partita", "select format(avg(n), 1) n from ($queryGiocatori) t1;");
printStat("Maggior numero di giocatori in una partita", "select max(n) n from ($queryGiocatori) t1;");

printStat("Media abbandoni per partita", "select format(avg(n), 1) n from ($queryAbbandoni) t1;");
printStat("Maggior numero di abbandoni in una partita", "select max(n) n from ($queryAbbandoni) t1;");


echo "</table><br>\n" .
    "<button onclick='window.location.href=\"./\"'>Home</button>\n" .
    "<button onclick='window.location.reload()'>Ricarica</button>\n";


include_once "./includes/footer.php";
