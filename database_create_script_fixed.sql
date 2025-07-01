-- Voedselbank Maaskantje Database Create Script
-- Complete database structure with correct order

-- Disable foreign key checks temporarily
SET FOREIGN_KEY_CHECKS = 0;

-- Drop all tables
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

-- 1. Base tables (no foreign keys)
CREATE TABLE gezin (
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE allergie (
    id INT PRIMARY KEY AUTO_INCREMENT,
    naam VARCHAR(255) NOT NULL UNIQUE,
    beschrijving TEXT,
    ernst_niveau ENUM('laag', 'middel', 'hoog', 'levensgevaarlijk') DEFAULT 'middel',
    is_actief BOOLEAN DEFAULT TRUE,
    opmerking TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE rol (
    id INT PRIMARY KEY AUTO_INCREMENT,
    naam VARCHAR(100) NOT NULL UNIQUE,
    beschrijving TEXT,
    is_actief BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE categorie (
    id INT PRIMARY KEY AUTO_INCREMENT,
    naam VARCHAR(255) NOT NULL UNIQUE,
    beschrijving TEXT,
    parent_categorie_id INT,
    is_actief BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE contact (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type ENUM('telefoon', 'email', 'adres', 'website') NOT NULL,
    waarde VARCHAR(255) NOT NULL,
    label VARCHAR(100),
    is_primair BOOLEAN DEFAULT FALSE,
    is_actief BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE eetwens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    naam VARCHAR(255) NOT NULL UNIQUE,
    beschrijving TEXT,
    type ENUM('dieet', 'voorkeur', 'religie', 'medisch') DEFAULT 'voorkeur',
    is_actief BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE leverancier (
    id INT PRIMARY KEY AUTO_INCREMENT,
    naam VARCHAR(255) NOT NULL,
    bedrijfsnaam VARCHAR(255),
    kvk_nummer VARCHAR(20),
    btw_nummer VARCHAR(30),
    adres VARCHAR(255),
    postcode VARCHAR(10),
    woonplaats VARCHAR(100),
    is_actief BOOLEAN DEFAULT TRUE,
    opmerking TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE magazijn (
    id INT PRIMARY KEY AUTO_INCREMENT,
    naam VARCHAR(255) NOT NULL,
    locatie VARCHAR(255),
    capaciteit INT,
    temperatuur_min DECIMAL(5,2),
    temperatuur_max DECIMAL(5,2),
    is_actief BOOLEAN DEFAULT TRUE,
    opmerking TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 2. Tables with one-level foreign keys
CREATE TABLE persoon (
    id INT PRIMARY KEY AUTO_INCREMENT,
    gezin_id INT,
    voornaam VARCHAR(100) NOT NULL,
    achternaam VARCHAR(100) NOT NULL,
    geboortedatum DATE,
    geslacht ENUM('M', 'V', 'X') DEFAULT 'X',
    is_actief BOOLEAN DEFAULT TRUE,
    opmerking TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gezin_id) REFERENCES gezin(id) ON DELETE CASCADE
);

CREATE TABLE product (
    id INT PRIMARY KEY AUTO_INCREMENT,
    categorie_id INT NOT NULL,
    naam VARCHAR(255) NOT NULL,
    merk VARCHAR(255),
    ean_code VARCHAR(20),
    eenheid VARCHAR(50) DEFAULT 'stuks',
    houdbaarheid_dagen INT DEFAULT 0,
    bewaar_temperatuur DECIMAL(5,2),
    is_actief BOOLEAN DEFAULT TRUE,
    opmerking TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categorie_id) REFERENCES categorie(id) ON DELETE RESTRICT
);

-- 3. Tables with two-level foreign keys
CREATE TABLE gebruiker (
    id INT PRIMARY KEY AUTO_INCREMENT,
    persoon_id INT NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100),
    is_actief BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (persoon_id) REFERENCES persoon(id) ON DELETE CASCADE
);

CREATE TABLE allergie_per_persoon (
    id INT PRIMARY KEY AUTO_INCREMENT,
    persoon_id INT NOT NULL,
    allergie_id INT NOT NULL,
    ernst ENUM('laag', 'middel', 'hoog', 'levensgevaarlijk') DEFAULT 'middel',
    opmerking TEXT,
    datum_vastgesteld DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (persoon_id) REFERENCES persoon(id) ON DELETE CASCADE,
    FOREIGN KEY (allergie_id) REFERENCES allergie(id) ON DELETE CASCADE,
    UNIQUE KEY unique_persoon_allergie (persoon_id, allergie_id)
);

CREATE TABLE voedselpakket (
    id INT PRIMARY KEY AUTO_INCREMENT,
    gezin_id INT NOT NULL,
    datum_uitgegeven DATE NOT NULL,
    uitgegeven_door INT,
    status ENUM('voorbereid', 'uitgegeven', 'geannuleerd') DEFAULT 'voorbereid',
    totaal_gewicht DECIMAL(8,2),
    opmerking TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gezin_id) REFERENCES gezin(id) ON DELETE CASCADE,
    FOREIGN KEY (uitgegeven_door) REFERENCES gebruiker(id) ON DELETE SET NULL
);

-- 4. Junction tables
CREATE TABLE rol_per_gebruiker (
    id INT PRIMARY KEY AUTO_INCREMENT,
    gebruiker_id INT NOT NULL,
    rol_id INT NOT NULL,
    toegekend_op TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    toegekend_door INT,
    is_actief BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gebruiker_id) REFERENCES gebruiker(id) ON DELETE CASCADE,
    FOREIGN KEY (rol_id) REFERENCES rol(id) ON DELETE CASCADE,
    FOREIGN KEY (toegekend_door) REFERENCES gebruiker(id) ON DELETE SET NULL,
    UNIQUE KEY unique_gebruiker_rol (gebruiker_id, rol_id)
);

CREATE TABLE contact_per_gezin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    gezin_id INT NOT NULL,
    contact_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gezin_id) REFERENCES gezin(id) ON DELETE CASCADE,
    FOREIGN KEY (contact_id) REFERENCES contact(id) ON DELETE CASCADE,
    UNIQUE KEY unique_gezin_contact (gezin_id, contact_id)
);

