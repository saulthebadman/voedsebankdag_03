-- ===================================
-- UPDATE SCRIPT VOOR ALLERGIEËN DATA
-- ===================================

USE VoedselbankMaaskantje;

-- Eerst alle bestaande allergie relaties verwijderen
DELETE FROM allergie_per_persoon;
DELETE FROM allergie;

-- Reset auto increment
ALTER TABLE allergie AUTO_INCREMENT = 1;
ALTER TABLE allergie_per_persoon AUTO_INCREMENT = 1;

-- Nieuwe allergieën invoegen in de juiste volgorde
INSERT INTO allergie (id, naam, omschrijving, anafylactisch_risico, is_actief, datum_aangemaakt) VALUES
(1, 'Soja', 'Allergisch voor soja en sojaproducten', 'laag', 1, CURRENT_TIMESTAMP(6)),
(2, 'Lactose', 'Allergisch voor lactose en zuivelproducten', 'zeerlaag', 1, CURRENT_TIMESTAMP(6)),
(3, 'Hazelnoten', 'Allergisch voor hazelnoten', 'redelijk_hoog', 1, CURRENT_TIMESTAMP(6)),
(4, 'Schaaldieren', 'Allergisch voor schaaldieren zoals garnalen, krab, kreeft', 'hoog', 1, CURRENT_TIMESTAMP(6)),
(5, 'Pindas', 'Allergisch voor pindas en pindaproducten', 'hoog', 1, CURRENT_TIMESTAMP(6)),
(6, 'Gluten', 'Allergisch voor gluten in granen', 'laag', 1, CURRENT_TIMESTAMP(6));

-- Nieuwe allergie per persoon relaties
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

-- Update product allergieën om overeen te komen met nieuwe allergie lijst
UPDATE product SET soort_allergie = 'Soja' WHERE naam = 'Sojamelk';
UPDATE product SET soort_allergie = 'Lactose' WHERE naam IN ('Kaas', 'Melk');
UPDATE product SET soort_allergie = 'Gluten' WHERE naam = 'Brood';
UPDATE product SET soort_allergie = 'Hazelnoten,Gluten' WHERE naam = 'Hazelnootcake';
UPDATE product SET soort_allergie = 'Schaaldieren' WHERE naam = 'Garnalen';
UPDATE product SET soort_allergie = 'Pindas' WHERE naam = 'Pindakaas';

SELECT 'Allergieën data succesvol bijgewerkt!' AS Message;
