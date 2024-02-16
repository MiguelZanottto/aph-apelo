SELECT 'CREATE DATABASE nombre_de_la_base_de_datos'
    WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = 'tienda');

DROP TABLE IF EXISTS "funkos";
DROP SEQUENCE IF EXISTS funkos_id_seq;
DROP TABLE IF EXISTS "categorias";


CREATE SEQUENCE funkos_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 7 CACHE 1;

CREATE TABLE "public"."funkos"
(
    "id"                        bigint                         DEFAULT nextval('funkos_id_seq') NOT NULL,
    "nombre"                    character varying(255),
    "precio"                    double precision               DEFAULT '0.0',
    "stock"                  integer                        DEFAULT '0',
    "imagen"                    text                           DEFAULT 'https://via.placeholder.com/150',
    "created_at"            timestamp                          DEFAULT CURRENT_TIMESTAMP           NOT NULL,
    "updated_at"            timestamp                          DEFAULT CURRENT_TIMESTAMP           NOT NULL,
    "is_deleted"    boolean                                    DEFAULT false,
    "categoria_id"  uuid,
    CONSTRAINT "funkos_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

DROP TABLE IF EXISTS "categorias";
CREATE TABLE "public"."categorias" (
                                       "is_deleted" boolean DEFAULT false NOT NULL,
                                       "created_at" timestamp DEFAULT now() NOT NULL,
                                       "updated_at" timestamp DEFAULT now() NOT NULL,
                                       "id" uuid NOT NULL,
                                       "nombre" character varying(255) NOT NULL,
                                       CONSTRAINT "categorias_nombre_key" UNIQUE ("nombre"),
                                       CONSTRAINT "categorias_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

INSERT INTO "categorias" ("is_deleted", "created_at", "updated_at", "id", "nombre") VALUES
                                                                                        ('f',	'2023-11-02 11:43:24.717712',	'2023-11-02 11:43:24.717712',	'd69cf3db-b77d-4181-b3cd-5ca8107fb6a9',	'REAL MADRID'),
                                                                                        ('f',	'2023-11-02 11:43:24.717712',	'2023-11-02 11:43:24.717712',	'6dbcbf5e-8e1c-47cc-8578-7b0a33ebc154',	'BARCELONA'),
                                                                                        ('f',	'2023-11-02 11:43:24.717712',	'2023-11-02 11:43:24.717712',	'9def16db-362b-44c4-9fc9-77117758b5b0',	'MANCHESTER CITY'),
                                                                                        ('f',	'2023-11-02 11:43:24.717712',	'2023-11-02 11:43:24.717712',	'8c5c06ba-49d6-46b6-85cc-8246c0f362bc',	'PSG');

INSERT INTO "funkos" ("id", "nombre", "precio", "stock", "imagen", "created_at", "updated_at", "is_deleted", "categoria_id")
VALUES (1, 'Cristiano Ronaldo', 19.99, 50, 'https://files.cults3d.com/uploaders/14409669/illustration-file/6f9e9aab-b0d0-4a86-a01f-2078a099758e/744.jpg' , '2023-11-29 15:30:45.123456', '2023-11-29 15:30:45.123456', 'f', 'd69cf3db-b77d-4181-b3cd-5ca8107fb6a9'),
       (2, 'Vinicius Junior', 14.99, 75, 'https://cdn.wallapop.com/images/10420/fr/p6/__/c10420p953516829/i3589150702.jpg?pictureSize=W640' , '2023-11-29 15:30:45.123456', '2023-11-29 15:30:45.123456', 'f', 'd69cf3db-b77d-4181-b3cd-5ca8107fb6a9'),
       (3, 'Mbappe', 16.99, 32, 'https://i5.walmartimages.com.mx/mg/gm/1p/images/product-images/img_large/00088969842796l.jpg?odnHeight=612&odnWidth=612&odnBg=FFFFFF' , '2023-11-29 15:30:45.123456', '2023-11-29 15:30:45.123456', 'f', '8c5c06ba-49d6-46b6-85cc-8246c0f362bc'),
       (4, 'Erling Haarland', 11.99, 29, 'https://acdn.mitiendanube.com/stores/002/294/590/products/haaland-133a46116d6c05085a16992940795851-1024-1024.jpeg' , '2023-11-29 15:30:45.123456', '2023-11-29 15:30:45.123456', 'f', '9def16db-362b-44c4-9fc9-77117758b5b0'),
       (5, 'Neymar', 13.99, 12, 'https://cdn.idealo.com/folder/Product/200814/3/200814367/s11_produktbild_gross/funko-pop-football-paris-saint-germain-neymar.jpg' , '2023-11-29 15:30:45.123456', '2023-11-29 15:30:45.123456', 'f', '8c5c06ba-49d6-46b6-85cc-8246c0f362bc'),
       (6, 'Messi',25.99, 14, 'https://files.cults3d.com/uploaders/14001561/illustration-file/a29a992e-00ac-4ae4-a731-57a161e1ca89/publicidadmessi.jpg' , '2023-11-29 15:30:45.123456', '2023-11-29 15:30:45.123456', 'f', '6dbcbf5e-8e1c-47cc-8578-7b0a33ebc154');


DROP TABLE IF EXISTS "usuarios";
DROP SEQUENCE IF EXISTS usuarios_id_seq;
CREATE SEQUENCE usuarios_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 5 CACHE 1;

CREATE TABLE "public"."usuarios" (
                                     "is_deleted" boolean DEFAULT false NOT NULL,
                                     "created_at" timestamp DEFAULT now() NOT NULL,
                                     "id" bigint DEFAULT nextval('usuarios_id_seq') NOT NULL,
                                     "updated_at" timestamp DEFAULT now() NOT NULL,
                                     "apellidos" character varying(255) NOT NULL,
                                     "email" character varying(255) NOT NULL,
                                     "nombre" character varying(255) NOT NULL,
                                     "password" character varying(255) NOT NULL,
                                     "username" character varying(255) NOT NULL,
                                     CONSTRAINT "usuarios_email_key" UNIQUE ("email"),
                                     CONSTRAINT "usuarios_pkey" PRIMARY KEY ("id"),
                                     CONSTRAINT "usuarios_username_key" UNIQUE ("username")
) WITH (oids = false);

INSERT INTO "usuarios" ("is_deleted", "created_at", "id", "updated_at", "apellidos", "email", "nombre", "password", "username") VALUES
                                                                                                                                    ('f',	'2023-11-02 11:43:24.724871',	1,	'2023-11-02 11:43:24.724871',	'Zanotto Rojas',	'migzanotto18@hotmail.com',	'Miguel Angel',	'$2a$12$l6W3/XdoKeX0dkjFM8GKg.57LW0r93U5Tgh3zJaxolpP67EbpMRPC',	'miguelzanotto'),
                                                                                                                                    ('f',	'2023-11-02 11:43:24.730431',	2,	'2023-11-02 11:43:24.730431',	'User User',	'user@prueba.net',	'usuario',	'$2a$12$l6W3/XdoKeX0dkjFM8GKg.57LW0r93U5Tgh3zJaxolpP67EbpMRPC',	'user'),
                                                                                                                                    ('f',	'2023-11-02 11:43:24.733552',	3,	'2023-11-02 11:43:24.733552',	'Test Test',	'test@prueba.net',	'Test',	'$2a$10$Pd1yyq2NowcsDf4Cpf/ZXObYFkcycswqHAqBndE1wWJvYwRxlb.Pu',	'test'),
                                                                                                                                    ('f',	'2023-11-02 11:43:24.736674',	4,	'2023-11-02 11:43:24.736674',	'Otro Otro',	'otro@prueba.net',	'otro',	'$2a$12$3Q4.UZbvBMBEvIwwjGEjae/zrIr6S50NusUlBcCNmBd2382eyU0bS',	'otro');


DROP TABLE IF EXISTS "user_roles";
DROP SEQUENCE IF EXISTS user_roles_id_seq;
CREATE SEQUENCE user_roles_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 6 CACHE 1;

CREATE TABLE "public"."user_roles" (
                                       "user_id" bigint NOT NULL,
                                       "roles" character varying(50)
) WITH (oids = false);

INSERT INTO "user_roles" ("user_id", "roles") VALUES
                                                       (1,	'USER'),
                                                       (1,	'ADMIN'),
                                                       (2,	'USER'),
                                                       (3,	'USER'),
                                                       (4,	'USER');



ALTER TABLE ONLY "public"."funkos"
    ADD CONSTRAINT "fk2fwq10nwymfv7fumctxt9vpgb" FOREIGN KEY (categoria_id) REFERENCES categorias (id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."user_roles" ADD CONSTRAINT "FK_87b8888186ca9769c960e926870" FOREIGN KEY (user_id) REFERENCES usuarios(id) NOT DEFERRABLE;