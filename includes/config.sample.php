<?php

/**
 * Copia questo file in config.php e inserisci i tuoi dati.
 * config.php non viene versionato (vedi .gitignore).
 */

// Credenziali database
$host = 'localhost';
$user = '';
$database = '';
$psw = '';

// Hash della password di amministratore. Generalo con:
//   php -r "echo password_hash('la-tua-password', PASSWORD_DEFAULT);"
// Lasciandolo vuoto l'accesso di amministratore è disabilitato.
define("ADMIN_PW_HASH", "");
