-- ===================================
-- VOEDSELBANK MAASKANTJE DATABASE
-- Complete database script met alle tabellen en systeem velden
-- ===================================

DROP DATABASE IF EXISTS VoedselbankMaaskantje;
CREATE DATABASE VoedselbankMaaskantje CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE VoedselbankMaaskantje;

-- ===================================
-- ALLERGIE TABELLEN
-- ===================================

CREATE TABLE allergie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL UNIQUE,
    beschrijving TEXT,
    omschrijving TEXT,
    anafylactisch_risico ENUM('geen', 'zeerlaag', 'laag', 'redelijk_hoog', 'hoog') DEFAULT 'laag',
    ernst_niveau ENUM('laag', 'middel', 'hoog') DEFAULT 'middel',
    -- Systeem velden
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ===================================
-- GEZIN EN PERSOON TABELLEN
-- ===================================

CREATE TABLE gezin (
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
    -- Systeem velden
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE persoon (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gezin_id INT,
    voornaam VARCHAR(100) NOT NULL,
    tussenvoegsel VARCHAR(50),
    achternaam VARCHAR(100) NOT NULL,
    geboortedatum DATE,
    geslacht ENUM('man', 'vrouw', 'anders') DEFAULT 'anders',
    type_persoon ENUM('Manager', 'Medewerker', 'Vrijwilliger', 'Klant') DEFAULT 'Klant',
    is_vertegenwoordiger BIT(1) DEFAULT 0,
    -- Systeem velden
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gezin_id) REFERENCES gezin(id) ON DELETE SET NULL
);

CREATE TABLE allergie_per_persoon (
    id INT AUTO_INCREMENT PRIMARY KEY,
    persoon_id INT NOT NULL,
    allergie_id INT NOT NULL,
    ernst ENUM('laag', 'middel', 'hoog') DEFAULT 'middel',
    datum_vastgesteld DATE,
    -- Systeem velden
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (persoon_id) REFERENCES persoon(id) ON DELETE CASCADE,
    FOREIGN KEY (allergie_id) REFERENCES allergie(id) ON DELETE CASCADE,
    UNIQUE KEY unique_persoon_allergie (persoon_id, allergie_id)
);

-- ===================================
-- GEBRUIKER EN ROL TABELLEN
-- ===================================

CREATE TABLE rol (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL UNIQUE,
    -- Systeem velden
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE gebruiker (
    id INT AUTO_INCREMENT PRIMARY KEY,
    persoon_id INT,
    inlog_naam VARCHAR(100) UNIQUE,
    gebruikersnaam VARCHAR(100),
    wachtwoord VARCHAR(255),
    is_ingelogd BIT(1) DEFAULT 0,
    ingelogd DATETIME,
    uitgelogd DATETIME,
    -- Systeem velden
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (persoon_id) REFERENCES persoon(id) ON DELETE SET NULL
);

CREATE TABLE rol_per_gebruiker (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gebruiker_id INT NOT NULL,
    rol_id INT NOT NULL,
    -- Systeem velden
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gebruiker_id) REFERENCES gebruiker(id) ON DELETE CASCADE,
    FOREIGN KEY (rol_id) REFERENCES rol(id) ON DELETE CASCADE,
    UNIQUE KEY unique_gebruiker_rol (gebruiker_id, rol_id)
);

-- ===================================
-- PRODUCT EN CATEGORIE TABELLEN
-- ===================================

CREATE TABLE categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL UNIQUE,
    omschrijving TEXT,
    -- Systeem velden
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categorie_id INT NOT NULL,
    naam VARCHAR(255) NOT NULL,
    soort_allergie VARCHAR(255),
    barcode VARCHAR(50),
    houdbaarheidsdatum DATE,
    omschrijving TEXT,
    status ENUM('OpVoorraad', 'NietOpVoorraad', 'NietLeverbaar', 'OverHoudbaarheidsDatum') DEFAULT 'OpVoorraad',
    -- Systeem velden
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categorie_id) REFERENCES categorie(id) ON DELETE RESTRICT
);

