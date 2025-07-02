-- ===================================
-- COMPLETE VOEDSELBANK DATABASE - FINAL VERSION
-- Alles in één script: database, tabellen, procedures, data
-- Met ALLE tabellen inclusief leveranciers, producten, magazijn, etc.
-- ===================================

DROP DATABASE IF EXISTS voedselbankdag_03;
CREATE DATABASE voedselbankdag_03 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE voedselbankdag_03;

-- HOOFDTABELLEN
CREATE TABLE allergies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL UNIQUE,
    beschrijving TEXT,
    omschrijving TEXT,
    anafylactisch_risico ENUM('geen', 'zeerlaag', 'laag', 'redelijk_hoog', 'hoog') DEFAULT 'laag',
    ernst_niveau ENUM('laag', 'middel', 'hoog') DEFAULT 'middel',
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE gezins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL,
    code VARCHAR(20) UNIQUE,
    omschrijving TEXT,
    adres VARCHAR(255),
    postcode VARCHAR(10),
    woonplaats VARCHAR(100),
    telefoon VARCHAR(20),
    email VARCHAR(255),
    aantal_personen INT DEFAULT 0,
    aantal_volwassenen INT DEFAULT 0,
    aantal_kinderen INT DEFAULT 0,
    aantal_babys INT DEFAULT 0,
    totaal_aantal_personen INT DEFAULT 0,
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE persoons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gezin_id INT,
    voornaam VARCHAR(100) NOT NULL,
    tussenvoegsel VARCHAR(50),
    achternaam VARCHAR(100) NOT NULL,
    geboortedatum DATE,
    geslacht ENUM('man', 'vrouw', 'anders') DEFAULT 'anders',
    type_persoon ENUM('Manager', 'Medewerker', 'Vrijwilliger', 'Klant') DEFAULT 'Klant',
    is_vertegenwoordiger BIT(1) DEFAULT 0,
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gezin_id) REFERENCES gezins(id) ON DELETE SET NULL
);

CREATE TABLE allergie_per_persoons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    persoon_id INT NOT NULL,
    allergie_id INT NOT NULL,
    ernst ENUM('laag', 'middel', 'hoog') DEFAULT 'middel',
    datum_vastgesteld DATE,
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (persoon_id) REFERENCES persoons(id) ON DELETE CASCADE,
    FOREIGN KEY (allergie_id) REFERENCES allergies(id) ON DELETE CASCADE,
    UNIQUE KEY unique_persoon_allergie (persoon_id, allergie_id)
);

-- LEVERANCIER EN PRODUCT TABELLEN
CREATE TABLE rols (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL UNIQUE,
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE gebruikers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    persoon_id INT,
    inlog_naam VARCHAR(100) NOT NULL UNIQUE,
    gebruikersnaam VARCHAR(100) NOT NULL,
    wachtwoord VARCHAR(255) NOT NULL,
    is_ingelogd BIT(1) DEFAULT 0,
    ingelogd DATETIME,
    uitgelogd DATETIME,
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (persoon_id) REFERENCES persoons(id) ON DELETE SET NULL
);

CREATE TABLE rol_per_gebruikers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gebruiker_id INT NOT NULL,
    rol_id INT NOT NULL,
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gebruiker_id) REFERENCES gebruikers(id) ON DELETE CASCADE,
    FOREIGN KEY (rol_id) REFERENCES rols(id) ON DELETE CASCADE,
    UNIQUE KEY unique_gebruiker_rol (gebruiker_id, rol_id)
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL UNIQUE,
    omschrijving TEXT,
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    straat VARCHAR(255),
    huisnummer VARCHAR(10),
    toevoeging VARCHAR(10),
    postcode VARCHAR(10),
    woonplaats VARCHAR(100),
    email VARCHAR(255),
    mobiel VARCHAR(20),
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE eetwensens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL UNIQUE,
    omschrijving TEXT,
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE eetwens_per_gezins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gezin_id INT NOT NULL,
    eetwens_id INT NOT NULL,
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gezin_id) REFERENCES gezins(id) ON DELETE CASCADE,
    FOREIGN KEY (eetwens_id) REFERENCES eetwensens(id) ON DELETE CASCADE,
    UNIQUE KEY unique_gezin_eetwens (gezin_id, eetwens_id)
);

CREATE TABLE contact_per_gezins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gezin_id INT NOT NULL,
    contact_id INT NOT NULL,
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gezin_id) REFERENCES gezins(id) ON DELETE CASCADE,
    FOREIGN KEY (contact_id) REFERENCES contacts(id) ON DELETE CASCADE,
    UNIQUE KEY unique_gezin_contact (gezin_id, contact_id)
);

CREATE TABLE leveranciers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(255) NOT NULL,
    contact_persoon VARCHAR(255),
    leverancier_nummer VARCHAR(50) UNIQUE,
    leverancier_type ENUM('Bedrijf', 'Instelling', 'Overheid', 'Particulier', 'Donor') DEFAULT 'Bedrijf',
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE contact_per_leveranciers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    leverancier_id INT NOT NULL,
    contact_id INT NOT NULL,
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (leverancier_id) REFERENCES leveranciers(id) ON DELETE CASCADE,
    FOREIGN KEY (contact_id) REFERENCES contacts(id) ON DELETE CASCADE,
    UNIQUE KEY unique_leverancier_contact (leverancier_id, contact_id)
);

CREATE TABLE magazijns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ontvangstdatum DATE,
    uitleveringsdatum DATE,
    verpakkings_eenheid VARCHAR(100),
    aantal INT DEFAULT 0,
    locatie VARCHAR(255),
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categorie_id INT,
    naam VARCHAR(255) NOT NULL,
    soort_allergie VARCHAR(255),
    barcode VARCHAR(100),
    houdbaarheidsdatum DATE,
    omschrijving TEXT,
    status ENUM('OpVoorraad', 'NietOpVoorraad', 'NietLeverbaar', 'OverHoudbaarheidsDatum') DEFAULT 'OpVoorraad',
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE product_per_leveranciers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    leverancier_id INT NOT NULL,
    product_id INT NOT NULL,
    datum_aangeleverd DATE,
    datum_eerst_volgende_levering DATE,
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (leverancier_id) REFERENCES leveranciers(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_leverancier_product (leverancier_id, product_id)
);

CREATE TABLE product_per_magazijns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    magazijn_id INT NOT NULL,
    locatie VARCHAR(255),
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (magazijn_id) REFERENCES magazijns(id) ON DELETE CASCADE,
    UNIQUE KEY unique_product_magazijn (product_id, magazijn_id)
);

