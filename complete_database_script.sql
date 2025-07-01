-- =============================================================================
-- VOEDSELBANK MAASKANTJE - COMPLETE DATABASE SCRIPT
-- =============================================================================
-- Bevat: Database structuur + Stored Procedures + Testdata + Indexes
-- Voor: Allergie module en gerelateerde functionaliteiten
-- Datum: 1 Juli 2025
-- =============================================================================

-- Disable foreign key checks tijdens setup
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';

-- =============================================================================
-- SECTION 1: DROP EXISTING TABLES (in correct order)
-- =============================================================================

DROP TABLE IF EXISTS allergie_per_persoon;
DROP TABLE IF EXISTS rol_per_gebruiker;
DROP TABLE IF EXISTS eetwens_per_gezin;
DROP TABLE IF EXISTS contact_per_leverancier;
DROP TABLE IF EXISTS contact_per_gezin;
DROP TABLE IF EXISTS product_per_voedselpakket;
DROP TABLE IF EXISTS product_per_leverancier;
DROP TABLE IF EXISTS product_per_magazijn;
DROP TABLE IF EXISTS voedselpakket;
DROP TABLE IF EXISTS product;
DROP TABLE IF EXISTS gebruiker;
DROP TABLE IF EXISTS persoon;
DROP TABLE IF EXISTS gezin;
DROP TABLE IF EXISTS allergie;
DROP TABLE IF EXISTS rol;
DROP TABLE IF EXISTS categorie;
DROP TABLE IF EXISTS contact;
DROP TABLE IF EXISTS eetwens;
DROP TABLE IF EXISTS leverancier;
DROP TABLE IF EXISTS magazijn;

-- Drop stored procedures if they exist
DROP PROCEDURE IF EXISTS GetGezinnenMetAllergien;
DROP PROCEDURE IF EXISTS FilterGezinnenByAllergie;
DROP PROCEDURE IF EXISTS GetGezinAllergieDetails;
DROP PROCEDURE IF EXISTS UpdatePersoonAllergie;
DROP PROCEDURE IF EXISTS GetAllergieStatistieken;
DROP PROCEDURE IF EXISTS GetPopulaireAllergien;

-- =============================================================================
-- SECTION 2: CREATE BASE TABLES
-- =============================================================================

-- Gezin tabel
CREATE TABLE gezin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    naam VARCHAR(255) NOT NULL,
    code VARCHAR(50) UNIQUE NOT NULL,
    adres VARCHAR(255),
    postcode VARCHAR(10),
    woonplaats VARCHAR(100),
    telefoon VARCHAR(20),
    email VARCHAR(255),
    aantal_personen INT DEFAULT 1,
    is_actief BOOLEAN DEFAULT TRUE,
    opmerking TEXT,
    inschrijfdatum DATE DEFAULT (CURDATE()),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_gezin_naam (naam),
    INDEX idx_gezin_postcode (postcode),
    INDEX idx_gezin_actief (is_actief)
);

-- Allergie tabel
CREATE TABLE allergie (
    id INT PRIMARY KEY AUTO_INCREMENT,
    naam VARCHAR(255) NOT NULL UNIQUE,
    beschrijving TEXT,
    omschrijving TEXT,
    ernst_niveau ENUM('laag', 'middel', 'hoog', 'levensgevaarlijk') DEFAULT 'middel',
    anafylactisch_risico ENUM('geen', 'laag', 'redelijk_hoog', 'hoog') DEFAULT 'laag',
    is_actief BOOLEAN DEFAULT TRUE,
    opmerking TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_allergie_naam (naam),
    INDEX idx_allergie_ernst (ernst_niveau),
    INDEX idx_allergie_risico (anafylactisch_risico),
    INDEX idx_allergie_actief (is_actief)
);