-- ===================================
-- LEVERANCIER EN CONTACT TABELLEN
-- ===================================

CREATE TABLE contact (
    id INT AUTO_INCREMENT PRIMARY KEY,
    straat VARCHAR(255),
    huisnummer VARCHAR(10),
    toevoeging VARCHAR(10),
    postcode VARCHAR(10),
    woonplaats VARCHAR(100),
    email VARCHAR(255),
    mobiel VARCHAR(20),
    -- Systeem velden
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE leverancier (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(255) NOT NULL,
    contact_persoon VARCHAR(255),
    leverancier_nummer VARCHAR(50) UNIQUE,
    leverancier_type ENUM('Bedrijf', 'Instelling', 'Overheid', 'Particulier', 'Donor') DEFAULT 'Bedrijf',
    -- Systeem velden
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE contact_per_leverancier (
    id INT AUTO_INCREMENT PRIMARY KEY,
    leverancier_id INT NOT NULL,
    contact_id INT NOT NULL,
    -- Systeem velden
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (leverancier_id) REFERENCES leverancier(id) ON DELETE CASCADE,
    FOREIGN KEY (contact_id) REFERENCES contact(id) ON DELETE CASCADE
);

CREATE TABLE contact_per_gezin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gezin_id INT NOT NULL,
    contact_id INT NOT NULL,
    -- Systeem velden
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gezin_id) REFERENCES gezin(id) ON DELETE CASCADE,
    FOREIGN KEY (contact_id) REFERENCES contact(id) ON DELETE CASCADE
);

-- ===================================
-- EETWENS TABELLEN
-- ===================================

CREATE TABLE eetwens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL UNIQUE,
    omschrijving TEXT,
    -- Systeem velden
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE eetwens_per_gezin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gezin_id INT NOT NULL,
    eetwens_id INT NOT NULL,
    -- Systeem velden
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gezin_id) REFERENCES gezin(id) ON DELETE CASCADE,
    FOREIGN KEY (eetwens_id) REFERENCES eetwens(id) ON DELETE CASCADE,
    UNIQUE KEY unique_gezin_eetwens (gezin_id, eetwens_id)
);

-- ===================================
-- MAGAZIJN EN VOORRAAD TABELLEN
-- ===================================

