DROP TABLE IF EXISTS oeuvre;
DROP TABLE IF EXISTS artiste;

CREATE TABLE artiste
(
    id        INT(11) AUTO_INCREMENT,
    nom       VARCHAR(50),
    email     VARCHAR(50),
    telephone VARCHAR(10),
    PRIMARY KEY (id)
);

CREATE TABLE oeuvre
(
    id                 INT(11) AUTO_INCREMENT,
    artiste_id         INT(11),
    titre              VARCHAR(50),
    description        VARCHAR(2000),
    date_de_creation   DATE,
    FOREIGN KEY (artiste_id) REFERENCES artiste (id) ON DELETE CASCADE,
    PRIMARY KEY (id)
);

INSERT INTO artiste (id, nom, email, telephone)
VALUES (1, "Jerome Floyd", "VZlT3@example.com", "0645125596");


INSERT
INTO oeuvre (artiste_id, titre, description, date_de_creation, compteur_jaime, compteur_jaime_pas)
VALUES (1, "Le Reveur des Prairies",
        "Au cœur de cette vaste toile étendue, intitulée 'Le Reveur des Prairies',trône majestueusement un âne, dont la présence discrète évoque à la fois votre curiosité insatiable et votre aspiration indomptable à l'aventure. Son regard, empreint de malice et de sagesse, semble scruter l'horizon lointain, où se dessinent les contours de l'inconnu. Autour de lui, la prairie s'étend à perte de vue, tel un océan de verdure infinie, vibrant de vie et de mystère. Chaque brin d'herbe, chaque pétale de fleur semble participer à un ballet harmonieux, offrant à vous, cher spectateur, un tableau vivant de diversité et de beauté. Les couleurs se mêlent dans une symphonie enchanteresse, tandis que les rayons du soleil, émergeant timidement à l'horizon, caressent la scène d'une lumière dorée, promettant un nouveau jour empli d'aventures et de découvertes. Dans cet univers foisonnant de détails et de sensations, chaque élément semble avoir une histoire à raconter, un secret à révéler. Vous imaginez sans doute les doux murmures du vent dans les hautes herbes, les parfums enivrants des fleurs sauvages, le frisson de l'herbe sous les sabots de l'âne avide d'exploration. Et puis, il y a cet âne, fier et déterminé, prêt à partir à l'aventure. Il incarne à lui seul le voyageur qui sommeille en chacun de nous, rappelant avec force que les plus grandes explorations débutent souvent par un simple pas dans l'inconnu. Son attitude résolue vous invite au dépassement de soi, à l'audace de franchir les frontières du connu pour plonger tête baissée dans l'océan de l'inconnu. En contemplant cette scène empreinte de magie et de poésie, vous vous trouvez transporté dans un autre monde, où le temps semble suspendu et où les rêves prennent vie. Car au-delà de la simple représentation artistique, 'Le Rêveur des Prairies' éveille en chacun de vous cette soif d'aventure et cette fascination pour l'inconnu qui font battre le cœur de l'humanité depuis la nuit des temps.",
        "2024-04-13")

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prenom VARCHAR(255) NOT NULL,
    nom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NULL,
    password VARCHAR(255) NOT NULL,
    telephone VARCHAR(255) NULL,
    bio TEXT NOT NULL,
    photo VARCHAR(255) NOT NULL
);