-- Persoon tabel
CREATE TABLE persoon (
    id INT PRIMARY KEY AUTO_INCREMENT,
    gezin_id INT NOT NULL,
    voornaam VARCHAR(100) NOT NULL,
    achternaam VARCHAR(100) NOT NULL,
    geboortedatum DATE NOT NULL,
    geslacht ENUM('man', 'vrouw', 'anders') NOT NULL,
    is_vertegenwoordiger BOOLEAN DEFAULT FALSE,
    is_actief BOOLEAN DEFAULT TRUE,
    opmerking TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gezin_id) REFERENCES gezin(id) ON DELETE CASCADE,
    INDEX idx_persoon_gezin (gezin_id),
    INDEX idx_persoon_naam (voornaam, achternaam),
    INDEX idx_persoon_geboortedatum (geboortedatum),
    INDEX idx_persoon_vertegenwoordiger (is_vertegenwoordiger),
    INDEX idx_persoon_actief (is_actief)
);

-- Rol tabel
CREATE TABLE rol (
    id INT PRIMARY KEY AUTO_INCREMENT,
    naam VARCHAR(100) NOT NULL UNIQUE,
    beschrijving TEXT,
    is_actief BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Gebruiker tabel  
CREATE TABLE gebruiker (
    id INT PRIMARY KEY AUTO_INCREMENT,
    naam VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    is_actief BOOLEAN DEFAULT TRUE,
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_gebruiker_email (email),
    INDEX idx_gebruiker_actief (is_actief)
);

-- Leverancier tabel
CREATE TABLE leverancier (
    id INT PRIMARY KEY AUTO_INCREMENT,
    naam VARCHAR(255) NOT NULL,
    adres VARCHAR(255),
    postcode VARCHAR(10),
    woonplaats VARCHAR(100),
    telefoon VARCHAR(20),
    email VARCHAR(255),
    is_actief BOOLEAN DEFAULT TRUE,
    opmerking TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_leverancier_naam (naam),
    INDEX idx_leverancier_actief (is_actief)
);

-- Magazijn tabel
CREATE TABLE magazijn (
    id INT PRIMARY KEY AUTO_INCREMENT,
    naam VARCHAR(255) NOT NULL,
    adres VARCHAR(255),
    is_actief BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Categorie tabel
CREATE TABLE categorie (
    id INT PRIMARY KEY AUTO_INCREMENT,
    naam VARCHAR(255) NOT NULL UNIQUE,
    beschrijving TEXT,
    is_actief BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Product tabel
CREATE TABLE product (
    id INT PRIMARY KEY AUTO_INCREMENT,
    naam VARCHAR(255) NOT NULL,
    beschrijving TEXT,
    categorie_id INT,
    is_actief BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categorie_id) REFERENCES categorie(id),
    INDEX idx_product_naam (naam),
    INDEX idx_product_categorie (categorie_id),
    INDEX idx_product_actief (is_actief)
);

-- Contact tabel
CREATE TABLE contact (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type ENUM('telefoon', 'email', 'adres') NOT NULL,
    waarde VARCHAR(255) NOT NULL,
    is_primair BOOLEAN DEFAULT FALSE,
    is_actief BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Eetwens tabel
CREATE TABLE eetwens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    naam VARCHAR(255) NOT NULL UNIQUE,
    beschrijving TEXT,
    is_actief BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Voedselpakket tabel
CREATE TABLE voedselpakket (
    id INT PRIMARY KEY AUTO_INCREMENT,
    gezin_id INT NOT NULL,
    uitgiftedatum DATE NOT NULL,
    status ENUM('voorbereid', 'uitgedeeld', 'geannuleerd') DEFAULT 'voorbereid',
    opmerking TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gezin_id) REFERENCES gezin(id),
    INDEX idx_pakket_gezin (gezin_id),
    INDEX idx_pakket_datum (uitgiftedatum),
    INDEX idx_pakket_status (status)
);

-- =============================================================================
-- SECTION 3: CREATE JUNCTION/RELATIONSHIP TABLES
-- =============================================================================

-- Allergie per persoon (many-to-many met extra attributen)
CREATE TABLE allergie_per_persoon (
    id INT PRIMARY KEY AUTO_INCREMENT,
    persoon_id INT NOT NULL,
    allergie_id INT NOT NULL,
    ernst ENUM('laag', 'middel', 'hoog', 'levensgevaarlijk') DEFAULT 'middel',
    opmerking TEXT,
    datum_vastgesteld DATE DEFAULT (CURDATE()),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (persoon_id) REFERENCES persoon(id) ON DELETE CASCADE,
    FOREIGN KEY (allergie_id) REFERENCES allergie(id) ON DELETE CASCADE,
    UNIQUE KEY unique_persoon_allergie (persoon_id, allergie_id),
    INDEX idx_app_persoon (persoon_id),
    INDEX idx_app_allergie (allergie_id),
    INDEX idx_app_ernst (ernst),
    INDEX idx_app_datum (datum_vastgesteld)
);

-- Rol per gebruiker
CREATE TABLE rol_per_gebruiker (
    id INT PRIMARY KEY AUTO_INCREMENT,
    gebruiker_id INT NOT NULL,
    rol_id INT NOT NULL,
    toegewezen_op TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_actief BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (gebruiker_id) REFERENCES gebruiker(id) ON DELETE CASCADE,
    FOREIGN KEY (rol_id) REFERENCES rol(id) ON DELETE CASCADE,
    UNIQUE KEY unique_gebruiker_rol (gebruiker_id, rol_id)
);

-- Eetwens per gezin
CREATE TABLE eetwens_per_gezin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    gezin_id INT NOT NULL,
    eetwens_id INT NOT NULL,
    opmerking TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (gezin_id) REFERENCES gezin(id) ON DELETE CASCADE,
    FOREIGN KEY (eetwens_id) REFERENCES eetwens(id) ON DELETE CASCADE,
    UNIQUE KEY unique_gezin_eetwens (gezin_id, eetwens_id)
);

-- Contact per leverancier
CREATE TABLE contact_per_leverancier (
    id INT PRIMARY KEY AUTO_INCREMENT,
    leverancier_id INT NOT NULL,
    contact_id INT NOT NULL,
    FOREIGN KEY (leverancier_id) REFERENCES leverancier(id) ON DELETE CASCADE,
    FOREIGN KEY (contact_id) REFERENCES contact(id) ON DELETE CASCADE
);

-- Contact per gezin
CREATE TABLE contact_per_gezin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    gezin_id INT NOT NULL,
    contact_id INT NOT NULL,
    FOREIGN KEY (gezin_id) REFERENCES gezin(id) ON DELETE CASCADE,
    FOREIGN KEY (contact_id) REFERENCES contact(id) ON DELETE CASCADE
);

