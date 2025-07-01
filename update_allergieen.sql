-- ===================================
-- UPDATE ALLERGIEËN NAAR GEWENSTE LIJST
-- Bijwerken van de allergie tabel met de juiste allergieën
-- ===================================

USE VoedselbankMaaskantje;

-- Eerst alle allergie_per_persoon relaties verwijderen
DELETE FROM allergie_per_persoon;

-- Alle bestaande allergieën verwijderen
DELETE FROM allergie;

-- Reset auto increment
ALTER TABLE allergie AUTO_INCREMENT = 1;
ALTER TABLE allergie_per_persoon AUTO_INCREMENT = 1;

-- Nieuwe allergieën invoegen
INSERT INTO allergie (id, naam, beschrijving, omschrijving, anafylactisch_risico, ernst_niveau, is_actief, datum_aangemaakt) VALUES
(1, 'Soja', 'Soja-allergie', 'Allergisch voor soja en alle sojaproducten zoals sojamelk, tofu, sojasaus', 'laag', 'middel', 1, CURRENT_TIMESTAMP(6)),
(2, 'Lactose', 'Lactose-intolerantie', 'Kan geen lactose verteren, problemen met melk en zuivelproducten', 'zeerlaag', 'laag', 1, CURRENT_TIMESTAMP(6)),
(3, 'Hazelnoten', 'Hazelnoot allergie', 'Allergisch voor hazelnoten en producten die hazelnoten bevatten', 'redelijk_hoog', 'hoog', 1, CURRENT_TIMESTAMP(6)),
(4, 'Schaaldieren', 'Schaaldieren allergie', 'Allergisch voor schaaldieren zoals garnalen, krab, kreeft, mosselen', 'hoog', 'hoog', 1, CURRENT_TIMESTAMP(6)),
(5, 'Pindas', 'Pinda allergie', 'Allergisch voor pindas en alle pindaproducten zoals pindakaas', 'hoog', 'hoog', 1, CURRENT_TIMESTAMP(6)),
(6, 'Gluten', 'Gluten-intolerantie/Coeliakie', 'Kan geen gluten verdragen uit tarwe, rogge, gerst en haver', 'laag', 'middel', 1, CURRENT_TIMESTAMP(6));

-- Nieuwe allergie_per_persoon relaties invoegen met logische verdeling
INSERT INTO allergie_per_persoon (id, persoon_id, allergie_id, ernst, opmerking, datum_vastgesteld, is_actief, datum_aangemaakt) VALUES
-- Johan van Zevenhuizen (persoon 4) - Soja allergie
(1, 4, 1, 'middel', 'Kan geen sojamilk drinken', '2023-01-15', 1, CURRENT_TIMESTAMP(6)),

-- Sarah den Dolder (persoon 5) - Lactose intolerantie
(2, 5, 2, 'laag', 'Lichte lactose intolerantie', '2022-06-20', 1, CURRENT_TIMESTAMP(6)),

-- Theo van Zevenhuizen (persoon 6, kind) - Hazelnoten allergie
(3, 6, 3, 'hoog', 'Ernstige reactie op hazelnoten', '2020-09-10', 1, CURRENT_TIMESTAMP(6)),

-- Jantien van Zevenhuizen (persoon 7, kind) - Schaaldieren allergie
(4, 7, 4, 'hoog', 'Anafylactische reactie op schaaldieren', '2021-03-05', 1, CURRENT_TIMESTAMP(6)),

-- Arjan Bergkamp (persoon 8) - Pinda allergie
(5, 8, 5, 'hoog', 'Ernstige pinda allergie', '2018-11-12', 1, CURRENT_TIMESTAMP(6)),

-- Janneke Sanders (persoon 9) - Gluten intolerantie
(6, 9, 6, 'middel', 'Coeliakie diagnose', '2019-04-08', 1, CURRENT_TIMESTAMP(6)),

-- Stein Bergkamp (persoon 10, kind) - Lactose intolerantie
(7, 10, 2, 'laag', 'Erfelijke lactose intolerantie', '2015-07-30', 1, CURRENT_TIMESTAMP(6)),

-- Mazin van Vliet (persoon 12) - Pinda allergie
(8, 12, 5, 'hoog', 'Levensbedreigende pinda allergie', '2020-02-14', 1, CURRENT_TIMESTAMP(6)),

-- Selma van de Heuvel (persoon 13) - Hazelnoten allergie
(9, 13, 3, 'middel', 'Matige reactie op hazelnoten', '2021-08-22', 1, CURRENT_TIMESTAMP(6)),

-- Eva Scherder (persoon 14) - Gluten intolerantie
(10, 14, 6, 'hoog', 'Ernstige coeliakie', '2022-01-10', 1, CURRENT_TIMESTAMP(6)),

-- Felicia Scherder (persoon 15, kind) - Schaaldieren allergie
(11, 15, 4, 'middel', 'Allergisch voor garnalen', '2023-05-18', 1, CURRENT_TIMESTAMP(6)),

-- Devin Scherder (persoon 16, baby) - Lactose intolerantie
(12, 16, 2, 'laag', 'Baby lactose intolerantie', '2024-03-01', 1, CURRENT_TIMESTAMP(6)),

-- Frieda de Jong (persoon 17) - Gluten intolerantie EN Pinda allergie (dubbele allergie)
(13, 17, 6, 'hoog', 'Ernstige coeliakie', '2020-09-15', 1, CURRENT_TIMESTAMP(6)),
(14, 17, 5, 'hoog', 'Ook ernstige pinda allergie', '2020-09-15', 1, CURRENT_TIMESTAMP(6)),

-- Simeon de Jong (persoon 18, kind) - Hazelnoten allergie
(15, 18, 3, 'laag', 'Lichte hazelnoot allergie', '2023-02-28', 1, CURRENT_TIMESTAMP(6)),

-- Hanna van der Berg (persoon 19) - Soja allergie
(16, 19, 1, 'middel', 'Soja intolerantie', '2021-12-05', 1, CURRENT_TIMESTAMP(6));

-- Update de product tabel om de allergieën aan te passen
UPDATE product SET soort_allergie = 'Lactose' WHERE id = 5;  -- Kaas
UPDATE product SET soort_allergie = 'Lactose' WHERE id = 7;  -- Melk
UPDATE product SET soort_allergie = 'Soja' WHERE id = 8;     -- Sojamelk
UPDATE product SET soort_allergie = 'Gluten' WHERE id = 9;   -- Brood
UPDATE product SET soort_allergie = 'Hazelnoten,Gluten' WHERE id = 10; -- Hazelnootcake
UPDATE product SET soort_allergie = 'Schaaldieren' WHERE id = 11;      -- Garnalen
UPDATE product SET soort_allergie = 'Pindas' WHERE id = 12;            -- Pindakaas

-- Bevestiging
SELECT 'Allergieën succesvol bijgewerkt naar: Soja, Lactose, Hazelnoten, Schaaldieren, Pindas, Gluten' AS Bericht;

-- Toon de nieuwe allergieën
SELECT * FROM allergie ORDER BY id;

-- Toon aantal allergie registraties per allergie
SELECT 
    a.naam AS allergie,
    COUNT(app.id) AS aantal_personen
FROM allergie a
LEFT JOIN allergie_per_persoon app ON a.id = app.allergie_id AND app.is_actief = 1
GROUP BY a.id, a.naam
ORDER BY aantal_personen DESC;
