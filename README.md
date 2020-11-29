# Tombola
Il classico gioco natalizio online.

[Gioca sul mio server](https://vincenzopadula.altervista.org/tombola/v1.1/)

![Homepage](screenshots/homepage.png)

## Installazione
Se vuoi installare la tombola sul tuo server, devi:
1.  Scaricare tutti i file sul tuo server;
2.  Nel file ``connect.php`` modificare le credenziali con quelle del tuo database;
3.  Aprire il sito alla pagina ``install.php``.

## Database
![Modello E/R](mysql/modello_er.png)

### Reset del database
Alcune tabelle del database non devono essere alterate/svuotate. Per resettare il database eseguire il seguente script ``MySql``:

```sql
TRUNCATE tombola_k_avere;
TRUNCATE tombola_k_estrarre;
TRUNCATE tombola_k_server;
TRUNCATE tombola_k_utente;
TRUNCATE tombola_k_vincere;
```

[![Donate](https://img.shields.io/badge/donate-paypal-blue.svg)](https://www.paypal.com/paypalme/VincenzoPadula)