-- Product per voedselpakket
CREATE TABLE product_per_voedselpakket (
    id INT PRIMARY KEY AUTO_INCREMENT,
    voedselpakket_id INT NOT NULL,
    product_id INT NOT NULL,
    aantal INT DEFAULT 1,
    FOREIGN KEY (voedselpakket_id) REFERENCES voedselpakket(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE
);

-- Product per leverancier
CREATE TABLE product_per_leverancier (
    id INT PRIMARY KEY AUTO_INCREMENT,
    leverancier_id INT NOT NULL,
    product_id INT NOT NULL,
    prijs DECIMAL(8,2),
    is_beschikbaar BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (leverancier_id) REFERENCES leverancier(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE
);

-- Product per magazijn
CREATE TABLE product_per_magazijn (
    id INT PRIMARY KEY AUTO_INCREMENT,
    magazijn_id INT NOT NULL,
    product_id INT NOT NULL,
    voorraad INT DEFAULT 0,
    minimum_voorraad INT DEFAULT 10,
    houdbaarheidsdatum DATE,
    FOREIGN KEY (magazijn_id) REFERENCES magazijn(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE
);

-- =============================================================================
-- SECTION 4: INSERT TEST DATA
-- =============================================================================

-- Insert allergieën
INSERT INTO allergie (naam, beschrijving, omschrijving, ernst_niveau, anafylactisch_risico) VALUES
('Noten', 'Allergie voor alle soorten noten', 'Reactie op noten zoals amandelen, walnoten, hazelnoten', 'hoog', 'hoog'),
('Gluten', 'Coeliakie - glutenintolerantie', 'Kan niet tegen gluten uit granen', 'middel', 'laag'),
('Lactose', 'Lactose-intolerantie', 'Kan geen melkproducten verdragen', 'laag', 'geen'),
('Ei', 'Allergie voor kippeneieren', 'Reactie op eiwitten in eieren', 'middel', 'redelijk_hoog'),
('Vis', 'Allergie voor vis en schaaldieren', 'Reactie op vis, garnalen, mosselen', 'hoog', 'hoog'),
('Soja', 'Soja-allergie', 'Reactie op sojaproducten', 'middel', 'laag'),
('Sesam', 'Sesamzaad allergie', 'Reactie op sesamzaden en -olie', 'hoog', 'redelijk_hoog'),
('Sulfiet', 'Sulfiet-overgevoeligheid', 'Reactie op sulfieten (conserveermiddelen)', 'laag', 'laag');

-- Insert gezinnen
INSERT INTO gezin (naam, code, adres, postcode, woonplaats, telefoon, email, aantal_personen) VALUES
('Familie Jansen', 'GZ001', 'Dorpsstraat 12', '5231 AB', 'Maaskantje', '0123-456789', 'jansen@email.com', 4),
('Gezin Van Der Berg', 'GZ002', 'Kerkstraat 45', '5231 CD', 'Maaskantje', '0123-789456', 'vandeberg@email.com', 3),
('Familie Pietersen', 'GZ003', 'Schoolstraat 8', '5231 EF', 'Maaskantje', '0123-654321', 'pietersen@email.com', 2);

-- Insert personen
INSERT INTO persoon (gezin_id, voornaam, achternaam, geboortedatum, geslacht, is_vertegenwoordiger) VALUES
(1, 'Jan', 'Jansen', '1980-05-15', 'man', TRUE),
(1, 'Marie', 'Jansen', '1982-08-22', 'vrouw', FALSE),
(1, 'Tim', 'Jansen', '2010-03-10', 'man', FALSE),
(1, 'Lisa', 'Jansen', '2012-11-30', 'vrouw', FALSE),
(2, 'Piet', 'Van Der Berg', '1975-12-01', 'man', TRUE),
(2, 'Anna', 'Van Der Berg', '2015-06-18', 'vrouw', FALSE),
(3, 'Kees', 'Pietersen', '1990-09-05', 'man', TRUE);

-- Insert allergie per persoon relaties
INSERT INTO allergie_per_persoon (persoon_id, allergie_id, ernst, opmerking, datum_vastgesteld) VALUES
(1, 1, 'hoog', 'Ernstige reactie op alle noten, altijd EpiPen bij zich', '2020-01-15'),
(2, 2, 'middel', 'Glutenvrij dieet noodzakelijk', '2019-05-20'),
(3, 3, 'laag', 'Lactosevrije producten gewenst', '2021-03-10'),
(4, 4, 'middel', 'Geen eieren in voedsel', '2022-07-12'),
(6, 5, 'hoog', 'Allergie voor alle vis en schaaldieren', '2023-02-28');

-- Insert rollen
INSERT INTO rol (naam, beschrijving) VALUES
('Administrator', 'Volledige toegang tot het systeem'),
('Medewerker', 'Toegang tot dagelijkse operaties'),
('Vrijwilliger', 'Beperkte toegang voor vrijwilligers');

-- Insert gebruikers
INSERT INTO gebruiker (naam, email, password, is_actief) VALUES
('Admin User', 'admin@voedselbank.nl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', TRUE),
('Medewerker Test', 'medewerker@voedselbank.nl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', TRUE);

-- Insert categorieën
INSERT INTO categorie (naam, beschrijving) VALUES
('Conserven', 'Geconserveerd voedsel in blik of pot'),
('Vers', 'Verse producten zoals groente en fruit'),
('Zuivel', 'Melkproducten en kaas'),
('Brood', 'Brood en bakkerijproducten'),
('Vlees', 'Vlees en vleesproducten');

-- Insert leveranciers
INSERT INTO leverancier (naam, adres, postcode, woonplaats, telefoon, email) VALUES
('Supermarkt Plus', 'Marktstraat 100', '5231 GH', 'Maaskantje', '0123-111222', 'contact@plus.nl'),
('Bakkerij De Korenschoof', 'Broodstraat 25', '5231 IJ', 'Maaskantje', '0123-333444', 'info@korenschoof.nl');

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- =============================================================================
-- SECTION 5: CREATE STORED PROCEDURES
-- =============================================================================

DELIMITER //

-- Stored Procedure: Haal alle gezinnen met allergieën op
CREATE PROCEDURE GetGezinnenMetAllergien()
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

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
        COUNT(DISTINCT p.id) AS aantal_personen_met_allergie,
        COUNT(DISTINCT app.id) AS aantal_allergie_registraties
    FROM gezin g
    INNER JOIN persoon p ON g.id = p.gezin_id
    INNER JOIN allergie_per_persoon app ON p.id = app.persoon_id
    INNER JOIN allergie a ON app.allergie_id = a.id
    WHERE g.is_actief = TRUE 
      AND p.is_actief = TRUE
      AND a.is_actief = TRUE
    GROUP BY g.id, g.naam, g.code, g.adres, g.postcode, g.woonplaats, g.telefoon, g.email, g.aantal_personen
    ORDER BY g.naam;
END //

-- Stored Procedure: Filter gezinnen op specifieke allergie
CREATE PROCEDURE FilterGezinnenByAllergie(IN allergie_id INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

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
        COUNT(DISTINCT p.id) AS aantal_personen_met_allergie,
        GROUP_CONCAT(DISTINCT CONCAT(p.voornaam, ' ', p.achternaam) SEPARATOR ', ') AS personen_met_allergie
    FROM gezin g
    INNER JOIN persoon p ON g.id = p.gezin_id
    INNER JOIN allergie_per_persoon app ON p.id = app.persoon_id
    INNER JOIN allergie a ON app.allergie_id = a.id
    WHERE g.is_actief = TRUE 
      AND p.is_actief = TRUE
      AND a.is_actief = TRUE
      AND a.id = allergie_id
    GROUP BY g.id, g.naam, g.code, g.adres, g.postcode, g.woonplaats, g.telefoon, g.email, a.naam
    ORDER BY g.naam;
END //

-- Stored Procedure: Haal allergie details voor een gezin
CREATE PROCEDURE GetGezinAllergieDetails(IN gezin_id INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    SELECT 
        p.id AS persoon_id,
        CONCAT(p.voornaam, ' ', p.achternaam) AS volledige_naam,
        p.voornaam,
        p.achternaam,
        p.geboortedatum,
        p.geslacht,
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
    FROM persoon p
    INNER JOIN allergie_per_persoon app ON p.id = app.persoon_id
    INNER JOIN allergie a ON app.allergie_id = a.id
    WHERE p.gezin_id = gezin_id
      AND p.is_actief = TRUE
      AND a.is_actief = TRUE
    ORDER BY p.voornaam, p.achternaam, a.naam;
END //

-- Stored Procedure: Update allergie voor persoon
CREATE PROCEDURE UpdatePersoonAllergie(
    IN p_persoon_id INT,
    IN p_oude_allergie_id INT,
    IN p_nieuwe_allergie_id INT,
    IN p_ernst_niveau VARCHAR(20),
    IN p_opmerking TEXT,
    OUT success_message VARCHAR(500),
    OUT error_message VARCHAR(500)
)
BEGIN
    DECLARE duplicate_count INT DEFAULT 0;
    DECLARE oude_allergie_naam VARCHAR(255);
    DECLARE nieuwe_allergie_naam VARCHAR(255);
    DECLARE persoon_naam VARCHAR(255);
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SET error_message = 'Er is een databasefout opgetreden bij het wijzigen van de allergie.';
        SET success_message = NULL;
    END;

    START TRANSACTION;

    -- Check of nieuwe allergie al bestaat voor deze persoon
    SELECT COUNT(*) INTO duplicate_count
    FROM allergie_per_persoon
    WHERE persoon_id = p_persoon_id AND allergie_id = p_nieuwe_allergie_id;

    IF duplicate_count > 0 THEN
        SET error_message = 'Deze persoon heeft deze allergie al.';
        SET success_message = NULL;
        ROLLBACK;
    ELSE
        -- Haal namen op voor logdoeleinden
        SELECT naam INTO oude_allergie_naam FROM allergie WHERE id = p_oude_allergie_id;
        SELECT naam INTO nieuwe_allergie_naam FROM allergie WHERE id = p_nieuwe_allergie_id;
        SELECT CONCAT(voornaam, ' ', achternaam) INTO persoon_naam FROM persoon WHERE id = p_persoon_id;

        -- Verwijder oude allergie
        DELETE FROM allergie_per_persoon 
        WHERE persoon_id = p_persoon_id AND allergie_id = p_oude_allergie_id;

        -- Voeg nieuwe allergie toe
        INSERT INTO allergie_per_persoon (
            persoon_id, 
            allergie_id, 
            ernst, 
            opmerking, 
            datum_vastgesteld,
            created_at, 
            updated_at
        ) VALUES (
            p_persoon_id,
            p_nieuwe_allergie_id,
            COALESCE(p_ernst_niveau, 'middel'),
            CONCAT('Gewijzigd van "', oude_allergie_naam, '" naar "', nieuwe_allergie_naam, '" op ', CURDATE(), '. ', COALESCE(p_opmerking, '')),
            CURDATE(),
            NOW(),
            NOW()
        );

        SET success_message = CONCAT('Allergie voor ', persoon_naam, ' succesvol gewijzigd van "', oude_allergie_naam, '" naar "', nieuwe_allergie_naam, '".');
        SET error_message = NULL;
        COMMIT;
    END IF;
END //

-- Stored Procedure: Statistieken voor dashboard
CREATE PROCEDURE GetAllergieStatistieken()
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    SELECT 
        (SELECT COUNT(*) FROM allergie WHERE is_actief = TRUE) AS totaal_allergieen,
        (SELECT COUNT(DISTINCT g.id) FROM gezin g 
         INNER JOIN persoon p ON g.id = p.gezin_id
         INNER JOIN allergie_per_persoon app ON p.id = app.persoon_id 
         WHERE g.is_actief = TRUE AND p.is_actief = TRUE) AS gezinnen_met_allergieen,
        (SELECT COUNT(DISTINCT p.id) FROM persoon p
         INNER JOIN allergie_per_persoon app ON p.id = app.persoon_id
         WHERE p.is_actief = TRUE) AS personen_met_allergieen,
        (SELECT COUNT(*) FROM allergie_per_persoon app
         INNER JOIN persoon p ON app.persoon_id = p.id
         WHERE p.is_actief = TRUE) AS totaal_allergie_registraties,
        (SELECT COUNT(*) FROM allergie_per_persoon app
         INNER JOIN persoon p ON app.persoon_id = p.id
         INNER JOIN allergie a ON app.allergie_id = a.id
         WHERE p.is_actief = TRUE AND a.anafylactisch_risico = 'hoog') AS hoog_risico_allergieen,
        (SELECT COUNT(*) FROM gezin WHERE is_actief = TRUE) AS totaal_actieve_gezinnen,
        (SELECT COUNT(*) FROM persoon WHERE is_actief = TRUE) AS totaal_actieve_personen;
END //

-- Stored Procedure: Haal populairste allergieën op
CREATE PROCEDURE GetPopulaireAllergien(IN limiet INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    SELECT 
        a.id,
        a.naam,
        a.beschrijving,
        a.omschrijving,
        a.ernst_niveau,
        a.anafylactisch_risico,
        COUNT(app.id) AS aantal_gevallen,
        COUNT(DISTINCT app.persoon_id) AS aantal_personen,
        ROUND((COUNT(app.id) / (SELECT COUNT(*) FROM allergie_per_persoon) * 100), 2) AS percentage
    FROM allergie a
    INNER JOIN allergie_per_persoon app ON a.id = app.allergie_id
    INNER JOIN persoon p ON app.persoon_id = p.id
    WHERE a.is_actief = TRUE AND p.is_actief = TRUE
    GROUP BY a.id, a.naam, a.beschrijving, a.omschrijving, a.ernst_niveau, a.anafylactisch_risico
    ORDER BY aantal_gevallen DESC, aantal_personen DESC
    LIMIT limiet;
END //

-- Stored Procedure: Zoek personen met specifieke allergie
CREATE PROCEDURE ZoekPersonenMetAllergie(IN allergie_naam VARCHAR(255))
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    SELECT 
        p.id AS persoon_id,
        CONCAT(p.voornaam, ' ', p.achternaam) AS volledige_naam,
        p.geboortedatum,
        g.naam AS gezin_naam,
        g.code AS gezin_code,
        a.naam AS allergie_naam,
        app.ernst,
        app.datum_vastgesteld,
        app.opmerking
    FROM persoon p
    INNER JOIN gezin g ON p.gezin_id = g.id
    INNER JOIN allergie_per_persoon app ON p.id = app.persoon_id
    INNER JOIN allergie a ON app.allergie_id = a.id
    WHERE a.naam LIKE CONCAT('%', allergie_naam, '%')
      AND p.is_actief = TRUE
      AND g.is_actief = TRUE
      AND a.is_actief = TRUE
    ORDER BY p.voornaam, p.achternaam;
END //

DELIMITER ;

-- =============================================================================
-- SECTION 6: CREATE ADDITIONAL USEFUL VIEWS
-- =============================================================================

-- View: Overzicht gezinnen met allergie informatie
CREATE VIEW view_gezinnen_allergie_overzicht AS
SELECT 
    g.id AS gezin_id,
    g.naam AS gezin_naam,
    g.code AS gezin_code,
    g.aantal_personen,
    COUNT(DISTINCT p.id) AS aantal_personen_met_allergie,
    COUNT(DISTINCT app.allergie_id) AS aantal_verschillende_allergieen,
    GROUP_CONCAT(DISTINCT a.naam ORDER BY a.naam SEPARATOR ', ') AS allergieen_lijst,
    MAX(CASE WHEN a.anafylactisch_risico = 'hoog' THEN 1 ELSE 0 END) AS heeft_hoog_risico_allergie
FROM gezin g
LEFT JOIN persoon p ON g.id = p.gezin_id AND p.is_actief = TRUE
LEFT JOIN allergie_per_persoon app ON p.id = app.persoon_id
LEFT JOIN allergie a ON app.allergie_id = a.id AND a.is_actief = TRUE
WHERE g.is_actief = TRUE
GROUP BY g.id, g.naam, g.code, g.aantal_personen
ORDER BY g.naam;

-- View: Allergie risico overzicht
CREATE VIEW view_allergie_risico_overzicht AS
SELECT 
    a.id,
    a.naam,
    a.ernst_niveau,
    a.anafylactisch_risico,
    COUNT(app.id) AS aantal_personen_betrokken,
    COUNT(DISTINCT p.gezin_id) AS aantal_gezinnen_betrokken
FROM allergie a
LEFT JOIN allergie_per_persoon app ON a.id = app.allergie_id
LEFT JOIN persoon p ON app.persoon_id = p.id AND p.is_actief = TRUE
WHERE a.is_actief = TRUE
GROUP BY a.id, a.naam, a.ernst_niveau, a.anafylactisch_risico
ORDER BY a.anafylactisch_risico DESC, aantal_personen_betrokken DESC;

-- =============================================================================
-- SCRIPT COMPLETE
-- =============================================================================

-- Show completion message
SELECT 'Database successfully created with all tables, stored procedures, test data and views!' AS Status;

-- Show basic statistics
SELECT 
    (SELECT COUNT(*) FROM gezin WHERE is_actief = TRUE) AS Actieve_Gezinnen,
    (SELECT COUNT(*) FROM persoon WHERE is_actief = TRUE) AS Actieve_Personen,
    (SELECT COUNT(*) FROM allergie WHERE is_actief = TRUE) AS Beschikbare_Allergieen,
    (SELECT COUNT(*) FROM allergie_per_persoon) AS Allergie_Registraties;
