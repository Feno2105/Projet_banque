-- Base de données : tp_flight
CREATE DATABASE IF NOT EXISTS tp_flight;
USE tp_flight;

CREATE TABLE IF NOT EXISTS source_fond (
    id_source_fond INT AUTO_INCREMENT PRIMARY KEY,
    nom_source VARCHAR(255) NOT NULL
);

-- Table 1 : Fonds disponibles dans l’établissement
CREATE TABLE IF NOT EXISTS fonds_etablissement (
    id_fonds_etablissement INT AUTO_INCREMENT PRIMARY KEY,
    date_ajout DATE DEFAULT CURRENT_DATE,
    montant DECIMAL(15,2) NOT NULL,
    source INT NOT NULL,
    description VARCHAR(255),
    FOREIGN KEY (source) REFERENCES source_fond(id_source_fond)
);

-- Table 2 : Types de prêt avec taux et durée
CREATE TABLE IF NOT EXISTS type_pret (
    id_type_pret INT AUTO_INCREMENT PRIMARY KEY,
    nom_type_pret VARCHAR(100) NOT NULL,
    taux_interet DECIMAL(5,2) NOT NULL,
    duree_mois INT NOT NULL,
    montant_min DECIMAL(15,2),
    montant_max DECIMAL(15,2)
);

-- Table 3 : Clients
CREATE TABLE IF NOT EXISTS client (
    id_client INT AUTO_INCREMENT PRIMARY KEY,
    nom_client VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    telephone VARCHAR(20),
    adresse VARCHAR(255),
    date_inscription DATE DEFAULT CURRENT_DATE
);

-- Création de la table statut_pret
CREATE TABLE IF NOT EXISTS statut_pret (
    id_statut_pret INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(100) NOT NULL,
    UNIQUE KEY (libelle)  -- Évite les doublons de libellés
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- Table 4 : Prêts accordés aux clients
CREATE TABLE IF NOT EXISTS pret (
    id_pret INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT,
    type_pret_id INT,
    montant DECIMAL(15,2),
    reste_a_payer DECIMAL(15,2),
    date_debut DATE DEFAULT CURRENT_DATE,
    mensualite DECIMAL(15,2),  -- Ajout de la mensualité
    statut INT,
    FOREIGN KEY (client_id) REFERENCES client(id_client),
    FOREIGN KEY (type_pret_id) REFERENCES type_pret(id_type_pret),
    FOREIGN KEY (statut) REFERENCES statut_pret(id_statut_pret)
);
CREATE TABLE IF NOT EXISTS mode_paiement (
    id_mode_paiement INT AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS type_mouvement (
    id_type_mouvement INT AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(255) NOT NULL
);

-- Table 5 : Mouvement de remboursement
CREATE TABLE IF NOT EXISTS mouvement (
    id_mouvement INT AUTO_INCREMENT PRIMARY KEY,
    pret_id INT NOT NULL,
    client_id INT NOT NULL,
    date_mouvement DATE DEFAULT CURRENT_DATE,
    montant_paye DECIMAL(15,2) NOT NULL,
    reste_apres_paiement DECIMAL(15,2),
    mode_paiement VARCHAR(50),
    type_mouvement_id INT,
    FOREIGN KEY (type_mouvement_id) REFERENCES type_mouvement(id_type_mouvement),
    FOREIGN KEY (pret_id) REFERENCES pret(id_pret),
    FOREIGN KEY (client_id) REFERENCES client(id_client)
);

-- Table 6 : Fonds client (argent réellement sorti pour financement)
CREATE TABLE IF NOT EXISTS fonds_client (
    id_fonds_client INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    solde DECIMAL(15,2) NOT NULL,
    FOREIGN KEY (client_id) REFERENCES client(id_client)
);
CREATE TABLE remboursement (
    id_remboursement INT AUTO_INCREMENT PRIMARY KEY,
    id_pret INT,
    mois INT NOT NULL CHECK (mois BETWEEN 1 AND 12),
    annee INT NOT NULL,
    FOREIGN KEY (id_pret) REFERENCES pret(id_pret)
);

CREATE TABLE interet(
    id_interet INT PRIMARY KEY AUTO_INCREMENT,
    id_pret INT ,
    mois_debut INT,
    annee_debut INT,
    mois_fin INT,
    annee_fin INT,
    valeur DECIMAL(15,2),
    FOREIGN KEY (id_pret) REFERENCES pret(id_pret)
);
-- PRET FULL INFO

-- VIEWS
CREATE VIEW view_pret AS 
    select * from 
        pret p 
        JOIN client c 
            ON p.client_id = c.id_client JOIN 
            statut_pret s ON s.id_statut_pret = p.statut
        JOIN type_pret tp ON tp.id_type_pret = p.type_pret_id;

SELECT * FROM view_pret;