CREATE TABLE voedselpakkets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gezin_id INT,
    pakket_nummer VARCHAR(50),
    datum_samenstelling DATE,
    datum_uitgifte DATE,
    status ENUM('Uitgereikt', 'NietUitgereikt', 'NietMeerIngeschreven') DEFAULT 'NietUitgereikt',
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gezin_id) REFERENCES gezins(id) ON DELETE SET NULL
);

CREATE TABLE product_per_voedselpakkets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    voedselpakket_id INT NOT NULL,
    product_id INT NOT NULL,
    aantal_product_eenheden INT DEFAULT 1,
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (voedselpakket_id) REFERENCES voedselpakkets(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_voedselpakket_product (voedselpakket_id, product_id)
);

-- LARAVEL STANDAARD TABELLEN
CREATE TABLE users (
    id bigint unsigned NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    email_verified_at timestamp NULL DEFAULT NULL,
    password varchar(255) NOT NULL,
    remember_token varchar(100) DEFAULT NULL,
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY users_email_unique (email)
);

CREATE TABLE sessions (
    id varchar(255) NOT NULL,
    user_id bigint unsigned DEFAULT NULL,
    ip_address varchar(45) DEFAULT NULL,
    user_agent text,
    payload longtext NOT NULL,
    last_activity int NOT NULL,
    PRIMARY KEY (id),
    KEY sessions_user_id_index (user_id),
    KEY sessions_last_activity_index (last_activity)
);

CREATE TABLE cache (
    `key` varchar(255) NOT NULL,
    value mediumtext NOT NULL,
    expiration int NOT NULL,
    PRIMARY KEY (`key`)
);

CREATE TABLE cache_locks (
    `key` varchar(255) NOT NULL,
    owner varchar(255) NOT NULL,
    expiration int NOT NULL,
    PRIMARY KEY (`key`)
);

CREATE TABLE jobs (
    id bigint unsigned NOT NULL AUTO_INCREMENT,
    queue varchar(255) NOT NULL,
    payload longtext NOT NULL,
    attempts tinyint unsigned NOT NULL,
    reserved_at int unsigned DEFAULT NULL,
    available_at int unsigned NOT NULL,
    created_at int unsigned NOT NULL,
    PRIMARY KEY (id),
    KEY jobs_queue_index (queue)
);

