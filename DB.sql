#-- Créer la DB pour l'école
CREATE DATABASE IF NOT EXISTS reshotels;

#-- -----------------------Hotels --------------------------------
#-- Création de la table hotels
Use reshotels;
CREATE TABLE IF NOT EXISTS hotels (
                                      id_hotel int(10) unsigned NOT NULL AUTO_INCREMENT,
                                      nom_hotel varchar(50) DEFAULT NULL,
                                      adress_hotel varchar(150) DEFAULT NULL,
                                      PRIMARY KEY(id_hotel)
)
    ENGINE=InnoDB DEFAULT CHARSET=utf8
;

#-- Contenu de la table hotels
INSERT INTO hotels (id_hotel,nom_hotel,adresse_hotel) VALUES
                                                          (1,'Hotel A', 'adresse 1 ville 1'),
                                                          (2,'Hotel B', 'adresse 2 ville 2'),
                                                          (3,'Hotel C', 'adresse 3 ville 3'),
                                                          (4,'Hotel D', 'adresse 4 ville 4'),
                                                          (5,'Hotel E', 'adresse 5 ville 5'),
                                                          (6,'Hotel F', 'adresse 6 ville 6'),
                                                          (7,'Hotel G', 'adresse 7 ville 7'),
                                                          (8,'Hotel H', 'adresse 8 ville 7'),
                                                          (9,'Hotel I', 'adresse 9 ville 8')

;


#-- -----------------------Chambres--------------------------------
#-- Création de la table chambres
create table chambres
(
    id_chambre  int unsigned auto_increment
        primary key,
    num_chambre int null,
    hotel_id    int null
)
    engine = InnoDB
    charset = utf8;

INSERT INTO reshotels.chambres (id_chambre, num_chambre, hotel_id) VALUES
                                                                       (1, 12, 1),
                                                                       (2, 11, 2),
                                                                       (3, 11, 3),
                                                                       (4, 11, 4),
                                                                       (5, 12, 6),
                                                                       (6, 14, 7),
                                                                       (7, 15, 8),
                                                                       (8, 12, 5),
                                                                       (9, 16, 6),
                                                                       (10, 18, 2),
                                                                       (11, 21, 7),
                                                                       (12, 10, 8),
                                                                       (13, 1, 9),
                                                                       (14, 1, 5),
                                                                       (15, 22, 1),
                                                                       (16, 23, 2),
                                                                       (17, 23, 3),
                                                                       (18, 23, 4),
                                                                       (19, 22, 5),
                                                                       (20, 23, 6),
                                                                       (21, 23, 7),
                                                                       (22, 22, 8),
                                                                       (23, 26, 9),
                                                                       (24, 25, 4),
                                                                       (25, 24, 3),
                                                                       (26, 2, 1),
                                                                       (27, 3, 2),
                                                                       (28, 4, 3),
                                                                       (29, 3, 4),
                                                                       (30, 2, 6),
                                                                       (31, 5, 5),
                                                                       (32, 6, 7),
                                                                       (33, 6, 8),
                                                                       (34, 7, 3),
                                                                       (35, 4, 9),
                                                                       (36, 6, 3)
;



#-- -----------------------Clients--------------------------------
#-- Création de la table clients
CREATE TABLE IF NOT EXISTS clients(
                                      id_client int(10) unsigned NOT NULL AUTO_INCREMENT,
                                      nom_client varchar(50) DEFAULT NULL,
                                      email_client varchar(50) DEFAULT NULL,
                                      PRIMARY KEY(id_client)
)
    ENGINE=InnoDB DEFAULT CHARSET=utf8
;



#-- -----------------------Réservations--------------------------------
#-- Création de la table reservations
CREATE TABLE IF NOT EXISTS reservations (
                                            id_reservation int(10) unsigned NOT NULL AUTO_INCREMENT,
                                            dc_reservation date NOT NULL DEFAULT '0000-00-00',
                                            dd_reservation date NOT NULL DEFAULT '0000-00-00',
                                            df_reservation date NOT NULL DEFAULT '0000-00-00',
                                            client_id int(10) DEFAULT NULL,
                                            chambre_id int(10) DEFAULT NULL,
                                            PRIMARY KEY(id_reservation)
)
    ENGINE=InnoDB DEFAULT CHARSET=utf8
;





# ------------------requetes SQL-------------------------------

SELECT ho.nom_hotel, ch.num_chambre
FROM  hotels ho
          INNER JOIN chambres ch on ho.id_hotel = ch.hotel_id
          LEFT JOIN reservations re on ch.id_chambre = re.chambre_id
WHERE re.dd_reservation IS NULL
   OR re.df_reservation >= '2022-11-07'
    AND ho.id_hotel = 2

;

SELECT t1.id_chambre, t1.nom_hotel
FROM (
         SELECT ho.nom_hotel, ch.num_chambre, ch.id_chambre
         FROM  hotels ho
                   INNER JOIN chambres ch on ho.id_hotel = ch.hotel_id
                   INNER JOIN reservations res on ch.id_chambre = res.chambre_id
     )t1



SELECT *
FROM chambres ch
         LEFT JOIN reservations re on ch.id_chambre = re.chambre_id
         LEFT JOIN hotels ho on ch.hotel_id = ho.id_hotel
WHERE re.dd_reservation IS NULL OR
    (re.dd_reservation <> '2022-11-07' AND re.df_reservation >= '2022-11-07')

HAVING ch.hotel_id = 3
;

SELECT nom_hotel
FROM hotels


