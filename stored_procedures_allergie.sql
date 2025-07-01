-- Stored Procedures voor Voedselbank Allergie Module
-- Deze procedures bieden geoptimaliseerde database operaties

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
        g.adres,
        g.postcode,
        g.woonplaats,
        g.telefoon,
        g.email,
        COUNT(DISTINCT p.id) AS aantal_personen,
        COUNT(DISTINCT app.id) AS aantal_allergie_registraties
    FROM gezin g
    INNER JOIN persoon p ON g.id = p.gezin_id
    INNER JOIN allergie_per_persoon app ON p.id = app.persoon_id
    WHERE g.is_actief = TRUE 
      AND p.is_actief = TRUE
    GROUP BY g.id, g.naam, g.adres, g.postcode, g.woonplaats, g.telefoon, g.email
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
        g.adres,
        g.postcode,
        g.woonplaats,
        a.naam AS allergie_naam,
        COUNT(DISTINCT p.id) AS aantal_personen_met_allergie
    FROM gezin g
    INNER JOIN persoon p ON g.id = p.gezin_id
    INNER JOIN allergie_per_persoon app ON p.id = app.persoon_id
    INNER JOIN allergie a ON app.allergie_id = a.id
    WHERE g.is_actief = TRUE 
      AND p.is_actief = TRUE
      AND a.id = allergie_id
    GROUP BY g.id, g.naam, g.adres, g.postcode, g.woonplaats, a.naam
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
        p.geboortedatum,
        p.geslacht,
        a.id AS allergie_id,
        a.naam AS allergie_naam,
        a.beschrijving AS allergie_beschrijving,
        a.ernst_niveau,
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
    IN persoon_id INT,
    IN oude_allergie_id INT,
    IN nieuwe_allergie_id INT,
    IN ernst_niveau ENUM('laag', 'middel', 'hoog', 'levensgevaarlijk'),
    IN opmerking TEXT,
    OUT success_message VARCHAR(255),
    OUT error_message VARCHAR(255)
)
BEGIN
    DECLARE duplicate_count INT DEFAULT 0;
    DECLARE oude_allergie_naam VARCHAR(255);
    DECLARE nieuwe_allergie_naam VARCHAR(255);
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
    WHERE persoon_id = persoon_id AND allergie_id = nieuwe_allergie_id;

    IF duplicate_count > 0 THEN
        SET error_message = 'Deze persoon heeft deze allergie al.';
        SET success_message = NULL;
        ROLLBACK;
    ELSE
        -- Haal allergienamen op voor logdoeleinden
        SELECT naam INTO oude_allergie_naam FROM allergie WHERE id = oude_allergie_id;
        SELECT naam INTO nieuwe_allergie_naam FROM allergie WHERE id = nieuwe_allergie_id;

        -- Verwijder oude allergie
        DELETE FROM allergie_per_persoon 
        WHERE persoon_id = persoon_id AND allergie_id = oude_allergie_id;

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
            persoon_id,
            nieuwe_allergie_id,
            COALESCE(ernst_niveau, 'middel'),
            CONCAT('Gewijzigd van ', oude_allergie_naam, ' naar ', nieuwe_allergie_naam, '. ', COALESCE(opmerking, '')),
            CURDATE(),
            NOW(),
            NOW()
        );

        SET success_message = CONCAT('Allergie succesvol gewijzigd van "', oude_allergie_naam, '" naar "', nieuwe_allergie_naam, '".');
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
        (SELECT COUNT(DISTINCT gezin_id) FROM persoon p 
         INNER JOIN allergie_per_persoon app ON p.id = app.persoon_id 
         WHERE p.is_actief = TRUE) AS gezinnen_met_allergieen,
        (SELECT COUNT(DISTINCT persoon_id) FROM allergie_per_persoon app
         INNER JOIN persoon p ON app.persoon_id = p.id
         WHERE p.is_actief = TRUE) AS personen_met_allergieen,
        (SELECT COUNT(*) FROM allergie_per_persoon app
         INNER JOIN persoon p ON app.persoon_id = p.id
         WHERE p.is_actief = TRUE) AS totaal_allergie_registraties,
        (SELECT COUNT(*) FROM allergie_per_persoon app
         INNER JOIN persoon p ON app.persoon_id = p.id
         INNER JOIN allergie a ON app.allergie_id = a.id
         WHERE p.is_actief = TRUE AND a.ernst_niveau = 'hoog') AS hoog_risico_allergieen;
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
        a.ernst_niveau,
        COUNT(app.id) AS aantal_gevallen,
        COUNT(DISTINCT app.persoon_id) AS aantal_personen
    FROM allergie a
    INNER JOIN allergie_per_persoon app ON a.id = app.allergie_id
    INNER JOIN persoon p ON app.persoon_id = p.id
    WHERE a.is_actief = TRUE AND p.is_actief = TRUE
    GROUP BY a.id, a.naam, a.beschrijving, a.ernst_niveau
    ORDER BY aantal_gevallen DESC, aantal_personen DESC
    LIMIT limiet;
END //

DELIMITER ;

-- Grant privileges voor stored procedures (indien nodig)
-- GRANT EXECUTE ON PROCEDURE GetGezinnenMetAllergien TO 'voedselbank_user'@'localhost';
-- GRANT EXECUTE ON PROCEDURE FilterGezinnenByAllergie TO 'voedselbank_user'@'localhost';
-- GRANT EXECUTE ON PROCEDURE GetGezinAllergieDetails TO 'voedselbank_user'@'localhost';
-- GRANT EXECUTE ON PROCEDURE UpdatePersoonAllergie TO 'voedselbank_user'@'localhost';
-- GRANT EXECUTE ON PROCEDURE GetAllergieStatistieken TO 'voedselbank_user'@'localhost';
-- GRANT EXECUTE ON PROCEDURE GetPopulaireAllergien TO 'voedselbank_user'@'localhost';
