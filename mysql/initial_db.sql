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

drop table if exists tombola_k_vincere;
drop table if exists tombola_k_premio;
drop table if exists tombola_k_estrarre;
drop table if exists tombola_k_avere;
drop table if exists tombola_k_comporre;
drop table if exists tombola_k_numero;
drop table if exists tombola_k_cartella;
drop table if exists tombola_k_utente;
drop table if exists tombola_k_server;

create table tombola_k_server (
  idserver int auto_increment not null primary key,
  pin char(6) not null,
  data datetime not null,
  accessibile boolean not null,
  terminato boolean null,
  offlimits boolean null
);

create table tombola_k_utente (
  idutente int auto_increment not null primary key,
  idserver int not null references tombola_k_server(idserver),
  nick varchar(30) not null,
  uscito boolean null,
  privato boolean null
);

create table tombola_k_cartella (
  idcartella int auto_increment not null primary key
);

create table tombola_k_numero (
  idnumero int not null primary key,
  cartella char(1) not null,
  riga char(1) not null
);

create table tombola_k_comporre (
  idcomporre int auto_increment not null primary key,
  idcartella int not null references tombolaonline_cartella(idcartella),
  idnumero int not null references tombolaonline_numero(idnumero),
  riga char(1) not null
);

create table tombola_k_avere (
  idavere int auto_increment not null primary key,
  idutente int not null references tombola_k_utente(idutente),
  idcartella int not null references tombola_k_cartella(idcartella)
);

create table tombola_k_estrarre (
  idestrarre int auto_increment not null primary key,
  idserver int not null references tombola_k_server(idserver),
  idnumero int not null references tombola_k_numero(idnumero)
);

create table tombola_k_premio (
  idpremio int auto_increment not null primary key,
  testo varchar(10) not null
);

create table tombola_k_vincere (
  idvincere int auto_increment not null primary key,
  idutente int not null references tombola_k_utente(idutente),
  idpremio int not null references tombola_k_premio(idpremio)
);

insert into tombola_k_premio
  (testo)
 values
  ('Ambo'),
  ('Terna'),
  ('Quaterna'),
  ('Cinquina'),
  ('Tombola');

insert into tombola_k_numero
  (idnumero, cartella, riga)
values
  ( 1, '1', '1'), ( 2, '1', '1'), ( 3, '1', '1'), ( 4, '1', '1'), ( 5, '1', '1'),
  ( 6, '1', '2'), ( 7, '1', '2'), ( 8, '1', '2'), ( 9, '1', '2'), (10, '1', '2'),
  (11, '1', '3'), (12, '1', '3'), (13, '1', '3'), (14, '1', '3'), (15, '1', '3'),
  (46, '4', '1'), (47, '4', '1'), (48, '4', '1'), (49, '4', '1'), (50, '4', '1'),
  (51, '4', '2'), (52, '4', '2'), (53, '4', '2'), (54, '4', '2'), (55, '4', '2'),
  (56, '4', '3'), (57, '4', '3'), (58, '4', '3'), (59, '4', '3'), (60, '4', '3'),
  (16, '2', '1'), (17, '2', '1'), (18, '2', '1'), (19, '2', '1'), (20, '2', '1'),
  (21, '2', '2'), (22, '2', '2'), (23, '2', '2'), (24, '2', '2'), (25, '2', '2'),
  (26, '2', '3'), (27, '2', '3'), (28, '2', '3'), (29, '2', '3'), (30, '2', '3'),
  (61, '5', '1'), (62, '5', '1'), (63, '5', '1'), (64, '5', '1'), (65, '5', '1'),
  (66, '5', '2'), (67, '5', '2'), (68, '5', '2'), (69, '5', '2'), (70, '5', '2'),
  (71, '5', '3'), (72, '5', '3'), (73, '5', '3'), (74, '5', '3'), (75, '5', '3'),
  (31, '3', '1'), (32, '3', '1'), (33, '3', '1'), (34, '3', '1'), (35, '3', '1'),
  (36, '3', '2'), (37, '3', '2'), (38, '3', '2'), (39, '3', '2'), (40, '3', '2'),
  (41, '3', '3'), (42, '3', '3'), (43, '3', '3'), (44, '3', '3'), (45, '3', '3'),
  (76, '6', '1'), (77, '6', '1'), (78, '6', '1'), (79, '6', '1'), (80, '6', '1'),
  (81, '6', '2'), (82, '6', '2'), (83, '6', '2'), (84, '6', '2'), (85, '6', '2'),
  (86, '6', '3'), (87, '6', '3'), (88, '6', '3'), (89, '6', '3'), (90, '6', '3');