CREATE TABLE contact_per_leverancier (
    id INT PRIMARY KEY AUTO_INCREMENT,
    leverancier_id INT NOT NULL,
    contact_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (leverancier_id) REFERENCES leverancier(id) ON DELETE CASCADE,
    FOREIGN KEY (contact_id) REFERENCES contact(id) ON DELETE CASCADE,
    UNIQUE KEY unique_leverancier_contact (leverancier_id, contact_id)
);

CREATE TABLE eetwens_per_gezin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    gezin_id INT NOT NULL,
    eetwens_id INT NOT NULL,
    opmerking TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gezin_id) REFERENCES gezin(id) ON DELETE CASCADE,
    FOREIGN KEY (eetwens_id) REFERENCES eetwens(id) ON DELETE CASCADE,
    UNIQUE KEY unique_gezin_eetwens (gezin_id, eetwens_id)
);

CREATE TABLE product_per_leverancier (
    id INT PRIMARY KEY AUTO_INCREMENT,
    leverancier_id INT NOT NULL,
    product_id INT NOT NULL,
    leverancier_productcode VARCHAR(100),
    inkoopprijs DECIMAL(8,2),
    minimum_bestelhoeveelheid INT DEFAULT 1,
    levertijd_dagen INT DEFAULT 1,
    is_actief BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (leverancier_id) REFERENCES leverancier(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE,
    UNIQUE KEY unique_leverancier_product (leverancier_id, product_id)
);

CREATE TABLE product_per_magazijn (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    magazijn_id INT NOT NULL,
    voorraad_aantal INT DEFAULT 0,
    minimum_voorraad INT DEFAULT 0,
    maximum_voorraad INT DEFAULT 1000,
    locatie_code VARCHAR(50),
    laatste_inventaris TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE,
    FOREIGN KEY (magazijn_id) REFERENCES magazijn(id) ON DELETE CASCADE,
    UNIQUE KEY unique_product_magazijn (product_id, magazijn_id)
);

CREATE TABLE product_per_voedselpakket (
    id INT PRIMARY KEY AUTO_INCREMENT,
    voedselpakket_id INT NOT NULL,
    product_id INT NOT NULL,
    aantal INT NOT NULL DEFAULT 1,
    gewicht DECIMAL(8,2),
    eenheid VARCHAR(50) DEFAULT 'stuks',
    houdbaar_tot DATE,
    opmerking TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (voedselpakket_id) REFERENCES voedselpakket(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE
);

-- Add self-referencing foreign key for categorie
ALTER TABLE categorie ADD FOREIGN KEY (parent_categorie_id) REFERENCES categorie(id) ON DELETE SET NULL;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- Create indexes for better performance
CREATE INDEX idx_persoon_gezin ON persoon(gezin_id);
CREATE INDEX idx_allergie_per_persoon_persoon ON allergie_per_persoon(persoon_id);
CREATE INDEX idx_allergie_per_persoon_allergie ON allergie_per_persoon(allergie_id);
CREATE INDEX idx_gebruiker_email ON gebruiker(email);
CREATE INDEX idx_gebruiker_persoon ON gebruiker(persoon_id);
CREATE INDEX idx_rol_per_gebruiker_gebruiker ON rol_per_gebruiker(gebruiker_id);
CREATE INDEX idx_rol_per_gebruiker_rol ON rol_per_gebruiker(rol_id);
CREATE INDEX idx_voedselpakket_gezin ON voedselpakket(gezin_id);
CREATE INDEX idx_voedselpakket_datum ON voedselpakket(datum_uitgegeven);
CREATE INDEX idx_product_categorie ON product(categorie_id);
CREATE INDEX idx_product_ean ON product(ean_code);

-- Insert basic data
INSERT INTO allergie (naam, beschrijving, ernst_niveau) VALUES
('Noten', 'Alle soorten noten en notenprodukten', 'hoog'),
('Gluten', 'Glutenintolerantie en coeliakie', 'middel'),
('Lactose', 'Lactose-intolerantie', 'laag'),
('Eieren', 'Allergie voor kippeneieren', 'middel'),
('Vis', 'Allergie voor alle vissoorten', 'hoog'),
('Schaaldieren', 'Garnalen, krab, kreeft etc.', 'hoog'),
('Soja', 'Soja-allergie', 'middel'),
('Sesam', 'Sesamzaad allergie', 'middel');

INSERT INTO rol (naam, beschrijving) VALUES
('Administrator', 'Volledige toegang tot alle functionaliteiten'),
('Coördinator', 'Toegang tot beheer van gezinnen en voedseluitgifte'),
('Vrijwilliger', 'Basis toegang voor voedselpakket uitgifte'),
('Gebruiker', 'Beperkte toegang voor eigen gegevens');

INSERT INTO categorie (naam, beschrijving) VALUES
('Groenten', 'Verse groenten en fruit'),
('Vlees', 'Vlees en vleesproducten'),
('Zuivel', 'Melk, kaas, yoghurt etc.'),
('Brood', 'Brood en bakkerijproducten'),
('Conserven', 'Ingeblikte en geconserveerde producten'),
('Diepvries', 'Diepvriesproducten'),
('Dranken', 'Frisdranken, sappen etc.'),
('Snacks', 'Koekjes, chips en snacks');

INSERT INTO eetwens (naam, beschrijving, type) VALUES
('Vegetarisch', 'Geen vlees of vis', 'dieet'),
('Veganistisch', 'Geen dierlijke producten', 'dieet'),
('Halal', 'Volgens islamitische voorschriften', 'religie'),
('Kosher', 'Volgens joodse voorschriften', 'religie'),
('Suikervrij', 'Geen toegevoegde suikers', 'medisch'),
('Zoutarm', 'Weinig zout/natrium', 'medisch');

-- Sample test data
INSERT INTO gezin (naam, adres, postcode, woonplaats, telefoon, email) VALUES
('Familie Jansen', 'Hoofdstraat 123', '1234AB', 'Maaskantje', '0123-456789', 'jansen@email.com'),
('Familie de Vries', 'Kerkstraat 45', '1234CD', 'Maaskantje', '0123-987654', 'devries@email.com'),
('Familie Bakker', 'Molenweg 78', '1234EF', 'Maaskantje', '0123-567890', 'bakker@email.com');

INSERT INTO persoon (gezin_id, voornaam, achternaam, geboortedatum, geslacht) VALUES
(1, 'Jan', 'Jansen', '1980-05-15', 'M'),
(1, 'Marie', 'Jansen', '1982-08-22', 'V'),
(1, 'Piet', 'Jansen', '2010-03-10', 'M'),
(2, 'Kees', 'de Vries', '1975-12-03', 'M'),
(2, 'Anna', 'de Vries', '1978-07-18', 'V'),
(3, 'Hans', 'Bakker', '1990-01-25', 'M');

INSERT INTO allergie_per_persoon (persoon_id, allergie_id, ernst, opmerking) VALUES
(1, 1, 'hoog', 'Zeer gevoelig voor alle noten'),
(2, 2, 'middel', 'Coeliakie gediagnosticeerd'),
(3, 3, 'laag', 'Lichte lactose-intolerantie'),
(4, 4, 'middel', 'Huiduitslag bij eieren'),
(5, 1, 'hoog', 'EpiPen nodig bij contact');

COMMIT;