CREATE TABLE job_batches (
    id varchar(255) NOT NULL,
    name varchar(255) NOT NULL,
    total_jobs int NOT NULL,
    pending_jobs int NOT NULL,
    failed_jobs int NOT NULL,
    failed_job_ids longtext NOT NULL,
    options mediumtext,
    cancelled_at int DEFAULT NULL,
    created_at int NOT NULL,
    finished_at int DEFAULT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE failed_jobs (
    id bigint unsigned NOT NULL AUTO_INCREMENT,
    uuid varchar(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload longtext NOT NULL,
    exception longtext NOT NULL,
    failed_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY failed_jobs_uuid_unique (uuid)
);

-- INDEXES VOOR OPTIMALE PERFORMANCE
CREATE INDEX idx_allergie_naam ON allergies(naam);
CREATE INDEX idx_allergie_actief ON allergies(is_actief);
CREATE INDEX idx_gezin_code ON gezins(code);
CREATE INDEX idx_gezin_naam ON gezins(naam);
CREATE INDEX idx_persoon_gezin ON persoons(gezin_id);
CREATE INDEX idx_persoon_naam ON persoons(achternaam, voornaam);
CREATE INDEX idx_app_persoon ON allergie_per_persoons(persoon_id);
CREATE INDEX idx_app_allergie ON allergie_per_persoons(allergie_id);
CREATE INDEX idx_leverancier_nummer ON leveranciers(leverancier_nummer);
CREATE INDEX idx_product_barcode ON products(barcode);
CREATE INDEX idx_product_categorie ON products(categorie_id);
CREATE INDEX idx_voedselpakket_gezin ON voedselpakkets(gezin_id);
CREATE INDEX idx_contact_email ON contacts(email);

DELIMITER //

-- STORED PROCEDURES
CREATE PROCEDURE GetGezinnenMetAllergien()
BEGIN
    SELECT DISTINCT 
        g.id,
        g.naam,
        g.code,
        g.adres,
        g.postcode,
        g.woonplaats,
        g.telefoon,
        g.email,
        g.aantal_personen,
        COUNT(DISTINCT app.id) AS aantal_allergie_registraties,
        COUNT(DISTINCT p.id) AS aantal_personen_met_allergie
    FROM gezins g
    INNER JOIN persoons p ON g.id = p.gezin_id
    INNER JOIN allergie_per_persoons app ON p.id = app.persoon_id
    INNER JOIN allergies a ON app.allergie_id = a.id
    WHERE g.is_actief = TRUE AND p.is_actief = TRUE AND app.is_actief = TRUE AND a.is_actief = TRUE
    GROUP BY g.id, g.naam, g.code, g.adres, g.postcode, g.woonplaats, g.telefoon, g.email, g.aantal_personen
    ORDER BY g.naam;
END //

CREATE PROCEDURE FilterGezinnenByAllergie(IN allergie_id INT)
BEGIN
    SELECT DISTINCT 
        g.id,
        g.naam,
        g.code,
        g.adres,
        g.postcode,
        g.woonplaats,
        g.telefoon,
        g.email,
        a.naam AS allergie_naam,
        COUNT(DISTINCT p.id) AS aantal_personen_met_allergie
    FROM gezins g
    INNER JOIN persoons p ON g.id = p.gezin_id
    INNER JOIN allergie_per_persoons app ON p.id = app.persoon_id
    INNER JOIN allergies a ON app.allergie_id = a.id
    WHERE g.is_actief = TRUE AND p.is_actief = TRUE AND app.is_actief = TRUE AND a.is_actief = TRUE AND a.id = allergie_id
    GROUP BY g.id, g.naam, g.code, g.adres, g.postcode, g.woonplaats, g.telefoon, g.email, a.naam
    ORDER BY g.naam;
END //

CREATE PROCEDURE GetGezinAllergieDetails(IN gezin_id INT)
BEGIN
    SELECT 
        p.id AS persoon_id,
        CONCAT(p.voornaam, 
               CASE WHEN p.tussenvoegsel IS NOT NULL THEN CONCAT(' ', p.tussenvoegsel) ELSE '' END,
               ' ', p.achternaam) AS volledige_naam,
        p.voornaam,
        p.tussenvoegsel,
        p.achternaam,
        p.geboortedatum,
        p.geslacht,
        p.type_persoon,
        p.is_vertegenwoordiger,
        a.id AS allergie_id,
        a.naam AS allergie_naam,
        a.beschrijving AS allergie_beschrijving,
        a.omschrijving AS allergie_omschrijving,
        a.ernst_niveau,
        a.anafylactisch_risico,
        app.ernst AS persoonlijke_ernst,
        app.opmerking,
        app.datum_vastgesteld,
        app.created_at
    FROM persoons p
    INNER JOIN allergie_per_persoons app ON p.id = app.persoon_id
    INNER JOIN allergies a ON app.allergie_id = a.id
    WHERE p.gezin_id = gezin_id AND p.is_actief = TRUE AND app.is_actief = TRUE AND a.is_actief = TRUE
    ORDER BY p.achternaam, p.voornaam, a.naam;
END //

CREATE PROCEDURE UpdatePersoonAllergie(
    IN p_persoon_id INT,
    IN p_oude_allergie_id INT,
    IN p_nieuwe_allergie_id INT,
    IN p_ernst ENUM('laag', 'middel', 'hoog'),
    IN p_opmerking VARCHAR(255),
    OUT success_message VARCHAR(255),
    OUT error_message VARCHAR(255)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SET error_message = 'Er is een fout opgetreden bij het wijzigen van de allergie';
        SET success_message = NULL;
    END;

    START TRANSACTION;
    
    DELETE FROM allergie_per_persoons WHERE persoon_id = p_persoon_id AND allergie_id = p_oude_allergie_id;
    
    INSERT INTO allergie_per_persoons (persoon_id, allergie_id, ernst, opmerking, datum_vastgesteld, is_actief, datum_aangemaakt) 
    VALUES (p_persoon_id, p_nieuwe_allergie_id, p_ernst, p_opmerking, CURDATE(), 1, CURRENT_TIMESTAMP(6));
    
    SET success_message = 'Allergie succesvol gewijzigd';
    SET error_message = NULL;
    
    COMMIT;
END //

CREATE PROCEDURE GetAllergieStatistieken()
BEGIN
    SELECT 
        COUNT(*) AS totaal_allergieen,
        (SELECT COUNT(DISTINCT p.gezin_id) FROM persoons p INNER JOIN allergie_per_persoons app ON p.id = app.persoon_id WHERE app.is_actief = TRUE) AS gezinnen_met_allergieen,
        (SELECT COUNT(DISTINCT app.persoon_id) FROM allergie_per_persoons app WHERE app.is_actief = TRUE) AS personen_met_allergieen,
        (SELECT COUNT(*) FROM allergie_per_persoons WHERE is_actief = TRUE) AS totaal_allergie_registraties,
        (SELECT COUNT(*) FROM allergie_per_persoons app INNER JOIN allergies a ON app.allergie_id = a.id WHERE app.is_actief = TRUE AND a.anafylactisch_risico = 'hoog') AS hoog_risico_allergieen
    FROM allergies WHERE is_actief = TRUE;
END //

CREATE PROCEDURE GetPopulaireAllergien(IN limiet INT)
BEGIN
    SELECT 
        a.id,
        a.naam,
        a.anafylactisch_risico,
        COUNT(app.id) AS aantal_registraties
    FROM allergies a
    INNER JOIN allergie_per_persoons app ON a.id = app.allergie_id
    WHERE a.is_actief = TRUE AND app.is_actief = TRUE
    GROUP BY a.id, a.naam, a.anafylactisch_risico
    ORDER BY aantal_registraties DESC
    LIMIT limiet;
END //

DELIMITER ;

-- DATA INVOEGEN
-- Allergieën (aangepast volgens specificatie)
INSERT INTO allergies (id, naam, beschrijving, omschrijving, anafylactisch_risico, ernst_niveau, is_actief, datum_aangemaakt) VALUES
(1, 'Gluten', 'Gluten-intolerantie/Coeliakie', 'Allergisch voor gluten', 'zeerlaag', 'middel', 1, CURRENT_TIMESTAMP(6)),
(2, 'Pindas', 'Pinda allergie', 'Allergisch voor pindas', 'hoog', 'hoog', 1, CURRENT_TIMESTAMP(6)),
(3, 'Schaaldieren', 'Schaaldieren allergie', 'Allergisch voor schaaldieren', 'redelijk_hoog', 'hoog', 1, CURRENT_TIMESTAMP(6)),
(4, 'Hazelnoten', 'Hazelnoot allergie', 'Allergisch voor hazelnoten', 'laag', 'middel', 1, CURRENT_TIMESTAMP(6)),
(5, 'Lactose', 'Lactose-intolerantie', 'Allergisch voor lactose', 'zeerlaag', 'laag', 1, CURRENT_TIMESTAMP(6)),
(6, 'Soja', 'Soja-allergie', 'Allergisch voor soja', 'zeerlaag', 'middel', 1, CURRENT_TIMESTAMP(6));

-- Rollen
INSERT INTO rols (id, naam, is_actief, datum_aangemaakt) VALUES
(1, 'Manager', 1, CURRENT_TIMESTAMP(6)),
(2, 'Medewerker', 1, CURRENT_TIMESTAMP(6)),
(3, 'Vrijwilliger', 1, CURRENT_TIMESTAMP(6));

-- Categorieën
INSERT INTO categories (id, naam, omschrijving, is_actief, datum_aangemaakt) VALUES
(1, 'AGF', 'Aardappelen groente en fruit', 1, CURRENT_TIMESTAMP(6)),
(2, 'KV', 'Kaas en vleeswaren', 1, CURRENT_TIMESTAMP(6)),
(3, 'ZPE', 'Zuivel plantaardig en eieren', 1, CURRENT_TIMESTAMP(6)),
(4, 'BB', 'Bakkerij en Banket', 1, CURRENT_TIMESTAMP(6)),
(5, 'FSKT', 'Frisdranken, sappen, koffie en thee', 1, CURRENT_TIMESTAMP(6)),
(6, 'PRW', 'Pasta, rijst en wereldkeuken', 1, CURRENT_TIMESTAMP(6)),
(7, 'SSKO', 'Soepen, sauzen, kruiden en olie', 1, CURRENT_TIMESTAMP(6)),
(8, 'SKCC', 'Snoep, koek, chips en chocolade', 1, CURRENT_TIMESTAMP(6)),
(9, 'BVH', 'Baby, verzorging en hygiëne', 1, CURRENT_TIMESTAMP(6));

-- Contacten
INSERT INTO contacts (id, straat, huisnummer, toevoeging, postcode, woonplaats, email, mobiel, is_actief, datum_aangemaakt) VALUES
(1, 'Prinses Irenestraat', '12', 'A', '5271TH', 'Maaskantje', 'j.van.zevenhuizen@gmail.com', '+31 623456123', 1, CURRENT_TIMESTAMP(6)),
(2, 'Gibraltarstraat', '234', NULL, '5271TJ', 'Maaskantje', 'a.bergkamp@hotmail.com', '+31 623456123', 1, CURRENT_TIMESTAMP(6)),
(3, 'Der Kinderenstraat', '456', 'Bis', '5271TH', 'Maaskantje', 's.van.de.heuvel@gmail.com', '+31 623456123', 1, CURRENT_TIMESTAMP(6)),
(4, 'Nachtegaalstraat', '233', 'A', '5271TJ', 'Maaskantje', 'e.scherder@gmail.com', '+31 623456123', 1, CURRENT_TIMESTAMP(6)),
(5, 'Bertram Russellstraat', '45', NULL, '5271TH', 'Maaskantje', 'f.de.jong@hotmail.com', '+31 623456123', 1, CURRENT_TIMESTAMP(6)),
(6, 'Leonardo Da VinciHof', '34', NULL, '5271ZE', 'Maaskantje', 'h.van.der.berg@gmail.com', '+31 623456123', 1, CURRENT_TIMESTAMP(6)),
(7, 'Siegfried Knutsenlaan', '234', NULL, '5271ZE', 'Maaskantje', 'r.ter.weijden@ah.nl', '+31 623456123', 1, CURRENT_TIMESTAMP(6)),
(8, 'Theo de Bokstraat', '256', NULL, '5271ZH', 'Maaskantje', 'l.pastoor@gmail.com', '+31 623456123', 1, CURRENT_TIMESTAMP(6)),
(9, 'Meester van Leerhof', '2', 'A', '5271ZH', 'Maaskantje', 'm.yazidi@gemeenteutrecht.nl', '+31 623456123', 1, CURRENT_TIMESTAMP(6)),
(10, 'Van Wemelenplantsoen', '300', NULL, '5271TH', 'Maaskantje', 'b.van.driel@gmail.com', '+31 623456123', 1, CURRENT_TIMESTAMP(6)),
(11, 'Terlingenhof', '20', NULL, '5271TH', 'Maaskantje', 'j.pastorius@gmail.com', '+31 623456356', 1, CURRENT_TIMESTAMP(6)),
(12, 'Veldhoen', '31', NULL, '5271ZE', 'Maaskantje', 's.dollaard@gmail.com', '+31 623452314', 1, CURRENT_TIMESTAMP(6)),
(13, 'ScheringaDreef', '37', NULL, '5271ZE', 'Vught', 'j.blokker@gemeentevught.nl', '+31 623452314', 1, CURRENT_TIMESTAMP(6));

-- Eetwensen
INSERT INTO eetwensens (id, naam, omschrijving, is_actief, datum_aangemaakt) VALUES
(1, 'GeenVarken', 'Geen Varkensvlees', 1, CURRENT_TIMESTAMP(6)),
(2, 'Veganistisch', 'Geen zuivelproducten en vlees', 1, CURRENT_TIMESTAMP(6)),
(3, 'Vegetarisch', 'Geen vlees', 1, CURRENT_TIMESTAMP(6)),
(4, 'Omnivoor', 'Geen beperkingen', 1, CURRENT_TIMESTAMP(6));

-- Gezinnen
INSERT INTO gezins (id, naam, code, omschrijving, aantal_volwassenen, aantal_kinderen, aantal_babys, totaal_aantal_personen, is_actief, datum_aangemaakt) VALUES
(1, 'ZevenhuizenGezin', 'G0001', 'Bijstandsgezin', 2, 2, 0, 4, 1, CURRENT_TIMESTAMP(6)),
(2, 'BergkampGezin', 'G0002', 'Bijstandsgezin', 2, 1, 1, 4, 1, CURRENT_TIMESTAMP(6)),
(3, 'HeuvelGezin', 'G0003', 'Bijstandsgezin', 2, 0, 0, 2, 1, CURRENT_TIMESTAMP(6)),
(4, 'ScherderGezin', 'G0004', 'Bijstandsgezin', 1, 0, 2, 3, 1, CURRENT_TIMESTAMP(6)),
(5, 'DeJongGezin', 'G0005', 'Bijstandsgezin', 1, 1, 0, 2, 1, CURRENT_TIMESTAMP(6)),
(6, 'VanderBergGezin', 'G0006', 'AlleenGaande', 1, 0, 0, 1, 1, CURRENT_TIMESTAMP(6));

-- Leveranciers
INSERT INTO leveranciers (id, naam, contact_persoon, leverancier_nummer, leverancier_type, is_actief, datum_aangemaakt) VALUES
(1, 'Albert Heijn', 'Ruud ter Weijden', 'L0001', 'Bedrijf', 1, CURRENT_TIMESTAMP(6)),
(2, 'Albertus Kerk', 'Leo Pastoor', 'L0002', 'Instelling', 1, CURRENT_TIMESTAMP(6)),
(3, 'Gemeente Utrecht', 'Mohammed Yazidi', 'L0003', 'Overheid', 1, CURRENT_TIMESTAMP(6)),
(4, 'Boerderij Meerhoven', 'Bertus van Driel', 'L0004', 'Particulier', 1, CURRENT_TIMESTAMP(6)),
(5, 'Jan van der Heijden', 'Jan van der Heijden', 'L0005', 'Donor', 1, CURRENT_TIMESTAMP(6)),
(6, 'Vomar', 'Jaco Pastorius', 'L0006', 'Bedrijf', 1, CURRENT_TIMESTAMP(6)),
(7, 'DekaMarkt', 'Sil den Dollaard', 'L0007', 'Bedrijf', 1, CURRENT_TIMESTAMP(6)),
(8, 'Gemeente Vught', 'Jan Blokker', 'L0008', 'Overheid', 1, CURRENT_TIMESTAMP(6));

-- Magazijn
INSERT INTO magazijns (id, ontvangstdatum, uitleveringsdatum, verpakkings_eenheid, aantal, is_actief, datum_aangemaakt) VALUES
(1, '2024-05-12', NULL, '5 kg', 20, 1, CURRENT_TIMESTAMP(6)),
(2, '2024-05-26', NULL, '2.5 kg', 40, 1, CURRENT_TIMESTAMP(6)),
(3, '2024-04-02', NULL, '1 kg', 30, 1, CURRENT_TIMESTAMP(6)),
(4, '2024-05-16', NULL, '1.5 kg', 25, 1, CURRENT_TIMESTAMP(6)),
(5, '2024-05-23', NULL, '4 stuks', 75, 1, CURRENT_TIMESTAMP(6)),
(6, '2024-03-12', NULL, '1 kg/tros', 60, 1, CURRENT_TIMESTAMP(6)),
(7, '2024-03-19', NULL, '2 kg/tros', 200, 1, CURRENT_TIMESTAMP(6)),
(8, '2024-06-19', NULL, '200 g', 45, 1, CURRENT_TIMESTAMP(6)),
(9, '2024-07-23', NULL, '100 g', 60, 1, CURRENT_TIMESTAMP(6)),
(10, '2024-07-23', NULL, '1 liter', 120, 1, CURRENT_TIMESTAMP(6)),
(11, '2024-06-02', NULL, '250 g', 80, 1, CURRENT_TIMESTAMP(6)),
(12, '2024-01-04', NULL, '6 stuks', 120, 1, CURRENT_TIMESTAMP(6)),
(13, '2024-04-07', NULL, '800 g', 220, 1, CURRENT_TIMESTAMP(6)),
(14, '2024-04-04', NULL, '1 stuk', 130, 1, CURRENT_TIMESTAMP(6)),
(15, '2024-04-28', NULL, '150 ml', 72, 1, CURRENT_TIMESTAMP(6)),
(16, '2024-04-19', NULL, '1 l', 12, 1, CURRENT_TIMESTAMP(6)),
(17, '2024-04-23', NULL, '250 g', 300, 1, CURRENT_TIMESTAMP(6)),
(18, '2024-03-02', NULL, '25 zakjes', 280, 1, CURRENT_TIMESTAMP(6)),
(19, '2024-04-16', NULL, '500 g', 330, 1, CURRENT_TIMESTAMP(6)),
(20, '2024-04-25', NULL, '1 kg', 34, 1, CURRENT_TIMESTAMP(6)),
(21, '2024-04-13', NULL, '50 g', 23, 1, CURRENT_TIMESTAMP(6)),
(22, '2024-04-23', NULL, '1 l', 46, 1, CURRENT_TIMESTAMP(6)),
(23, '2024-04-21', NULL, '250 ml', 98, 1, CURRENT_TIMESTAMP(6)),
(24, '2024-04-30', NULL, '1 potje', 56, 1, CURRENT_TIMESTAMP(6)),
(25, '2024-04-27', NULL, '1 l', 210, 1, CURRENT_TIMESTAMP(6)),
(26, '2024-04-01', NULL, '4 stuks', 24, 1, CURRENT_TIMESTAMP(6)),
(27, '2024-04-07', NULL, '300 g', 87, 1, CURRENT_TIMESTAMP(6)),
(28, '2024-04-22', NULL, '200 g', 230, 1, CURRENT_TIMESTAMP(6)),
(29, '2024-04-21', NULL, '80 g', 30, 1, CURRENT_TIMESTAMP(6));

-- Personen
INSERT INTO persoons (id, gezin_id, voornaam, tussenvoegsel, achternaam, geboortedatum, type_persoon, is_vertegenwoordiger, is_actief, datum_aangemaakt) VALUES
(1, NULL, 'Hans', 'van', 'Leeuwen', '1958-02-12', 'Manager', 0, 1, CURRENT_TIMESTAMP(6)),
(2, NULL, 'Jan', 'van der', 'Sluijs', '1993-04-30', 'Medewerker', 0, 1, CURRENT_TIMESTAMP(6)),
(3, NULL, 'Herman', 'den', 'Duiker', '1989-08-30', 'Vrijwilliger', 0, 1, CURRENT_TIMESTAMP(6)),
(4, 1, 'Johan', 'van', 'Zevenhuizen', '1990-05-20', 'Klant', 1, 1, CURRENT_TIMESTAMP(6)),
(5, 1, 'Sarah', 'den', 'Dolder', '1985-03-23', 'Klant', 0, 1, CURRENT_TIMESTAMP(6)),
(6, 1, 'Theo', 'van', 'Zevenhuizen', '2015-03-08', 'Klant', 0, 1, CURRENT_TIMESTAMP(6)),
(7, 1, 'Jantien', 'van', 'Zevenhuizen', '2016-09-20', 'Klant', 0, 1, CURRENT_TIMESTAMP(6)),
(8, 2, 'Arjan', NULL, 'Bergkamp', '1968-07-12', 'Klant', 1, 1, CURRENT_TIMESTAMP(6)),
(9, 2, 'Janneke', NULL, 'Sanders', '1969-05-11', 'Klant', 0, 1, CURRENT_TIMESTAMP(6)),
(10, 2, 'Stein', NULL, 'Bergkamp', '2009-02-02', 'Klant', 0, 1, CURRENT_TIMESTAMP(6)),
(11, 2, 'Judith', NULL, 'Bergkamp', '2022-02-05', 'Klant', 0, 1, CURRENT_TIMESTAMP(6)),
(12, 3, 'Mazin', 'van', 'Vliet', '1968-08-18', 'Klant', 0, 1, CURRENT_TIMESTAMP(6)),
(13, 3, 'Selma', 'van de', 'Heuvel', '1965-09-04', 'Klant', 1, 1, CURRENT_TIMESTAMP(6)),
(14, 4, 'Eva', NULL, 'Scherder', '2000-04-07', 'Klant', 1, 1, CURRENT_TIMESTAMP(6)),
(15, 4, 'Felicia', NULL, 'Scherder', '2021-11-29', 'Klant', 0, 1, CURRENT_TIMESTAMP(6)),
(16, 4, 'Devin', NULL, 'Scherder', '2024-03-01', 'Klant', 0, 1, CURRENT_TIMESTAMP(6)),
(17, 5, 'Frieda', 'de', 'Jong', '1980-09-04', 'Klant', 1, 1, CURRENT_TIMESTAMP(6)),
(18, 5, 'Simeon', 'de', 'Jong', '2018-05-23', 'Klant', 0, 1, CURRENT_TIMESTAMP(6)),
(19, 6, 'Hanna', 'van der', 'Berg', '1999-09-09', 'Klant', 1, 1, CURRENT_TIMESTAMP(6));

-- Gebruikers
INSERT INTO gebruikers (id, persoon_id, inlog_naam, gebruikersnaam, wachtwoord, is_ingelogd, ingelogd, uitgelogd, is_actief, datum_aangemaakt) VALUES
(1, 1, 'Hans', 'hans@maaskantje.nl', '$2y$10$296RMzqzZqWENu9vyh6axed0DkfsuYkbvoI/AXVowCp/DL6zKiF0i', 1, '2024-03-13 17:03:06', NULL, 1, CURRENT_TIMESTAMP(6)),
(2, 2, 'Jan', 'jan@maaskantje.nl', '$2y$10$296RMzqzZqWENu9vyh6axed0DkfsuYkbvoI/AXVowCp/DL3zKiF6i', 0, '2024-03-13 15:13:23', '2024-03-13 15:23:46', 1, CURRENT_TIMESTAMP(6)),
(3, 3, 'Herman', 'herman@maaskantje.nl', '$2y$10$296RMzqzZqWENu9vyh6axed0DkfsuYkbvoI/AXVuwCp/DL9zKiF2i', 1, '2024-06-20 12:05:20', NULL, 1, CURRENT_TIMESTAMP(6));

-- Producten
INSERT INTO products (id, categorie_id, naam, soort_allergie, barcode, houdbaarheidsdatum, omschrijving, status, is_actief, datum_aangemaakt) VALUES
(1, 1, 'Aardappel', NULL, '8719587321239', '2024-07-12', 'Kruimige aardappel', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(2, 1, 'Aardappel', NULL, '8719587321239', '2024-07-26', 'Kruimige aardappel', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(3, 1, 'Ui', NULL, '8719437321335', '2024-09-02', 'Gele ui', 'NietOpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(4, 1, 'Appel', NULL, '8719486321332', '2024-08-16', 'Granny Smith', 'NietLeverbaar', 1, CURRENT_TIMESTAMP(6)),
(5, 1, 'Appel', NULL, '8719486321332', '2024-09-23', 'Granny Smith', 'NietLeverbaar', 1, CURRENT_TIMESTAMP(6)),
(6, 1, 'Banaan', 'Banaan', '8719484321336', '2024-07-12', 'Biologische Banaan', 'OverHoudbaarheidsDatum', 1, CURRENT_TIMESTAMP(6)),
(7, 1, 'Banaan', 'Banaan', '8719484321336', '2024-07-19', 'Biologische Banaan', 'OverHoudbaarheidsDatum', 1, CURRENT_TIMESTAMP(6)),
(8, 2, 'Kaas', 'Lactose', '8719487421338', '2024-09-19', 'Jonge Kaas', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(9, 2, 'Rosbief', NULL, '8719487421331', '2024-07-23', 'Rundvlees', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(10, 3, 'Melk', 'Lactose', '8719447321332', '2024-07-23', 'Halfvolle melk', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(11, 3, 'Margarine', NULL, '8719486321336', '2024-08-02', 'Plantaardige boter', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(12, 3, 'Ei', 'Eier', '8719487421334', '2024-08-04', 'Scharrelei', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(13, 4, 'Brood', 'Gluten', '8719487721337', '2024-07-07', 'Volkoren brood', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(14, 4, 'Gevulde Koek', 'Amandel', '8719483321333', '2024-09-04', 'Banketbakkers kwaliteit', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(15, 5, 'Fristi', 'Lactose', '8719487121331', '2024-10-28', 'Frisdrank', 'NietOpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(16, 5, 'Appelsap', NULL, '8719487521335', '2024-10-19', '100% vruchtensap', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(17, 5, 'Koffie', 'Caffeïne', '8719487381338', '2024-10-23', 'Arabica koffie', 'OverHoudbaarheidsDatum', 1, CURRENT_TIMESTAMP(6)),
(18, 5, 'Thee', 'Theïne', '8719487329339', '2024-09-02', 'Ceylon thee', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(19, 6, 'Pasta', 'Gluten', '8719487321334', '2024-12-16', 'Macaroni', 'NietLeverbaar', 1, CURRENT_TIMESTAMP(6)),
(20, 6, 'Rijst', NULL, '8719487331332', '2024-12-25', 'Basmati Rijst', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(21, 6, 'Knorr Nasi Mix', NULL, '871948735135', '2024-12-13', 'Nasi kruiden', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(22, 7, 'Tomatensoep', NULL, '8719487371337', '2024-12-23', 'Romige tomatensoep', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(23, 7, 'Tomatensaus', NULL, '8719487341334', '2024-12-21', 'Pizza saus', 'NietOpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(24, 7, 'Peterselie', NULL, '8719487321636', '2024-07-31', 'Verse kruidenpot', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(25, 8, 'Olie', NULL, '8719487327337', '2024-12-27', 'Olijfolie', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(26, 8, 'Mars', NULL, '8719487324334', '2024-12-11', 'Snoep', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(27, 8, 'Biscuit', NULL, '8719487311331', '2024-08-07', 'San Francisco biscuit', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(28, 8, 'Paprika Chips', NULL, '87194873218398', '2024-12-22', 'Ribbelchips paprika', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(29, 8, 'Chocolade reep', 'Cacoa', '8719487321533', '2024-11-21', 'Tony Chocolonely', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6));

-- Voedselpakketten
INSERT INTO voedselpakkets (id, gezin_id, pakket_nummer, datum_samenstelling, datum_uitgifte, status, is_actief, datum_aangemaakt) VALUES
(1, 1, '1', '2024-04-06', '2024-04-07', 'Uitgereikt', 1, CURRENT_TIMESTAMP(6)),
(2, 1, '2', '2024-04-13', NULL, 'NietUitgereikt', 1, CURRENT_TIMESTAMP(6)),
(3, 1, '3', '2024-04-20', NULL, 'NietMeerIngeschreven', 1, CURRENT_TIMESTAMP(6)),
(4, 2, '4', '2024-04-06', '2024-04-07', 'Uitgereikt', 1, CURRENT_TIMESTAMP(6)),
(5, 2, '5', '2024-04-13', '2024-04-14', 'Uitgereikt', 1, CURRENT_TIMESTAMP(6)),
(6, 2, '6', '2024-04-20', NULL, 'NietUitgereikt', 1, CURRENT_TIMESTAMP(6));

-- Allergie per persoon (volgens specificatie - ALLEEN SARAH MAG PINDA ALLERGIE HEBBEN)
INSERT INTO allergie_per_persoons (id, persoon_id, allergie_id, ernst, is_actief, datum_aangemaakt) VALUES
(1, 4, 1, 'middel', 1, CURRENT_TIMESTAMP(6)),  -- Johan van Zevenhuizen - Gluten
(2, 5, 2, 'hoog', 1, CURRENT_TIMESTAMP(6)),    -- Sarah den Dolder - Pindas (ENIGE MET PINDAS!)
(3, 6, 3, 'hoog', 1, CURRENT_TIMESTAMP(6)),    -- Theo van Zevenhuizen - Schaaldieren
(4, 7, 4, 'middel', 1, CURRENT_TIMESTAMP(6)),  -- Jantien van Zevenhuizen - Hazelnoten
(5, 8, 3, 'hoog', 1, CURRENT_TIMESTAMP(6)),    -- Arjan Bergkamp - Schaaldieren
(6, 9, 1, 'middel', 1, CURRENT_TIMESTAMP(6)),  -- Janneke Sanders - Gluten (WAS Pindas)
(7, 10, 5, 'laag', 1, CURRENT_TIMESTAMP(6)),   -- Stein Bergkamp - Lactose
(8, 12, 3, 'hoog', 1, CURRENT_TIMESTAMP(6)),   -- Mazin van Vliet - Schaaldieren (WAS Pindas)
(9, 13, 4, 'middel', 1, CURRENT_TIMESTAMP(6)), -- Selma van de Heuvel - Hazelnoten
(10, 14, 1, 'hoog', 1, CURRENT_TIMESTAMP(6)),  -- Eva Scherder - Gluten
(11, 15, 3, 'middel', 1, CURRENT_TIMESTAMP(6)), -- Felicia Scherder - Schaaldieren
(12, 16, 5, 'laag', 1, CURRENT_TIMESTAMP(6)),  -- Devin Scherder - Lactose
(13, 17, 1, 'hoog', 1, CURRENT_TIMESTAMP(6)),  -- Frieda de Jong - Gluten (WAS Pindas)
(14, 17, 4, 'middel', 1, CURRENT_TIMESTAMP(6)), -- Frieda de Jong - Hazelnoten (WAS tweede Pindas)
(15, 18, 4, 'laag', 1, CURRENT_TIMESTAMP(6)),  -- Simeon de Jong - Hazelnoten
(16, 19, 4, 'middel', 1, CURRENT_TIMESTAMP(6)); -- Hanna van der Berg - Hazelnoten

-- Rol per gebruiker
INSERT INTO rol_per_gebruikers (id, gebruiker_id, rol_id, is_actief, datum_aangemaakt) VALUES
(1, 1, 1, 1, CURRENT_TIMESTAMP(6)),  -- Hans - Manager
(2, 2, 2, 1, CURRENT_TIMESTAMP(6)),  -- Jan - Medewerker
(3, 3, 3, 1, CURRENT_TIMESTAMP(6));  -- Herman - Vrijwilliger

-- Eetwens per gezin
INSERT INTO eetwens_per_gezins (id, gezin_id, eetwens_id, is_actief, datum_aangemaakt) VALUES
(1, 1, 2, 1, CURRENT_TIMESTAMP(6)),  -- ZevenhuizenGezin - Veganistisch
(2, 2, 4, 1, CURRENT_TIMESTAMP(6)),  -- BergkampGezin - Omnivoor
(3, 3, 4, 1, CURRENT_TIMESTAMP(6)),  -- HeuvelGezin - Omnivoor
(4, 4, 3, 1, CURRENT_TIMESTAMP(6)),  -- ScherderGezin - Vegetarisch
(5, 5, 2, 1, CURRENT_TIMESTAMP(6));  -- DeJongGezin - Veganistisch

-- Contact per leverancier
INSERT INTO contact_per_leveranciers (id, leverancier_id, contact_id, is_actief, datum_aangemaakt) VALUES
(1, 1, 7, 1, CURRENT_TIMESTAMP(6)),   -- Albert Heijn - Ruud ter Weijden
(2, 2, 8, 1, CURRENT_TIMESTAMP(6)),   -- Albertus Kerk - Leo Pastoor
(3, 3, 9, 1, CURRENT_TIMESTAMP(6)),   -- Gemeente Utrecht - Mohammed Yazidi
(4, 4, 10, 1, CURRENT_TIMESTAMP(6)),  -- Boerderij Meerhoven - Bertus van Driel
(5, 6, 11, 1, CURRENT_TIMESTAMP(6)),  -- Vomar - Jaco Pastorius
(6, 7, 12, 1, CURRENT_TIMESTAMP(6)),  -- DekaMarkt - Sil den Dollaard
(7, 8, 13, 1, CURRENT_TIMESTAMP(6));  -- Gemeente Vught - Jan Blokker

-- Contact per gezin
INSERT INTO contact_per_gezins (id, gezin_id, contact_id, is_actief, datum_aangemaakt) VALUES
(1, 1, 1, 1, CURRENT_TIMESTAMP(6)),  -- ZevenhuizenGezin
(2, 2, 2, 1, CURRENT_TIMESTAMP(6)),  -- BergkampGezin
(3, 3, 3, 1, CURRENT_TIMESTAMP(6)),  -- HeuvelGezin
(4, 4, 4, 1, CURRENT_TIMESTAMP(6)),  -- ScherderGezin
(5, 5, 5, 1, CURRENT_TIMESTAMP(6)),  -- DeJongGezin
(6, 6, 6, 1, CURRENT_TIMESTAMP(6));  -- VanderBergGezin

-- Product per voedselpakket
INSERT INTO product_per_voedselpakkets (id, voedselpakket_id, product_id, aantal_product_eenheden, is_actief, datum_aangemaakt) VALUES
(1, 1, 7, 1, 1, CURRENT_TIMESTAMP(6)),   -- Pakket 1 - Banaan
(2, 1, 8, 2, 1, CURRENT_TIMESTAMP(6)),   -- Pakket 1 - Kaas
(3, 1, 9, 1, 1, CURRENT_TIMESTAMP(6)),   -- Pakket 1 - Rosbief
(4, 2, 12, 1, 1, CURRENT_TIMESTAMP(6)),  -- Pakket 2 - Ei
(5, 2, 13, 2, 1, CURRENT_TIMESTAMP(6)),  -- Pakket 2 - Brood
(6, 2, 14, 1, 1, CURRENT_TIMESTAMP(6)),  -- Pakket 2 - Gevulde Koek
(7, 3, 3, 1, 1, CURRENT_TIMESTAMP(6)),   -- Pakket 3 - Ui
(8, 3, 4, 1, 1, CURRENT_TIMESTAMP(6)),   -- Pakket 3 - Appel
(9, 4, 20, 1, 1, CURRENT_TIMESTAMP(6)),  -- Pakket 4 - Rijst
(10, 4, 19, 1, 1, CURRENT_TIMESTAMP(6)), -- Pakket 4 - Pasta
(11, 4, 21, 1, 1, CURRENT_TIMESTAMP(6)), -- Pakket 4 - Knorr Nasi Mix
(12, 5, 24, 1, 1, CURRENT_TIMESTAMP(6)), -- Pakket 5 - Peterselie
(13, 5, 25, 1, 1, CURRENT_TIMESTAMP(6)), -- Pakket 5 - Olie
(14, 5, 26, 1, 1, CURRENT_TIMESTAMP(6)), -- Pakket 5 - Mars
(15, 6, 26, 1, 1, CURRENT_TIMESTAMP(6)); -- Pakket 6 - Mars

-- Product per leverancier
INSERT INTO product_per_leveranciers (id, leverancier_id, product_id, datum_aangeleverd, datum_eerst_volgende_levering, is_actief, datum_aangemaakt) VALUES
(1, 4, 1, '2024-04-12', '2024-05-12', 1, CURRENT_TIMESTAMP(6)),
(2, 4, 2, '2024-03-02', '2024-04-02', 1, CURRENT_TIMESTAMP(6)),
(3, 2, 3, '2024-07-16', '2024-08-16', 1, CURRENT_TIMESTAMP(6)),
(4, 1, 4, '2024-02-12', '2024-03-12', 1, CURRENT_TIMESTAMP(6)),
(5, 4, 5, '2024-05-19', '2024-06-19', 1, CURRENT_TIMESTAMP(6)),
(6, 1, 6, '2024-06-23', '2024-07-23', 1, CURRENT_TIMESTAMP(6)),
(7, 4, 7, '2024-06-20', '2024-07-20', 1, CURRENT_TIMESTAMP(6)),
(8, 4, 8, '2024-05-02', '2024-06-02', 1, CURRENT_TIMESTAMP(6)),
(9, 4, 9, '2022-12-04', '2024-01-04', 1, CURRENT_TIMESTAMP(6)),
(10, 3, 10, '2024-03-07', '2024-04-07', 1, CURRENT_TIMESTAMP(6)),
(11, 3, 11, '2024-02-04', '2024-03-04', 1, CURRENT_TIMESTAMP(6)),
(12, 3, 12, '2024-02-28', '2024-03-28', 1, CURRENT_TIMESTAMP(6)),
(13, 3, 13, '2024-03-19', '2024-04-19', 1, CURRENT_TIMESTAMP(6)),
(14, 2, 14, '2024-03-23', '2024-04-23', 1, CURRENT_TIMESTAMP(6)),
(15, 2, 15, '2024-02-02', '2024-03-02', 1, CURRENT_TIMESTAMP(6)),
(16, 1, 16, '2024-02-16', '2024-03-16', 1, CURRENT_TIMESTAMP(6)),
(17, 1, 17, '2024-03-25', '2024-04-25', 1, CURRENT_TIMESTAMP(6)),
(18, 1, 18, '2024-03-13', '2024-04-13', 1, CURRENT_TIMESTAMP(6)),
(19, 1, 19, '2024-03-23', '2024-04-23', 1, CURRENT_TIMESTAMP(6)),
(20, 4, 20, '2024-02-21', '2024-03-21', 1, CURRENT_TIMESTAMP(6)),
(21, 2, 21, '2024-03-31', '2024-04-30', 1, CURRENT_TIMESTAMP(6)),
(22, 1, 22, '2024-03-27', '2024-04-27', 1, CURRENT_TIMESTAMP(6)),
(23, 3, 23, '2024-04-11', '2024-04-18', 1, CURRENT_TIMESTAMP(6)),
(24, 3, 24, '2024-04-07', '2024-04-14', 1, CURRENT_TIMESTAMP(6)),
(25, 1, 25, '2024-05-07', '2024-05-14', 1, CURRENT_TIMESTAMP(6)),
(26, 2, 26, '2024-05-05', '2024-05-12', 1, CURRENT_TIMESTAMP(6));

-- Product per magazijn
INSERT INTO product_per_magazijns (id, product_id, magazijn_id, locatie, is_actief, datum_aangemaakt) VALUES
(1, 1, 1, 'Berlicum', 1, CURRENT_TIMESTAMP(6)),
(2, 2, 2, 'Rosmalen', 1, CURRENT_TIMESTAMP(6)),
(3, 3, 3, 'Berlicum', 1, CURRENT_TIMESTAMP(6)),
(4, 4, 4, 'Berlicum', 1, CURRENT_TIMESTAMP(6)),
(5, 5, 5, 'Rosmalen', 1, CURRENT_TIMESTAMP(6)),
(6, 6, 6, 'Berlicum', 1, CURRENT_TIMESTAMP(6)),
(7, 7, 7, 'Rosmalen', 1, CURRENT_TIMESTAMP(6)),
(8, 8, 8, 'Sint-MichelsGestel', 1, CURRENT_TIMESTAMP(6)),
(9, 9, 9, 'Sint-MichelsGestel', 1, CURRENT_TIMESTAMP(6)),
(10, 10, 10, 'Middelrode', 1, CURRENT_TIMESTAMP(6)),
(11, 11, 11, 'Middelrode', 1, CURRENT_TIMESTAMP(6)),
(12, 12, 12, 'Middelrode', 1, CURRENT_TIMESTAMP(6)),
(13, 13, 13, 'Schijndel', 1, CURRENT_TIMESTAMP(6)),
(14, 14, 14, 'Schijndel', 1, CURRENT_TIMESTAMP(6)),
(15, 15, 15, 'Gemonde', 1, CURRENT_TIMESTAMP(6)),
(16, 16, 16, 'Gemonde', 1, CURRENT_TIMESTAMP(6)),
(17, 17, 17, 'Gemonde', 1, CURRENT_TIMESTAMP(6)),
(18, 18, 18, 'Gemonde', 1, CURRENT_TIMESTAMP(6)),
(19, 19, 19, 'Den Bosch', 1, CURRENT_TIMESTAMP(6)),
(20, 20, 20, 'Den Bosch', 1, CURRENT_TIMESTAMP(6)),
(21, 21, 21, 'Den Bosch', 1, CURRENT_TIMESTAMP(6)),
(22, 22, 22, 'Heeswijk Dinther', 1, CURRENT_TIMESTAMP(6)),
(23, 23, 23, 'Heeswijk Dinther', 1, CURRENT_TIMESTAMP(6)),
(24, 24, 24, 'Heeswijk Dinther', 1, CURRENT_TIMESTAMP(6)),
(25, 25, 25, 'Vught', 1, CURRENT_TIMESTAMP(6)),
(26, 26, 26, 'Vught', 1, CURRENT_TIMESTAMP(6)),
(27, 27, 27, 'Vught', 1, CURRENT_TIMESTAMP(6)),
(28, 28, 28, 'Vught', 1, CURRENT_TIMESTAMP(6)),
(29, 29, 29, 'Vught', 1, CURRENT_TIMESTAMP(6));

-- Laravel users tabel voor authenticatie
INSERT INTO users (id, name, email, password, created_at, updated_at) VALUES
(1, 'Test User', 'test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW());

SELECT 'Database voedselbankdag_03 succesvol aangemaakt met alle tabellen, procedures en data!' AS Message;
SELECT 'Alle leverancier, product, magazijn en allergie data geladen!' AS LeverancierData;
SELECT 'Totaal aantal tabellen:' AS Info, COUNT(*) AS AantalTabellen FROM information_schema.tables WHERE table_schema = 'voedselbankdag_03';
