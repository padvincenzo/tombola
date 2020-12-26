![Homepage](screenshots/homepage.png)

# Tombola [![Donate](https://img.shields.io/badge/donate-paypal-blue.svg)](https://www.paypal.com/paypalme/VincenzoPadula)
Il classico gioco natalizio online.

## Contribuire
Chiunque può contribuire a questo progetto, in diversi modi:
* Traducendo il progetto in altre lingue;
* Scovando e segnalando/risolvendo [bug](https://github.com/padvincenzo/tombola/issues);
* Suggerendo nuove idee;
* Implementando nuove funzionalità.
Per qualunque dubbio o perplessità possiamo discuterne sulla [pagina apposita](https://github.com/padvincenzo/tombola/discussions)

Se volete installare la tombola sul vostro server, per poter provare delle modifiche, potete farlo, purchè sia menzionata la pagina principale di questo progetto su GitHub. Nelle discussioni potete anche proporre i vostri server, che aggiungerò alla tabella sottostante.

## Server disponibili
[Gioca sul mio server](https://vincenzopadula.altervista.org/tombola/)

### Altri server
_nessun altro server per ora_

## Installazione
Se vuoi installare la tombola sul tuo server, devi:
1.  Scaricare tutti i file sul tuo server;
2.  Aggiornare il file ``connect.php`` con le credenziali del tuo database e scegliere una password di amministratore;
3.  Aprire il sito alla pagina ``install.php`` per creare e inizializzare il database.
4.  Sei incoraggiato ad apportare modifiche e implementare nuove funzioni (nel rispetto della [licenza](https://github.com/padvincenzo/tombola/blob/main/LICENSE))

## Come funziona

### Pagine
![Pagine del sito](screenshots/pagine.png)

#### Amministrazione
* ``install.php`` crea il database e si autoelimina;
* ``reset.php`` effettua un reset del database.

### Database
![Modello E/R](mysql/modello_er.png)

