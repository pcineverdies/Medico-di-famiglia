DROP DATABASE IF EXISTS `medicoDiFamiglia`; 
CREATE DATABASE  IF NOT EXISTS `medicoDiFamiglia`;
USE `medicoDiFamiglia`;

-- -------------------------------- 
--  Tabella utente
-- --------------------------------
DROP TABLE IF EXISTS `utente`;
CREATE TABLE `utente`(
    `codiceUtente`              INT NOT NULL AUTO_INCREMENT,
    `nome`                      VARCHAR(100) NOT NULL,
    `cognome`                   VARCHAR(100) NOT NULL,
    `codiceFiscale`             VARCHAR(100) NOT NULL,
    `email`                     VARCHAR(100) NOT NULL,
    `password`                  VARCHAR(100) NOT NULL,
    `telefono`                  VARCHAR(10) NOT NULL,
    `stato`                     INT(1) NOT NULL,
    PRIMARY KEY(`codiceUtente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `utente`(`nome`,`cognome`,`codiceFiscale`,`email`,`password`,`telefono`,`stato`)
VALUES      ("Dottor.","Seldon","-","admin","$2y$10$arB6Yb3zigxaekXVnjSPrOsizrQCssYzYvB1pYN/gwE0INlriki0u","-","-1"),
            ("G","S","123","g.s@yahoo.com","$2y$10$tyFwANJ/O7UXkwftleeyCunGwcX9L8KYs3XBQ70PN.gwDceXjDlvq","123","1"),
            ("A","V","123","a.v@gmail.com","$2y$10$cItayKmvHp5oBFIjFE0P9.WWRW8N05maYxyDO.53y52x.sFpDFHpu","123","1");


-- -------------------------------- 
--  Tabella messagio
-- --------------------------------
DROP TABLE IF EXISTS `messaggio`;
CREATE TABLE `messaggio`(
    `codiceMessaggio`           INT NOT NULL AUTO_INCREMENT,
    `testo`                     TEXT NOT NULL,
    `timestampInvio`            TIMESTAMP NOT NULL,
    `destinatario`              INT NOT NULL,
    `mittente`                  INT NOT NULL,
    `letto`                     TINYINT NOT NULL DEFAULT 0,

    FOREIGN KEY (`destinatario`) REFERENCES `utente`(`codiceUtente`),
    FOREIGN KEY (`mittente`) REFERENCES `utente`(`codiceUtente`),

    PRIMARY KEY(`codiceMessaggio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `messaggio` (`codiceMessaggio`, `testo`, `timestampInvio`, `destinatario`, `mittente`, `letto`) VALUES
(1, 'Salve dottore, mi scusi il disturbo, vorrei sapere se ci saranno dei giorni di ambulatorio nella prima settimana di Febbraio. Purtroppo negli stessi giorni ho degli esami universitari, e vorrei organizzarmi per fare entrambe le cose.', '2021-01-27 17:55:31', 1, 2, 1),
(2, 'Buona serata Giacomo. Purtroppo prima del 31 di Gennaio non riesco a dire con precisione i giorni di ambulatorio. Senza entrare nel dettaglio, sono troppo preso da una nuova serie televisiva, e mi sono posto l''obbiettivo di finirla nel più breve tempo possibile.', '2021-01-27 17:57:40', 2, 1, 1),
(3, 'Sono curioso di sapere di che serie si è interessato...', '2021-01-27 17:57:53', 1, 2, 1),
(4, 'Il Trono di Spade', '2021-01-27 17:58:27', 2, 1, 1),
(5, 'Capisco. Le voglio solo che nel finale della serie Jon uccide Daenerys e Bran sale sul trono di spade', '2021-01-27 17:59:32', 1, 2, 1),
(6, '...', '2021-01-27 17:59:37', 2, 1, 1);

-- -------------------------------- 
--  Tabella articolo
-- --------------------------------
DROP TABLE IF EXISTS `articolo`;
CREATE TABLE `articolo`(
    `codiceArticolo`            INT NOT NULL AUTO_INCREMENT,
    `titolo`                    TEXT NOT NULL,
    `testo`                     TEXT NOT NULL,
    `timestampPubblicazione`    DATETIME NOT NULL,
    `autore`                    INT NOT NULL,
    `modificato`                TINYINT DEFAULT 0,

    FOREIGN KEY (`autore`) REFERENCES `utente`(`codiceUtente`),
    PRIMARY KEY(`codiceArticolo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `articolo`(`titolo`,`testo`,`timestampPubblicazione`,`autore`,`modificato`)
VALUES  ("Al bar Casablanca","Al bar Casablanca, seduti all’aperto una birra gelata.
Guardiamo le donne, guardiamo la gente che va in passeggiata.
Con aria un po' stanca, camicia slacciata in mano un maglione.
Parliamo parliamo, di proletariato, di rivoluzione.
Al bar Casablanca con una gauloise, la Nikon gli occhiali.
E sopra una sedia i titoli rossi dei nostri giornali.
Blue jeans scoloriti la barba sporcata da un po' di gelato.
Parliamo parliamo di rivoluzione di proletariato.","2021/01/03 12:23",1,0),

        ("Pensieri","Al mattino comincia col dire a te stesso: incontrerò un indiscreto, un ingrato, un prepotente, un impostore, un invidioso, un individualista. Il loro comportamento deriva ogni volta dall'ignoranza di ciò che è bene e ciò che è male. Quanto a me, poiché riflettendo sulla natura del bene e del male ho concluso che si tratta rispettivamente di ciò che è bello o brutto in senso morale, e, riflettendo sulla natura di chi sbaglia, ho concluso che si tratta di un mio parente, non perché derivi dallo stesso sangue o dallo stesso seme, ma in quanto compartecipe dell'intelletto e di una particella divina, ebbene, io non posso ricevere danno da nessuno di essi, perché nessuno potrà coinvolgermi in turpitudini, e nemmeno posso adirarmi con un parente né odiarlo. Infatti siamo nati per la collaborazione, come i piedi, le mani, le palpebre, i denti superiori e inferiori. Pertanto agire l'uno contro l'altro è contro natura: e adirarsi e respingere sdegnosamente qualcuno è agire contro di lui.","2020/12/18 12:23",1,0),

        ("Uso del latinorum per confondere","Gallia est omnis divisa in partes tres, quarum unam incolunt Belgae, aliam Aquitani, tertiam qui ipsorum lingua Celtae, nostra Galli appellantur. Hi omnes lingua, institutis, legibus inter se differunt. Gallos ab Aquitanis Garumna flumen, a Belgis Matrona et Sequana dividit. Horum omnium fortissimi sunt Belgae, propterea quod a cultu atque humanitate provinciae longissime absunt, minimeque ad eos mercatores saepe commeant atque ea quae ad effeminandos animos pertinent important, proximique sunt Germanis, qui trans Rhenum incolunt, quibuscum continenter bellum gerunt. Qua de causa Helvetii quoque reliquos Gallos virtute praecedunt, quod fere cotidianis proeliis cum Germanis contendunt, cum aut suis finibus eos prohibent aut ipsi in eorum finibus bellum gerunt. Eorum una, pars, quam Gallos obtinere dictum est, initium capit a flumine Rhodano, continetur Garumna flumine, Oceano, finibus Belgarum, attingit etiam ab Sequanis et Helvetiis flumen Rhenum, vergit ad septentriones. Belgae ab extremis Galliae finibus oriuntur, pertinent ad inferiorem partem fluminis Rheni, spectant in septentrionem et orientem solem. Aquitania a Garumna flumine ad Pyrenaeos montes et eam partem Oceani quae est ad Hispaniam pertinet; spectat inter occasum solis et septentriones.","2021/01/12 12:23",1,0),

        ("COVID-19 e fake news","Buona serata a tutt*.

        Negli ultimi mesi di pandemia sono girate molte notizie della categoria 'fake' che, purtroppo, hanno avuto una visibilità molto maggiore di quella che si meritivano. Le persone tendono a preferire l'autorità di fonti inattenibili che riescono a portare una notizia in modo attraente, piuttosto che l'affidabilità di persone nel settire da anni, che hanno dedicato la propria vita allo studio di certi fenomemi prima ancora che, sfortunatamente, diventassero noti all'opinione pubblica.
        
        Per questo motivo, oltre a ringraziare gli esperti che in questi mesi hanno portato un raggio di sole in quest'oscurità, voglio anche chiedervi di verificare sempre quanto leggete, prima di smuovere la notizia tra familiari, amici o, ancora di più, nei social. Inoltre, davanti a titoli altisonanti e attraenti, sappiate che spesso si nascondono fini di lucro, andando a sfruttare il click per proporvi della pubblicità.
        
        Nel mio piccolo, vi raccomando di tenere sempre le precauzioni consigliate dal Mnistero della Salute e dall'OMS. Ricordatevi che la pandemia non è stata ancora sconfitta, e che comportamenti irresponsabili del singolo, se amplificati dalla maggioranza, richischiano di vanificare il lavoro di questi mesi.","2021/01/31 12:23",1,0);

-- -------------------------------- 
--  Tabella giornoDisponibile
-- --------------------------------
DROP TABLE IF EXISTS `giornoDisponibile`;
CREATE TABLE `giornoDisponibile`(
    `data`                      DATE NOT NULL,
    `orarioInizio`              TIME NOT NULL,
    `orarioFine`                TIME NOT NULL,
    `stato`                     INT NOT NULL,
    PRIMARY KEY(`data`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `giornodisponibile` (`data`, `orarioInizio`, `orarioFine`, `stato`) VALUES
('2021-02-15', '16:00:00', '17:00:00', 1),
('2021-02-19', '14:00:00', '15:00:00', 1),
('2021-02-22', '16:00:00', '17:00:00', 1),
('2021-02-25', '12:00:00', '18:00:00', 0),
('2021-03-02', '11:00:00', '16:00:00', 1),
('2021-03-15', '16:00:00', '17:00:00', 1);

-- -------------------------------- 
--  Tabella visita
-- --------------------------------
DROP TABLE IF EXISTS `visita`;
CREATE TABLE `visita`(
    `codiceVisita`              INT NOT NULL AUTO_INCREMENT,
    `fasciaOraria`              TIME NOT NULL,
    `note`                      TEXT DEFAULT NULL,
    `codiceUtente`              INT NOT NULL,
    `data`                      DATE NOT NULL,

    FOREIGN KEY (`codiceUtente`) REFERENCES `utente`(`codiceUtente`),
    FOREIGN KEY (`data`) REFERENCES `giornoDisponibile`(`data`),

    PRIMARY KEY(`codiceVisita`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `visita` (`codiceVisita`, `fasciaOraria`, `note`, `codiceUtente`, `data`) VALUES
(1, '14:15:00', 'Negli ultimi tempi non riesco a respirare bene. Spesso ho la sensazione che il cuore batta più forte del dovuto, in corrispondenza ad un calo di pressioni che mi impedisce di continuare a fare quello che stavo facendo. Vorrei avere una sua consultazione.', 2, '2021-02-19'),
(2, '16:15:00', 'Ho un forte mal di gola da qualche settimana che non riesco a far passare, neanche con un uso abbondante di farmaci da banco.', 3, '2021-02-22'),
(3, '12:15:00', 'In vista della prossima riapertura dei centri sportivi, vorrei procedere ad una visita per attività sportiva non agonistica.', 2, '2021-03-02'),
(4, '14:15:00', '', 2, '2021-02-25');


DROP PROCEDURE IF EXISTS modificaGiornoDisponibile;
DELIMITER $$
CREATE PROCEDURE modificaGiornoDisponibile(dataInput DATE)
BEGIN
    DECLARE stato INT DEFAULT 0;
    DECLARE visite INT DEFAULT 0;

    SET stato = (
                    SELECT G.stato
                    FROM giornoDisponibile G
                    WHERE G.data = dataInput
                );
    
    SET visite = (
                    SELECT COUNT(*)
                    FROM visita V
                    WHERE V.data = dataInput
                 );
    IF stato = 1 AND visite = 0 THEN
        DELETE FROM giornoDisponibile
        WHERE data = dataInput;
    ELSE
        UPDATE giornoDisponibile G
        SET G.stato = IF(G.stato=0,1,0)
        WHERE G.data = dataInput;
    END IF;
END $$
DELIMITER ;