insert into tombola_k_cartella
  (idcartella)
 values
  ( 1), ( 2), ( 3), ( 4), ( 5), ( 6),
  ( 7), ( 8), ( 9), (10), (11), (12),
  (13), (14), (15), (16), (17), (18),
  (19), (20), (21), (22), (23), (24),
  (25), (26), (27), (28), (29), (30),
  (31), (32), (33), (34), (35), (36),
  (37), (38), (39), (40), (41), (42),
  (43), (44), (45), (46), (47), (48);

insert into tombola_k_comporre
  (idcartella, idnumero, riga)
 values
  ( 1, 22, '1'), ( 1, 52, '1'), ( 1, 64, '1'), ( 1, 71, '1'), ( 1, 81, '1'),
  ( 1,  1, '2'), ( 1, 11, '2'), ( 1, 36, '2'), ( 1, 55, '2'), ( 1, 77, '2'),
  ( 1, 19, '3'), ( 1, 24, '3'), ( 1, 46, '3'), ( 1, 69, '3'), ( 1, 87, '3'),
  ( 2, 15, '1'), ( 2, 30, '1'), ( 2, 57, '1'), ( 2, 74, '1'), ( 2, 84, '1'),
  ( 2,  2, '2'), ( 2, 27, '2'), ( 2, 49, '2'), ( 2, 65, '2'), ( 2, 88, '2'),
  ( 2,  9, '3'), ( 2, 18, '3'), ( 2, 34, '3'), ( 2, 58, '3'), ( 2, 75, '3'),
  ( 3,  3, '1'), ( 3, 23, '1'), ( 3, 41, '1'), ( 3, 60, '1'), ( 3, 76, '1'),
  ( 3, 10, '2'), ( 3, 25, '2'), ( 3, 48, '2'), ( 3, 50, '2'), ( 3, 82, '2'),
  ( 3, 16, '3'), ( 3, 33, '3'), ( 3, 67, '3'), ( 3, 79, '3'), ( 3, 90, '3'),
  ( 4,  4, '1'), ( 4, 32, '1'), ( 4, 42, '1'), ( 4, 61, '1'), ( 4, 86, '1'),
  ( 4, 12, '2'), ( 4, 20, '2'), ( 4, 45, '2'), ( 4, 51, '2'), ( 4, 70, '2'),
  ( 4,  7, '3'), ( 4, 21, '3'), ( 4, 35, '3'), ( 4, 56, '3'), ( 4, 63, '3'),
  ( 5, 13, '1'), ( 5, 40, '1'), ( 5, 53, '1'), ( 5, 72, '1'), ( 5, 80, '1'),
  ( 5, 17, '2'), ( 5, 37, '2'), ( 5, 44, '2'), ( 5, 66, '2'), ( 5, 85, '2'),
  ( 5,  5, '3'), ( 5, 28, '3'), ( 5, 38, '3'), ( 5, 54, '3'), ( 5, 78, '3'),
  ( 6,  6, '1'), ( 6, 26, '1'), ( 6, 31, '1'), ( 6, 62, '1'), ( 6, 83, '1'),
  ( 6,  8, '2'), ( 6, 29, '2'), ( 6, 43, '2'), ( 6, 59, '2'), ( 6, 73, '2'),
  ( 6, 14, '3'), ( 6, 39, '3'), ( 6, 47, '3'), ( 6, 68, '3'), ( 6, 89, '3'),
  ( 7, 21, '1'), ( 7, 53, '1'), ( 7, 64, '1'), ( 7, 71, '1'), ( 7, 83, '1'),
  ( 7,  7, '2'), ( 7, 13, '2'), ( 7, 32, '2'), ( 7, 56, '2'), ( 7, 77, '2'),
  ( 7, 15, '3'), ( 7, 25, '3'), ( 7, 49, '3'), ( 7, 67, '3'), ( 7, 85, '3'),
  ( 8, 10, '1'), ( 8, 31, '1'), ( 8, 51, '1'), ( 8, 74, '1'), ( 8, 81, '1'),
  ( 8,  4, '2'), ( 8, 29, '2'), ( 8, 46, '2'), ( 8, 61, '2'), ( 8, 86, '2'),
  ( 8,  5, '3'), ( 8, 17, '3'), ( 8, 39, '3'), ( 8, 57, '3'), ( 8, 79, '3'),
  ( 9,  9, '1'), ( 9, 20, '1'), ( 9, 42, '1'), ( 9, 63, '1'), ( 9, 72, '1'),
  ( 9, 11, '2'), ( 9, 22, '2'), ( 9, 47, '2'), ( 9, 59, '2'), ( 9, 88, '2'),
  ( 9, 12, '3'), ( 9, 33, '3'), ( 9, 69, '3'), ( 9, 75, '3'), ( 9, 90, '3'),
  (10,  2, '1'), (10, 35, '1'), (10, 40, '1'), (10, 62, '1'), (10, 87, '1'),
  (10, 18, '2'), (10, 24, '2'), (10, 45, '2'), (10, 50, '2'), (10, 78, '2'),
  (10,  3, '3'), (10, 28, '3'), (10, 38, '3'), (10, 52, '3'), (10, 68, '3'),
  ( 5, 14, '1'), (11, 41, '1'), (11, 54, '1'), (11, 70, '1'), (11, 82, '1'),
  (11, 19, '2'), (11, 34, '2'), (11, 43, '2'), (11, 65, '2'), (11, 89, '2'),
  (11,  8, '3'), (11, 26, '3'), (11, 37, '3'), (11, 58, '3'), (11, 73, '3'),
  (12,  1, '1'), (12, 23, '1'), (12, 30, '1'), (12, 60, '1'), (12, 80, '1'),
  (12,  6, '2'), (12, 27, '2'), (12, 44, '2'), (12, 55, '2'), (12, 76, '2'),
  (12, 16, '3'), (12, 36, '3'), (12, 48, '3'), (12, 66, '3'), (12, 84, '3'),
  (13, 20, '1'), (13, 54, '1'), (13, 63, '1'), (13, 75, '1'), (13, 83, '1'),
  (13,  4, '2'), (13, 11, '2'), (13, 31, '2'), (13, 56, '2'), (13, 76, '2'),
  (13, 15, '3'), (13, 25, '3'), (13, 43, '3'), (13, 65, '3'), (13, 88, '3'),
  (14, 10, '1'), (14, 37, '1'), (14, 57, '1'), (14, 71, '1'), (14, 80, '1'),
  (14,  1, '2'), (14, 27, '2'), (14, 48, '2'), (14, 69, '2'), (14, 89, '2'),
  (14,  7, '3'), (14, 18, '3'), (14, 39, '3'), (14, 59, '3'), (14, 78, '3'),
  (15,  2, '1'), (15, 23, '1'), (15, 45, '1'), (15, 61, '1'), (15, 74, '1'),
  (15, 12, '2'), (15, 24, '2'), (15, 47, '2'), (15, 58, '2'), (15, 84, '2'),
  (15, 13, '3'), (15, 38, '3'), (15, 68, '3'), (15, 77, '3'), (15, 86, '3'),
  (16,  3, '1'), (16, 33, '1'), (16, 40, '1'), (16, 60, '1'), (16, 82, '1'),
  (16, 14, '2'), (16, 21, '2'), (16, 44, '2'), (16, 52, '2'), (16, 79, '2'),
  (16,  6, '3'), (16, 26, '3'), (16, 35, '3'), (16, 53, '3'), (16, 66, '3'),
  (17, 16, '1'), (17, 41, '1'), (17, 50, '1'), (17, 72, '1'), (17, 81, '1'),
  (17, 17, '2'), (17, 34, '2'), (17, 42, '2'), (17, 64, '2'), (17, 87, '2'),
  (17,  9, '3'), (17, 29, '3'), (17, 36, '3'), (17, 55, '3'), (17, 73, '3'),
  (18,  5, '1'), (18, 22, '1'), (18, 30, '1'), (18, 62, '1'), (18, 85, '1'),
  (18,  8, '2'), (18, 28, '2'), (18, 46, '2'), (18, 51, '2'), (18, 70, '2'),
  (18, 19, '3'), (18, 32, '3'), (18, 49, '3'), (18, 67, '3'), (18, 90, '3'),
  (19, 24, '1'), (19, 53, '1'), (19, 62, '1'), (19, 74, '1'), (19, 82, '1'),
  (19,  3, '2'), (19, 17, '2'), (19, 30, '2'), (19, 55, '2'), (19, 75, '2'),
  (19, 19, '3'), (19, 29, '3'), (19, 41, '3'), (19, 64, '3'), (19, 87, '3'),
  (20, 10, '1'), (20, 38, '1'), (20, 56, '1'), (20, 71, '1'), (20, 88, '1'),
  (20,  5, '2'), (20, 22, '2'), (20, 46, '2'), (20, 68, '2'), (20, 89, '2'),
  (20,  6, '3'), (20, 14, '3'), (20, 39, '3'), (20, 58, '3'), (20, 73, '3'),
  (21,  1, '1'), (21, 20, '1'), (21, 44, '1'), (21, 63, '1'), (21, 70, '1'),
  (21, 11, '2'), (21, 23, '2'), (21, 49, '2'), (21, 57, '2'), (21, 85, '2'),
  (21, 12, '3'), (21, 34, '3'), (21, 67, '3'), (21, 77, '3'), (21, 90, '3'),
  (22,  2, '1'), (22, 31, '1'), (22, 43, '1'), (22, 61, '1'), (22, 81, '1'),
  (22, 13, '2'), (22, 26, '2'), (22, 47, '2'), (22, 51, '2'), (22, 72, '2'),
  (22,  9, '3'), (22, 28, '3'), (22, 37, '3'), (22, 54, '3'), (22, 65, '3'),
  (23, 15, '1'), (23, 40, '1'), (23, 52, '1'), (23, 76, '1'), (23, 83, '1'),
  (23, 18, '2'), (23, 33, '2'), (23, 42, '2'), (23, 60, '2'), (23, 84, '2'),
  (23,  4, '3'), (23, 21, '3'), (23, 36, '3'), (23, 59, '3'), (23, 79, '3'),
  (24,  7, '1'), (24, 25, '1'), (24, 32, '1'), (24, 66, '1'), (24, 80, '1'),
  (24,  8, '2'), (24, 27, '2'), (24, 45, '2'), (24, 50, '2'), (24, 78, '2'),
  (24, 16, '3'), (24, 35, '3'), (24, 48, '3'), (24, 69, '3'), (24, 86, '3'),
  (25, 21, '1'), (25, 54, '1'), (25, 63, '1'), (25, 73, '1'), (25, 81, '1'),
  (25,  9, '2'), (25, 12, '2'), (25, 33, '2'), (25, 59, '2'), (25, 79, '2'),
  (25, 16, '3'), (25, 27, '3'), (25, 44, '3'), (25, 69, '3'), (25, 90, '3'),
  (26, 13, '1'), (26, 30, '1'), (26, 52, '1'), (26, 75, '1'), (26, 80, '1'),
  (26,  2, '2'), (26, 23, '2'), (26, 42, '2'), (26, 67, '2'), (26, 87, '2'),
  (26,  8, '3'), (26, 19, '3'), (26, 37, '3'), (26, 55, '3'), (26, 78, '3'),
  (27,  4, '1'), (27, 22, '1'), (27, 41, '1'), (27, 62, '1'), (27, 70, '1'),
  (27, 14, '2'), (27, 24, '2'), (27, 45, '2'), (27, 56, '2'), (27, 86, '2'),
  (27, 18, '3'), (27, 39, '3'), (27, 66, '3'), (27, 74, '3'), (27, 88, '3'),
  (28,  1, '1'), (28, 34, '1'), (28, 40, '1'), (28, 60, '1'), (28, 82, '1'),
  (28, 15, '2'), (28, 25, '2'), (28, 48, '2'), (28, 53, '2'), (28, 77, '2'),
  (28,  7, '3'), (28, 28, '3'), (28, 36, '3'), (28, 58, '3'), (28, 68, '3'),
  (29, 11, '1'), (29, 43, '1'), (29, 51, '1'), (29, 71, '1'), (29, 84, '1'),
  (29, 17, '2'), (29, 32, '2'), (29, 46, '2'), (29, 61, '2'), (29, 85, '2'),
  (29,  3, '3'), (29, 29, '3'), (29, 35, '3'), (29, 57, '3'), (29, 76, '3'),
  (30,  5, '1'), (30, 20, '1'), (30, 31, '1'), (30, 64, '1'), (30, 83, '1'),
  (30,  6, '2'), (30, 26, '2'), (30, 47, '2'), (30, 50, '2'), (30, 72, '2'),
  (30, 10, '3'), (30, 38, '3'), (30, 49, '3'), (30, 65, '3'), (30, 89, '3'),
  (31, 22, '1'), (31, 53, '1'), (31, 66, '1'), (31, 71, '1'), (31, 80, '1'),
  (31,  1, '2'), (31, 10, '2'), (31, 34, '2'), (31, 54, '2'), (31, 79, '2'),
  (31, 15, '3'), (31, 29, '3'), (31, 43, '3'), (31, 68, '3'), (31, 90, '3'),
  (32, 11, '1'), (32, 31, '1'), (32, 52, '1'), (32, 72, '1'), (32, 83, '1'),
  (32,  6, '2'), (32, 24, '2'), (32, 47, '2'), (32, 60, '2'), (32, 85, '2'),
  (32,  9, '3'), (32, 17, '3'), (32, 32, '3'), (32, 59, '3'), (32, 74, '3'),
  (33,  5, '1'), (33, 23, '1'), (33, 42, '1'), (33, 61, '1'), (33, 73, '1'),
  (33, 14, '2'), (33, 28, '2'), (33, 46, '2'), (33, 51, '2'), (33, 82, '2'),
  (33, 16, '3'), (33, 37, '3'), (33, 67, '3'), (33, 75, '3'), (33, 88, '3'),
  (34,  3, '1'), (34, 30, '1'), (34, 44, '1'), (34, 63, '1'), (34, 84, '1'),
  (34, 19, '2'), (34, 21, '2'), (34, 49, '2'), (34, 55, '2'), (34, 76, '2'),
  (34,  7, '3'), (34, 25, '3'), (34, 38, '3'), (34, 57, '3'), (34, 64, '3'),
  (35, 12, '1'), (35, 40, '1'), (35, 50, '1'), (35, 70, '1'), (35, 86, '1'),
  (35, 18, '2'), (35, 35, '2'), (35, 45, '2'), (35, 69, '2'), (35, 87, '2'),
  (35,  4, '3'), (35, 20, '3'), (35, 39, '3'), (35, 58, '3'), (35, 78, '3'),
  (36,  2, '1'), (36, 26, '1'), (36, 33, '1'), (36, 62, '1'), (36, 81, '1'),
  (36,  8, '2'), (36, 27, '2'), (36, 41, '2'), (36, 56, '2'), (36, 77, '2'),
  (36, 13, '3'), (36, 36, '3'), (36, 48, '3'), (36, 65, '3'), (36, 89, '3'),
  (37, 24, '1'), (37, 50, '1'), (37, 60, '1'), (37, 72, '1'), (37, 81, '1'),
  (37,  4, '2'), (37, 18, '2'), (37, 33, '2'), (37, 51, '2'), (37, 73, '2'),
  (37, 19, '3'), (37, 26, '3'), (37, 40, '3'), (37, 62, '3'), (37, 83, '3'),
  (38, 13, '1'), (38, 34, '1'), (38, 53, '1'), (38, 76, '1'), (38, 86, '1'),
  (38,  1, '2'), (38, 21, '2'), (38, 45, '2'), (38, 66, '2'), (38, 88, '2'),
  (38,  5, '3'), (38, 15, '3'), (38, 36, '3'), (38, 56, '3'), (38, 77, '3'),
  (39,  6, '1'), (39, 22, '1'), (39, 48, '1'), (39, 65, '1'), (39, 70, '1'),
  (39, 14, '2'), (39, 25, '2'), (39, 49, '2'), (39, 52, '2'), (39, 87, '2'),
  (39, 17, '3'), (39, 32, '3'), (39, 68, '3'), (39, 74, '3'), (39, 89, '3'),
  (40,  8, '1'), (40, 31, '1'), (40, 42, '1'), (40, 67, '1'), (40, 85, '1'),
  (40, 10, '2'), (40, 20, '2'), (40, 47, '2'), (40, 58, '2'), (40, 71, '2'),
  (40,  9, '3'), (40, 27, '3'), (40, 39, '3'), (40, 59, '3'), (40, 69, '3'),
  (41, 11, '1'), (41, 41, '1'), (41, 54, '1'), (41, 75, '1'), (41, 82, '1'),
  (41, 16, '2'), (41, 35, '2'), (41, 43, '2'), (41, 63, '2'), (41, 84, '2'),
  (41,  7, '3'), (41, 23, '3'), (41, 38, '3'), (41, 55, '3'), (41, 78, '3'),
  (42,  2, '1'), (42, 28, '1'), (42, 30, '1'), (42, 61, '1'), (42, 80, '1'),
  (42,  3, '2'), (42, 29, '2'), (42, 44, '2'), (42, 57, '2'), (42, 79, '2'),
  (42, 12, '3'), (42, 37, '3'), (42, 46, '3'), (42, 64, '3'), (42, 90, '3'),
  (43, 23, '1'), (43, 51, '1'), (43, 61, '1'), (43, 70, '1'), (43, 80, '1'),
  (43,  9, '2'), (43, 14, '2'), (43, 38, '2'), (43, 57, '2'), (43, 71, '2'),
  (43, 18, '3'), (43, 25, '3'), (43, 48, '3'), (43, 68, '3'), (43, 89, '3'),
  (44, 12, '1'), (44, 35, '1'), (44, 50, '1'), (44, 77, '1'), (44, 81, '1'),
  (44,  2, '2'), (44, 20, '2'), (44, 44, '2'), (44, 62, '2'), (44, 84, '2'),
  (44,  7, '3'), (44, 17, '3'), (44, 37, '3'), (44, 56, '3'), (44, 79, '3'),
  (45,  8, '1'), (45, 24, '1'), (45, 40, '1'), (45, 64, '1'), (45, 73, '1'),
  (45, 15, '2'), (45, 29, '2'), (45, 43, '2'), (45, 53, '2'), (45, 85, '2'),
  (45, 19, '3'), (45, 31, '3'), (45, 66, '3'), (45, 76, '3'), (45, 90, '3'),
  (46,  1, '1'), (46, 32, '1'), (46, 41, '1'), (46, 63, '1'), (46, 87, '1'),
  (46, 16, '2'), (46, 22, '2'), (46, 46, '2'), (46, 54, '2'), (46, 75, '2'),
  (46,  3, '3'), (46, 26, '3'), (46, 33, '3'), (46, 55, '3'), (46, 65, '3'),
  (47, 10, '1'), (47, 42, '1'), (47, 58, '1'), (47, 72, '1'), (47, 83, '1'),
  (47, 13, '2'), (47, 36, '2'), (47, 49, '2'), (47, 67, '2'), (47, 86, '2'),
  (47,  6, '3'), (47, 27, '3'), (47, 39, '3'), (47, 59, '3'), (47, 78, '3'),
  (48,  4, '1'), (48, 21, '1'), (48, 30, '1'), (48, 60, '1'), (48, 82, '1'),
  (48,  5, '2'), (48, 28, '2'), (48, 45, '2'), (48, 52, '2'), (48, 74, '2'),
  (48, 11, '3'), (48, 34, '3'), (48, 47, '3'), (48, 69, '3'), (48, 88, '3');