CREATE TABLE magazijn (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ontvangstdatum DATE,
    uitleveringsdatum DATE,
    verpakkings_eenheid VARCHAR(100),
    aantal INT DEFAULT 0,
    -- Systeem velden
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE product_per_magazijn (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    magazijn_id INT NOT NULL,
    locatie VARCHAR(255),
    -- Systeem velden
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE,
    FOREIGN KEY (magazijn_id) REFERENCES magazijn(id) ON DELETE CASCADE
);

CREATE TABLE product_per_leverancier (
    id INT AUTO_INCREMENT PRIMARY KEY,
    leverancier_id INT NOT NULL,
    product_id INT NOT NULL,
    datum_aangeleverd DATE,
    datum_eerst_volgende_levering DATE,
    -- Systeem velden
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (leverancier_id) REFERENCES leverancier(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE
);

-- ===================================
-- VOEDSELPAKKET TABELLEN
-- ===================================

CREATE TABLE voedselpakket (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gezin_id INT NOT NULL,
    pakket_nummer VARCHAR(50),
    datum_samenstelling DATE,
    datum_uitgifte DATE,
    status ENUM('Uitgereikt', 'NietUitgereikt', 'NietMeerIngeschreven') DEFAULT 'NietUitgereikt',
    -- Systeem velden
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gezin_id) REFERENCES gezin(id) ON DELETE CASCADE
);

CREATE TABLE product_per_voedselpakket (
    id INT AUTO_INCREMENT PRIMARY KEY,
    voedselpakket_id INT NOT NULL,
    product_id INT NOT NULL,
    aantal_product_eenheden INT DEFAULT 1,
    -- Systeem velden
    is_actief BIT(1) DEFAULT 1,
    opmerking VARCHAR(255),
    datum_aangemaakt DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    datum_gewijzigd DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (voedselpakket_id) REFERENCES voedselpakket(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE
);

-- ===================================
-- INDEXES VOOR BETERE PRESTATIES
-- ===================================

-- Allergie indexes
CREATE INDEX idx_allergie_naam ON allergie(naam);
CREATE INDEX idx_allergie_actief ON allergie(is_actief);

-- Gezin indexes
CREATE INDEX idx_gezin_code ON gezin(code);
CREATE INDEX idx_gezin_naam ON gezin(naam);
CREATE INDEX idx_gezin_actief ON gezin(is_actief);

-- Persoon indexes
CREATE INDEX idx_persoon_gezin ON persoon(gezin_id);
CREATE INDEX idx_persoon_naam ON persoon(achternaam, voornaam);
CREATE INDEX idx_persoon_vertegenwoordiger ON persoon(is_vertegenwoordiger);
CREATE INDEX idx_persoon_actief ON persoon(is_actief);

-- Allergie per persoon indexes
CREATE INDEX idx_app_persoon ON allergie_per_persoon(persoon_id);
CREATE INDEX idx_app_allergie ON allergie_per_persoon(allergie_id);
CREATE INDEX idx_app_actief ON allergie_per_persoon(is_actief);

-- Product indexes
CREATE INDEX idx_product_categorie ON product(categorie_id);
CREATE INDEX idx_product_naam ON product(naam);
CREATE INDEX idx_product_barcode ON product(barcode);
CREATE INDEX idx_product_status ON product(status);
CREATE INDEX idx_product_actief ON product(is_actief);

-- Leverancier indexes
CREATE INDEX idx_leverancier_nummer ON leverancier(leverancier_nummer);
CREATE INDEX idx_leverancier_type ON leverancier(leverancier_type);
CREATE INDEX idx_leverancier_actief ON leverancier(is_actief);

-- Voedselpakket indexes
CREATE INDEX idx_voedselpakket_gezin ON voedselpakket(gezin_id);
CREATE INDEX idx_voedselpakket_datum ON voedselpakket(datum_uitgifte);
CREATE INDEX idx_voedselpakket_status ON voedselpakket(status);
CREATE INDEX idx_voedselpakket_actief ON voedselpakket(is_actief);

-- Gebruiker indexes
CREATE INDEX idx_gebruiker_persoon ON gebruiker(persoon_id);
CREATE INDEX idx_gebruiker_inlog ON gebruiker(inlog_naam);
CREATE INDEX idx_gebruiker_actief ON gebruiker(is_actief);

DELIMITER //

-- ===================================
-- STORED PROCEDURES
-- ===================================

-- Get alle gezinnen met allergieën
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
        COUNT(DISTINCT app.id) AS aantal_allergie_registraties,
        COUNT(DISTINCT p.id) AS aantal_personen_met_allergie,
        GROUP_CONCAT(DISTINCT a.naam SEPARATOR ', ') AS allergieen_lijst
    FROM gezin g
    INNER JOIN persoon p ON g.id = p.gezin_id
    INNER JOIN allergie_per_persoon app ON p.id = app.persoon_id
    INNER JOIN allergie a ON app.allergie_id = a.id
    WHERE g.is_actief = TRUE 
      AND p.is_actief = TRUE 
      AND app.is_actief = TRUE
      AND a.is_actief = TRUE
    GROUP BY g.id, g.naam, g.code, g.adres, g.postcode, g.woonplaats, g.telefoon, g.email, g.aantal_personen
    ORDER BY g.naam;
END //

-- Filter gezinnen op specifieke allergie
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
      AND app.is_actief = TRUE
      AND a.is_actief = TRUE
      AND a.id = allergie_id
    GROUP BY g.id, g.naam, g.code, g.adres, g.postcode, g.woonplaats, g.telefoon, g.email, a.naam
    ORDER BY g.naam;
END //

-- Get allergie details voor een gezin
CREATE PROCEDURE GetGezinAllergieDetails(IN gezin_id INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

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
    FROM persoon p
    INNER JOIN allergie_per_persoon app ON p.id = app.persoon_id
    INNER JOIN allergie a ON app.allergie_id = a.id
    WHERE p.gezin_id = gezin_id 
      AND p.is_actief = TRUE 
      AND app.is_actief = TRUE
      AND a.is_actief = TRUE
    ORDER BY p.achternaam, p.voornaam, a.naam;
END //

-- Update allergie voor persoon
CREATE PROCEDURE UpdatePersoonAllergie(
    IN p_persoon_id INT,
    IN p_allergie_id INT,
    IN p_ernst ENUM('laag', 'middel', 'hoog'),
    IN p_opmerking VARCHAR(255)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;
    
    UPDATE allergie_per_persoon 
    SET ernst = p_ernst,
        opmerking = p_opmerking,
        datum_gewijzigd = CURRENT_TIMESTAMP(6)
    WHERE persoon_id = p_persoon_id 
      AND allergie_id = p_allergie_id
      AND is_actief = TRUE;
    
    COMMIT;
END //

-- Add allergie voor persoon
CREATE PROCEDURE AddPersoonAllergie(
    IN p_persoon_id INT,
    IN p_allergie_id INT,
    IN p_ernst ENUM('laag', 'middel', 'hoog'),
    IN p_opmerking VARCHAR(255)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;
    
    INSERT INTO allergie_per_persoon (
        persoon_id,
        allergie_id,
        ernst,
        opmerking,
        datum_vastgesteld,
        is_actief,
        datum_aangemaakt
    ) VALUES (
        p_persoon_id,
        p_allergie_id,
        p_ernst,
        p_opmerking,
        CURDATE(),
        1,
        CURRENT_TIMESTAMP(6)
    );
    
    COMMIT;
END //

-- Get allergie statistieken
CREATE PROCEDURE GetAllergieStatistieken()
BEGIN
    SELECT 
        a.id,
        a.naam,
        a.anafylactisch_risico,
        COUNT(app.id) AS aantal_personen,
        COUNT(DISTINCT p.gezin_id) AS aantal_gezinnen,
        ROUND(AVG(CASE app.ernst 
            WHEN 'laag' THEN 1 
            WHEN 'middel' THEN 2 
            WHEN 'hoog' THEN 3 
            ELSE 1 END), 2) AS gemiddelde_ernst
    FROM allergie a
    LEFT JOIN allergie_per_persoon app ON a.id = app.allergie_id AND app.is_actief = TRUE
    LEFT JOIN persoon p ON app.persoon_id = p.id AND p.is_actief = TRUE
    WHERE a.is_actief = TRUE
    GROUP BY a.id, a.naam, a.anafylactisch_risico
    ORDER BY aantal_personen DESC, a.naam;
END //

-- Get populaire allergieën
CREATE PROCEDURE GetPopulaireAllergieen(IN limiet INT)
BEGIN
    SELECT 
        a.id,
        a.naam,
        a.anafylactisch_risico,
        COUNT(app.id) AS aantal_registraties
    FROM allergie a
    INNER JOIN allergie_per_persoon app ON a.id = app.allergie_id
    WHERE a.is_actief = TRUE AND app.is_actief = TRUE
    GROUP BY a.id, a.naam, a.anafylactisch_risico
    ORDER BY aantal_registraties DESC
    LIMIT limiet;
END //

DELIMITER ;

-- ===================================
-- TESTDATA INVOEGEN
-- ===================================

-- Allergieën
INSERT INTO allergie (id, naam, omschrijving, anafylactisch_risico, is_actief, datum_aangemaakt) VALUES
(1, 'Soja', 'Allergisch voor soja en sojaproducten', 'laag', 1, CURRENT_TIMESTAMP(6)),
(2, 'Lactose', 'Allergisch voor lactose en zuivelproducten', 'zeerlaag', 1, CURRENT_TIMESTAMP(6)),
(3, 'Hazelnoten', 'Allergisch voor hazelnoten', 'redelijk_hoog', 1, CURRENT_TIMESTAMP(6)),
(4, 'Schaaldieren', 'Allergisch voor schaaldieren zoals garnalen, krab, kreeft', 'hoog', 1, CURRENT_TIMESTAMP(6)),
(5, 'Pindas', 'Allergisch voor pindas en pindaproducten', 'hoog', 1, CURRENT_TIMESTAMP(6)),
(6, 'Gluten', 'Allergisch voor gluten in granen', 'laag', 1, CURRENT_TIMESTAMP(6));

-- Rollen
INSERT INTO rol (id, naam, is_actief, datum_aangemaakt) VALUES
(1, 'Manager', 1, CURRENT_TIMESTAMP(6)),
(2, 'Medewerker', 1, CURRENT_TIMESTAMP(6)),
(3, 'Vrijwilliger', 1, CURRENT_TIMESTAMP(6));

-- Categorieën
INSERT INTO categorie (id, naam, omschrijving, is_actief, datum_aangemaakt) VALUES
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
INSERT INTO contact (id, straat, huisnummer, toevoeging, postcode, woonplaats, email, mobiel, is_actief, datum_aangemaakt) VALUES
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

-- Eetlust opties
INSERT INTO eetwens (id, naam, omschrijving, is_actief, datum_aangemaakt) VALUES
(1, 'GeenVarken', 'Geen Varkensvlees', 1, CURRENT_TIMESTAMP(6)),
(2, 'Veganistisch', 'Geen zuivelproducten en vlees', 1, CURRENT_TIMESTAMP(6)),
(3, 'Vegetarisch', 'Geen vlees', 1, CURRENT_TIMESTAMP(6)),
(4, 'Omnivoor', 'Geen beperkingen', 1, CURRENT_TIMESTAMP(6));

-- Gezinnen
INSERT INTO gezin (id, naam, code, omschrijving, aantal_volwassenen, aantal_kinderen, aantal_babys, totaal_aantal_personen, is_actief, datum_aangemaakt) VALUES
(1, 'ZevenhuizenGezin', 'G0001', 'Bijstandsgezin', 2, 2, 0, 4, 1, CURRENT_TIMESTAMP(6)),
(2, 'BergkampGezin', 'G0002', 'Bijstandsgezin', 2, 1, 1, 4, 1, CURRENT_TIMESTAMP(6)),
(3, 'HeuvelGezin', 'G0003', 'Bijstandsgezin', 2, 0, 0, 2, 1, CURRENT_TIMESTAMP(6)),
(4, 'ScherderGezin', 'G0004', 'Bijstandsgezin', 1, 0, 2, 3, 1, CURRENT_TIMESTAMP(6)),
(5, 'DeJongGezin', 'G0005', 'Bijstandsgezin', 1, 1, 0, 2, 1, CURRENT_TIMESTAMP(6)),
(6, 'VanderBergGezin', 'G0006', 'AlleenGaande', 1, 0, 0, 1, 1, CURRENT_TIMESTAMP(6));

-- Leveranciers
INSERT INTO leverancier (id, naam, contact_persoon, leverancier_nummer, leverancier_type, is_actief, datum_aangemaakt) VALUES
(1, 'Albert Heijn', 'Ruud ter Weijden', 'L0001', 'Bedrijf', 1, CURRENT_TIMESTAMP(6)),
(2, 'Albertus Kerk', 'Leo Pastoor', 'L0002', 'Instelling', 1, CURRENT_TIMESTAMP(6)),
(3, 'Gemeente Utrecht', 'Mohammed Yazidi', 'L0003', 'Overheid', 1, CURRENT_TIMESTAMP(6)),
(4, 'Boerderij Meerhoven', 'Bertus van Driel', 'L0004', 'Particulier', 1, CURRENT_TIMESTAMP(6)),
(5, 'Jan van der Heijden', 'Jan van der Heijden', 'L0005', 'Donor', 1, CURRENT_TIMESTAMP(6)),
(6, 'Vomar', 'Jaco Pastorius', 'L0006', 'Bedrijf', 1, CURRENT_TIMESTAMP(6)),
(7, 'DekaMarkt', 'Sil den Dollaard', 'L0007', 'Bedrijf', 1, CURRENT_TIMESTAMP(6)),
(8, 'Gemeente Vught', 'Jan Blokker', 'L0008', 'Overheid', 1, CURRENT_TIMESTAMP(6));

-- Personen
INSERT INTO persoon (id, gezin_id, voornaam, tussenvoegsel, achternaam, geboortedatum, type_persoon, is_vertegenwoordiger, is_actief, datum_aangemaakt) VALUES
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
INSERT INTO gebruiker (id, persoon_id, inlog_naam, gebruikersnaam, wachtwoord, is_ingelogd, ingelogd, uitgelogd, is_actief, datum_aangemaakt) VALUES
(1, 1, 'Hans', 'hans@maaskantje.nl', '$2y$10$296RMzqzZqWENu9vyh6axed0DkfsuYkbvoI/AXVowCp/DL6zKiF0i', 1, '2024-03-13 17:03:06', NULL, 1, CURRENT_TIMESTAMP(6)),
(2, 2, 'Jan', 'jan@maaskantje.nl', '$2y$10$296RMzqzZqWENu9vyh6axed0DkfsuYkbvoI/AXVowCp/DL3zKiF6i', 0, '2024-03-13 15:13:23', '2024-03-13 15:23:46', 1, CURRENT_TIMESTAMP(6)),
(3, 3, 'Herman', 'herman@maaskantje.nl', '$2y$10$296RMzqzZqWENu9vyh6axed0DkfsuYkbvoI/AXVuwCp/DL9zKiF2i', 1, '2024-06-20 12:05:20', NULL, 1, CURRENT_TIMESTAMP(6));

-- Allergie per persoon relaties
INSERT INTO allergie_per_persoon (id, persoon_id, allergie_id, ernst, is_actief, datum_aangemaakt) VALUES
(1, 4, 1, 'middel', 1, CURRENT_TIMESTAMP(6)),    -- Johan van Zevenhuizen - Soja
(2, 5, 2, 'laag', 1, CURRENT_TIMESTAMP(6)),      -- Sarah den Dolder - Lactose
(3, 6, 3, 'hoog', 1, CURRENT_TIMESTAMP(6)),      -- Theo van Zevenhuizen - Hazelnoten
(4, 7, 4, 'hoog', 1, CURRENT_TIMESTAMP(6)),      -- Jantien van Zevenhuizen - Schaaldieren
(5, 8, 5, 'hoog', 1, CURRENT_TIMESTAMP(6)),      -- Arjan Bergkamp - Pindas
(6, 9, 6, 'middel', 1, CURRENT_TIMESTAMP(6)),    -- Janneke Sanders - Gluten
(7, 10, 2, 'laag', 1, CURRENT_TIMESTAMP(6)),     -- Stein Bergkamp - Lactose
(8, 12, 5, 'hoog', 1, CURRENT_TIMESTAMP(6)),     -- Mazin van Vliet - Pindas
(9, 13, 3, 'middel', 1, CURRENT_TIMESTAMP(6)),   -- Selma van de Heuvel - Hazelnoten
(10, 14, 6, 'hoog', 1, CURRENT_TIMESTAMP(6)),    -- Eva Scherder - Gluten
(11, 15, 4, 'middel', 1, CURRENT_TIMESTAMP(6)),  -- Felicia Scherder - Schaaldieren
(12, 16, 2, 'laag', 1, CURRENT_TIMESTAMP(6)),    -- Devin Scherder - Lactose
(13, 17, 6, 'hoog', 1, CURRENT_TIMESTAMP(6)),    -- Frieda de Jong - Gluten
(14, 17, 5, 'hoog', 1, CURRENT_TIMESTAMP(6)),    -- Frieda de Jong - Pindas (dubbele allergie)
(15, 18, 3, 'laag', 1, CURRENT_TIMESTAMP(6)),    -- Simeon de Jong - Hazelnoten
(16, 19, 1, 'middel', 1, CURRENT_TIMESTAMP(6));  -- Hanna van der Berg - Soja

-- Rol per gebruiker
INSERT INTO rol_per_gebruiker (id, gebruiker_id, rol_id, is_actief, datum_aangemaakt) VALUES
(1, 1, 1, 1, CURRENT_TIMESTAMP(6)),
(2, 2, 2, 1, CURRENT_TIMESTAMP(6)),
(3, 3, 3, 1, CURRENT_TIMESTAMP(6));

-- Eetwens per gezin
INSERT INTO eetwens_per_gezin (id, gezin_id, eetwens_id, is_actief, datum_aangemaakt) VALUES
(1, 1, 2, 1, CURRENT_TIMESTAMP(6)),
(2, 2, 4, 1, CURRENT_TIMESTAMP(6)),
(3, 3, 4, 1, CURRENT_TIMESTAMP(6)),
(4, 4, 3, 1, CURRENT_TIMESTAMP(6)),
(5, 5, 2, 1, CURRENT_TIMESTAMP(6));

-- Contact per leverancier
INSERT INTO contact_per_leverancier (id, leverancier_id, contact_id, is_actief, datum_aangemaakt) VALUES
(1, 1, 7, 1, CURRENT_TIMESTAMP(6)),
(2, 2, 8, 1, CURRENT_TIMESTAMP(6)),
(3, 3, 9, 1, CURRENT_TIMESTAMP(6)),
(4, 4, 10, 1, CURRENT_TIMESTAMP(6)),
(5, 6, 11, 1, CURRENT_TIMESTAMP(6)),
(6, 7, 12, 1, CURRENT_TIMESTAMP(6)),
(7, 8, 13, 1, CURRENT_TIMESTAMP(6));

-- Contact per gezin
INSERT INTO contact_per_gezin (id, gezin_id, contact_id, is_actief, datum_aangemaakt) VALUES
(1, 1, 1, 1, CURRENT_TIMESTAMP(6)),
(2, 2, 2, 1, CURRENT_TIMESTAMP(6)),
(3, 3, 3, 1, CURRENT_TIMESTAMP(6)),
(4, 4, 4, 1, CURRENT_TIMESTAMP(6)),
(5, 5, 5, 1, CURRENT_TIMESTAMP(6)),
(6, 6, 6, 1, CURRENT_TIMESTAMP(6));

-- Magazijn data
INSERT INTO magazijn (id, ontvangstdatum, uitleveringsdatum, verpakkings_eenheid, aantal, is_actief, datum_aangemaakt) VALUES
(1, '2024-05-12', NULL, '5 kg', 20, 1, CURRENT_TIMESTAMP(6)),
(2, '2024-05-26', NULL, '2.5 kg', 40, 1, CURRENT_TIMESTAMP(6)),
(3, '2024-04-02', NULL, '1 kg', 30, 1, CURRENT_TIMESTAMP(6)),
(4, '2024-05-16', NULL, '1.5 kg', 25, 1, CURRENT_TIMESTAMP(6)),
(5, '2024-05-23', NULL, '4 stuks', 75, 1, CURRENT_TIMESTAMP(6));

-- Producten
INSERT INTO product (id, categorie_id, naam, soort_allergie, barcode, houdbaarheidsdatum, omschrijving, status, is_actief, datum_aangemaakt) VALUES
(1, 1, 'Aardappel', NULL, '8719587321239', '2024-07-12', 'Kruimige aardappel', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(2, 1, 'Ui', NULL, '8719437321335', '2024-09-02', 'Gele ui', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(3, 1, 'Appel', NULL, '8719486321332', '2024-08-16', 'Granny Smith', 'NietLeverbaar', 1, CURRENT_TIMESTAMP(6)),
(4, 1, 'Banaan', NULL, '8719484321336', '2024-07-12', 'Biologische Banaan', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(5, 2, 'Kaas', 'Lactose', '8719487421338', '2024-09-19', 'Jonge Kaas', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(6, 2, 'Rosbief', NULL, '8719487421331', '2024-07-23', 'Rundvlees', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(7, 3, 'Melk', 'Lactose', '8719447321332', '2024-07-23', 'Halfvolle melk', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(8, 3, 'Sojamelk', 'Soja', '8719447321333', '2024-08-15', 'Biologische sojamelk', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(9, 4, 'Brood', 'Gluten', '8719487721337', '2024-07-07', 'Volkoren brood', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(10, 4, 'Hazelnootcake', 'Hazelnoten,Gluten', '8719487721340', '2024-07-14', 'Cake met hazelnoten', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(11, 2, 'Garnalen', 'Schaaldieren', '8719487421350', '2024-07-10', 'Noordzeegarnalen', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6)),
(12, 8, 'Pindakaas', 'Pindas', '8719487721355', '2024-10-20', 'Biologische pindakaas', 'OpVoorraad', 1, CURRENT_TIMESTAMP(6));

-- Voedselpakketten
INSERT INTO voedselpakket (id, gezin_id, pakket_nummer, datum_samenstelling, datum_uitgifte, status, is_actief, datum_aangemaakt) VALUES
(1, 1, '1', '2024-04-06', '2024-04-07', 'Uitgereikt', 1, CURRENT_TIMESTAMP(6)),
(2, 1, '2', '2024-04-13', NULL, 'NietUitgereikt', 1, CURRENT_TIMESTAMP(6)),
(3, 1, '3', '2024-04-20', NULL, 'NietMeerIngeschreven', 1, CURRENT_TIMESTAMP(6)),
(4, 2, '4', '2024-04-06', '2024-04-07', 'Uitgereikt', 1, CURRENT_TIMESTAMP(6)),
(5, 2, '5', '2024-04-13', '2024-04-14', 'Uitgereikt', 1, CURRENT_TIMESTAMP(6)),
(6, 2, '6', '2024-04-20', NULL, 'NietUitgereikt', 1, CURRENT_TIMESTAMP(6));

-- Product per voedselpakket
INSERT INTO product_per_voedselpakket (id, voedselpakket_id, product_id, aantal_product_eenheden, is_actief, datum_aangemaakt) VALUES
(1, 1, 7, 1, 1, CURRENT_TIMESTAMP(6)),
(2, 1, 8, 2, 1, CURRENT_TIMESTAMP(6)),
(3, 1, 9, 1, 1, CURRENT_TIMESTAMP(6)),
(4, 2, 2, 1, 1, CURRENT_TIMESTAMP(6)),
(5, 2, 3, 2, 1, CURRENT_TIMESTAMP(6)),
(6, 2, 4, 1, 1, CURRENT_TIMESTAMP(6));

-- Product per leverancier
INSERT INTO product_per_leverancier (id, leverancier_id, product_id, datum_aangeleverd, datum_eerst_volgende_levering, is_actief, datum_aangemaakt) VALUES
(1, 4, 1, '2024-04-12', '2024-05-12', 1, CURRENT_TIMESTAMP(6)),
(2, 4, 2, '2024-03-02', '2024-04-02', 1, CURRENT_TIMESTAMP(6)),
(3, 2, 3, '2024-07-16', '2024-08-16', 1, CURRENT_TIMESTAMP(6)),
(4, 1, 4, '2024-02-12', '2024-03-12', 1, CURRENT_TIMESTAMP(6)),
(5, 4, 5, '2024-05-19', '2024-06-19', 1, CURRENT_TIMESTAMP(6));

-- Product per magazijn
INSERT INTO product_per_magazijn (id, product_id, magazijn_id, locatie, is_actief, datum_aangemaakt) VALUES
(1, 1, 1, 'Berlicum', 1, CURRENT_TIMESTAMP(6)),
(2, 2, 2, 'Rosmalen', 1, CURRENT_TIMESTAMP(6)),
(3, 3, 3, 'Berlicum', 1, CURRENT_TIMESTAMP(6)),
(4, 4, 4, 'Berlicum', 1, CURRENT_TIMESTAMP(6)),
(5, 5, 5, 'Rosmalen', 1, CURRENT_TIMESTAMP(6));

-- ===================================
-- FINAL MESSAGE
-- ===================================
SELECT 'Database VoedselbankMaaskantje successfully created with all tables, system fields, stored procedures and test data!' AS Message;
