# Database Specificatie - Allergie Module
**Student:** [Jouw Naam]  
**Module:** Allergieën beheer  
**Datum:** 1 juli 2025

## Tabellen Overzicht

| Tabel Naam | Doel | Type |
|------------|------|------|
| allergie | Opslag van allergie types | Master data |
| allergie_per_persoon | Koppeling personen aan allergieën | Junction table |
| persoon | Personen in het systeem | Master data |
| gezin | Gezinnen informatie | Master data |

## Tabel Specificaties

### 1. Tabel: `allergie`
**Primaire Sleutel:** `id`

| Kolom | Type | NULL | Default | Beschrijving |
|-------|------|------|---------|--------------|
| id | INT | NO | AUTO_INCREMENT | Unieke identifier |
| naam | VARCHAR(100) | NO | - | Naam van de allergie |
| omschrijving | TEXT | YES | NULL | Uitgebreide beschrijving |
| anafylactisch_risico | ENUM('zeerlaag','laag','redelijk_hoog','hoog') | NO | 'laag' | Risico niveau |
| is_actief | BIT(1) | NO | 1 | Record actief status |
| opmerking | VARCHAR(255) | YES | NULL | Algemene opmerkingen |
| datum_aangemaakt | DATETIME(6) | NO | CURRENT_TIMESTAMP(6) | Aanmaakdatum |
| datum_gewijzigd | DATETIME(6) | NO | CURRENT_TIMESTAMP(6) ON UPDATE | Wijzigingsdatum |

### 2. Tabel: `allergie_per_persoon`
**Primaire Sleutel:** `id`  
**Unieke Index:** `persoon_id + allergie_id` (voorkomt duplicaten)

| Kolom | Type | NULL | Default | Beschrijving |
|-------|------|------|---------|--------------|
| id | INT | NO | AUTO_INCREMENT | Unieke identifier |
| persoon_id | INT | NO | - | **FK** naar persoon.id |
| allergie_id | INT | NO | - | **FK** naar allergie.id |
| is_actief | BIT(1) | NO | 1 | Record actief status |
| opmerking | VARCHAR(255) | YES | NULL | Specifieke opmerkingen |
| datum_aangemaakt | DATETIME(6) | NO | CURRENT_TIMESTAMP(6) | Aanmaakdatum |
| datum_gewijzigd | DATETIME(6) | NO | CURRENT_TIMESTAMP(6) ON UPDATE | Wijzigingsdatum |

### 3. Tabel: `persoon` (bestaand)
**Primaire Sleutel:** `id`

| Kolom | Type | NULL | Default | Beschrijving |
|-------|------|------|---------|--------------|
| id | INT | NO | AUTO_INCREMENT | Unieke identifier |
| gezin_id | INT | YES | NULL | **FK** naar gezin.id |
| voornaam | VARCHAR(100) | NO | - | Voornaam |
| tussenvoegsel | VARCHAR(20) | YES | NULL | Tussenvoegsel |
| achternaam | VARCHAR(100) | NO | - | Achternaam |
| geboortedatum | DATE | NO | - | Geboortedatum |
| type_persoon | ENUM('Klant','Manager','Medewerker','Vrijwilliger') | NO | 'Klant' | Type persoon |
| is_vertegenwoordiger | BIT(1) | NO | 0 | Gezinsvertegenwoordiger |
| is_actief | BIT(1) | NO | 1 | Record actief status |
| opmerking | VARCHAR(255) | YES | NULL | Algemene opmerkingen |
| datum_aangemaakt | DATETIME(6) | NO | CURRENT_TIMESTAMP(6) | Aanmaakdatum |
| datum_gewijzigd | DATETIME(6) | NO | CURRENT_TIMESTAMP(6) ON UPDATE | Wijzigingsdatum |

### 4. Tabel: `gezin` (bestaand)
**Primaire Sleutel:** `id`

| Kolom | Type | NULL | Default | Beschrijving |
|-------|------|------|---------|--------------|
| id | INT | NO | AUTO_INCREMENT | Unieke identifier |
| naam | VARCHAR(100) | NO | - | Gezinsnaam |
| code | VARCHAR(20) | NO | - | Unieke gezinscode |
| omschrijving | VARCHAR(255) | YES | NULL | Gezin omschrijving |
| aantal_volwassenen | INT | NO | 0 | Aantal volwassenen |
| aantal_kinderen | INT | NO | 0 | Aantal kinderen |
| aantal_babys | INT | NO | 0 | Aantal baby's |
| totaal_aantal_personen | INT | NO | 0 | Totaal personen |
| is_actief | BIT(1) | NO | 1 | Record actief status |
| opmerking | VARCHAR(255) | YES | NULL | Algemene opmerkingen |
| datum_aangemaakt | DATETIME(6) | NO | CURRENT_TIMESTAMP(6) | Aanmaakdatum |
| datum_gewijzigd | DATETIME(6) | NO | CURRENT_TIMESTAMP(6) ON UPDATE | Wijzigingsdatum |

## Foreign Key Constraints

| Constraint Naam | Van Tabel | Van Kolom | Naar Tabel | Naar Kolom | On Delete | On Update |
|-----------------|-----------|-----------|------------|------------|-----------|-----------|
| fk_allergie_per_persoon_persoon | allergie_per_persoon | persoon_id | persoon | id | CASCADE | CASCADE |
| fk_allergie_per_persoon_allergie | allergie_per_persoon | allergie_id | allergie | id | CASCADE | CASCADE |
| fk_persoon_gezin | persoon | gezin_id | gezin | id | SET NULL | CASCADE |

## Indexes

| Index Naam | Tabel | Kolommen | Type |
|------------|-------|----------|------|
| idx_allergie_naam | allergie | naam | UNIQUE |
| idx_allergie_per_persoon_unique | allergie_per_persoon | persoon_id, allergie_id | UNIQUE |
| idx_persoon_gezin | persoon | gezin_id | INDEX |
| idx_persoon_naam | persoon | achternaam, voornaam | INDEX |
| idx_gezin_code | gezin | code | UNIQUE |

## Business Rules

1. **Allergie per persoon**: Een persoon kan dezelfde allergie niet twee keer hebben
2. **Anafylactisch risico**: Moet een van de gedefinieerde waarden zijn
3. **Gezin vertegenwoordiger**: Per gezin maximaal één vertegenwoordiger
4. **Actieve records**: Alleen actieve records worden getoond in de applicatie
5. **Datum tracking**: Alle wijzigingen worden automatisch gelogd

## Gebruikte Technologieën

- **Database:** MySQL 8.0+
- **Framework:** Laravel 11
- **ORM:** Eloquent
- **Migraties:** Laravel Migrations
- **Seeders:** Laravel Database Seeders
