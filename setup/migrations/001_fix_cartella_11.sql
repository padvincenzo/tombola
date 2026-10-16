/**
 * Corregge il numero 14 assegnato per errore alla cartella 5 invece che alla 11.
 * Sulle installazioni nuove lo schema iniziale è già corretto e non cambia nulla.
 */

update {prefix}comporre
   set idcartella = 11
 where idcartella = 5 and idnumero = 14 and riga = '1';
