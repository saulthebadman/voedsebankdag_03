DROP PROCEDURE IF EXISTS GetGezinAllergieDetails;

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
END;
