# Dagplanning Voedselbank Maaskantje - Dag 3
**Student:** [Jouw Naam]  
**Datum:** 1 juli 2025  
**Toegewezen Functionaliteit:** Allergieën Module (2 User Stories)

## 🎯 Doelstellingen Dag 3
- Realiseren van Allergieën module met 2 User Stories (CRUD operaties)
- Implementeren van responsive design en MVC architectuur
- Completeren van database setup en documentatie
- Samenwerken in team met Git branching strategy

## ⏰ Tijdplanning

### 09:00 - 09:15 | Stand-up Meeting
- **Wat gedaan gisteren:** Database migraties, modellen en seeders gemaakt
- **Wat doe ik vandaag:** Allergieën functionaliteit implementeren, responsive maken, SQL script aanleveren
- **Blokkades:** Geen blokkades, alle tools zijn beschikbaar

### 09:15 - 10:30 | Git Setup & Branching
- [x] Aanmaken van main_dag03 branch
- [x] Aanmaken van dev_dag03 branch  
- [x] Aanmaken van feature_allergie branch
- [x] Controleren van Git repository structuur

### 10:30 - 12:00 | Database & SQL Script
- [x] Voltooien van complete SQL create script (database_create_script.sql)
- [x] Testen van SQL script uitvoering
- [x] Valideren van foreign key constraints
- [x] Invoegen van testdata conform opgave

### 12:00 - 13:00 | LUNCH PAUZE

### 13:00 - 14:30 | Dashboard & Navigation Ontwikkeling
- [x] Ontwerpen van gezamenlijk dashboard (homepage)
- [x] Implementeren van responsive navigation
- [x] Toevoegen van alle team functionaliteiten in navbar
- [x] Zorgen voor consistente styling (lettertypen, kleuren, lay-out)

### 14:30 - 16:00 | User Stories Implementatie
- [x] **User Story 1:** Overzicht gezinsallergieën met filter functionaliteit
  - [x] Controller methoden (index, filter)
  - [x] View templates met responsive design
  - [x] Data validatie en error handling
- [x] **User Story 2:** Bewerken persoon allergieën
  - [x] Controller methoden (edit, update)
  - [x] Form validatie (client & server side)
  - [x] Success/error feedback aan gebruikers

### 16:00 - 16:30 | Code Kwaliteit & Documentatie
- [x] Code review voor PSR-12 compliance
- [x] Toevoegen van commentaar en documentatie
- [x] Security validatie (CSRF, input sanitization)
- [x] Responsive design testing (mobile, tablet, desktop)

### 16:30 - 17:00 | Git Workflow & Deployment
- [x] Code committen naar feature_allergie branch
- [x] Merge naar dev_dag03 branch
- [x] Maken van screenshots (wireframes, commits)
- [x] Voorbereiden van eindpresentatie

## ✅ Acceptatiecriteria User Stories

### User Story 1: Overzicht Gezinsallergieën
**Als** vrijwilliger van de voedselbank  
**Wil ik** een overzicht kunnen zien van alle gezinnen met hun voedselallergieën  
**Zodat** ik rekening kan houden met allergieën bij het samenstellen van voedselpakketten

**Acceptatiecriteria:**
- [x] Tabel toont alle gezinnen met allergie-informatie
- [x] Filter functionaliteit op specifieke allergieën
- [x] Responsive design voor alle apparaten
- [x] Navigatie naar gezin details voor verdere informatie

### User Story 2: Bewerken Persoon Allergieën
**Als** medewerker van de voedselbank  
**Wil ik** allergieën van personen kunnen bewerken  
**Zodat** de allergie-informatie actueel en correct blijft

**Acceptatiecriteria:**
- [x] Formulier voor het bewerken van allergie-informatie
- [x] Validatie van invoer (client & server side)
- [x] Waarschuwingen bij hoog-risico allergieën
- [x] Success/error feedback na bewerkingen
- [x] Voorkomen van duplicate allergieën per persoon

## 🛠 Technische Eisen Checklist

### Code Kwaliteit
- [x] **Commentaar:** Uitgebreide code documentatie
- [x] **Joins:** Database queries met proper joins
- [x] **Try Catch:** Error handling geïmplementeerd
- [x] **PSR-12:** Code conventie toegepast
- [x] **Stored Procedures:** Niet van toepassing (Laravel migrations)
- [x] **Naamgeving:** Duidelijke functie en variabele namen
- [x] **MVC:** Model-View-Controller architectuur
- [x] **Security:** CSRF tokens, input validatie
- [x] **Validatie:** Client-side en server-side validatie
- [x] **Logging:** Error logging geïmplementeerd
- [x] **Feedback:** User feedback bij alle acties

### Design & UX
- [x] **Responsive:** Mobile-first design
- [x] **Lettertypen:** Consistente typography
- [x] **Lettergrootte:** Accessibility-friendly sizes
- [x] **Kleuren:** Consistent color scheme
- [x] **Lay-out:** Clean en intuitive layout

## 📋 Deliverables

### Bestanden
- [x] `database_create_script.sql` - Complete database setup
- [x] `VoedselbankSql_dag3.sql` - Team SQL script
- [x] `Voedselbankwireframes_dag3.png` - Screenshot wireframes
- [x] `VoedselbankCommits_dag3.png` - Git commits screenshot
- [x] `Voedselbank_dag3.zip` - Complete project zip

### Code Repository
- [x] Feature branch: `feature_allergie`
- [x] Development branch: `dev_dag03` 
- [x] Main branch: `main_dag03`
- [x] Alle code gepusht naar GitHub

## 🎉 Eindresultaat
Volledig werkende Allergieën module met:
- Responsive web interface
- Complete CRUD functionaliteit
- Veilige data handling
- Professionele gebruikersinterface
- Uitgebreide documentatie
- Team-compatible codebase

## 🔍 Test Scenarios
1. **Desktop browsing:** Chrome, Firefox, Safari
2. **Mobile responsive:** iPhone, Android devices  
3. **Tablet compatibility:** iPad, Android tablets
4. **Accessibility:** Keyboard navigation, screen readers
5. **Performance:** Page load times, database queries
6. **Security:** XSS protection, CSRF validation
