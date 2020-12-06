![Homepage](screenshots/homepage.png)

# Tombola
Il classico gioco natalizio online.

[Gioca sul mio server](https://vincenzopadula.altervista.org/tombola/)
[![Donate](https://img.shields.io/badge/donate-paypal-blue.svg)](https://www.paypal.com/paypalme/VincenzoPadula)

## Installazione
Se vuoi installare la tombola sul tuo server, devi:
1.  Scaricare tutti i file sul tuo server;
2.  Aggiornare il file ``connect.php`` con le credenziali del tuo database e scegliere una password di amministratore;
3.  Aprire il sito alla pagina ``install.php`` per creare e inizializzare il database.

## Database
![Modello E/R](mysql/modello_er.png)

### Amministrazione
* ``stats.php`` mostra le statistiche del sito;
* ``reset.php`` effettua un reset completo del database.
