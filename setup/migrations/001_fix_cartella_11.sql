/**
 * Corregge il numero 14 assegnato per errore alla cartella 5 invece che alla 11.
 * Da eseguire solo sui database installati prima di questa correzione.
 * Se usi un prefisso diverso da "tombola_k_", aggiorna il nome della tabella.
 */

update tombola_k_comporre
   set idcartella = 11
 where idcartella = 5 and idnumero = 14 and riga = '1';
