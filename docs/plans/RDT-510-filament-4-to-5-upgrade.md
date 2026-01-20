# Plan RDT-510 – Filament 4 → 5 Upgrade

**Status:** COMPLETED (2026-01-20)

## Meta

- **Ticket**: RDT-510  
- **Repo**: `saade/filament-fullcalendar` (package)  
- **Scope type**: COMPLEX (framework+frontend upgrade, meerdere tools)

## Context & Doel

- Huidige situatie:
  - `php`: `^8.2` (OK voor Filament v5).
  - `filament/filament`: `^4.0`.
  - `illuminate/contracts`: `^10.0|^11.0|^12.0` (Laravel 11.28+ valt hierbinnen).
  - `tailwindcss`: `^4.1.12` + `@tailwindcss/cli` `^4.1.12` (Tailwind v4 is al in gebruik).
  - Custom JS: `resources/js/components/filament-fullcalendar.js` + bundel in `resources/dist/…`.
- Doel:
  - Filament upgraden naar **v5** (incl. Livewire v4 support).
  - Compatibiliteit met Laravel **≥ 11.28** waarborgen.
  - Tailwind v4 setup bevestigen/optimaliseren.
  - Custom JS/Livewire-interop valideren voor Livewire v4.

## Scope

### In scope

- Composer-upgrade naar `filament/filament:^5.0` via officieel upgrade-script.
- Controleren dat `illuminate/*`-constraints Laravel 11.28+ ondersteunen.
- Bevestigen/aanpassen Tailwind v4 setup (CLI/config/CSS).
- Review/aanpassing van `resources/js/components/filament-fullcalendar.js` voor Livewire v4.
- Testen: CRUD-actions, event interactions, modal behavior in Filament context.
- Composer audit + lint/tests (Pest, PHPStan/Pint waar geconfigureerd).

### Out of scope

- Wijzigingen in consumer-projecten van dit package.
- Grote refactors buiten wat strikt nodig is voor compatibiliteit.
- UI/UX redesign of nieuwe features.

## Risico’s & mitigatie

- **Filament/Livewire breaking behavior**  
  → Mitigatie: gerichte tests op actions, kalenderinteracties en modals.

- **Tailwind v4 gedrag vs bestaande CSS**  
  → Mitigatie: Vite-build + visuele sanity-check rondom kalendercomponent.

- **Composer dependency conflicts bij upgrade**  
  → Mitigatie: stapsgewijze composer-commands en duidelijke rollback-strategie.

- **Documentatie STOP-condities**  
  → Plan en ADR's worden in `docs/` vastgelegd zodat PLAN-FIRST geborgd is.

## Implementation Plan

### Fase 1 – Pre-checks & branch

1. **Branching**
   - Vanuit huidige `4.x`-basis:
     - `git checkout 4.x` (of huidige stabiele basisbranch).
     - `git checkout -b feature/RDT-510`.
   - Geen commits op `4.x` tijdens de upgrade.

2. **Versie-audit**
   - Bevestigen:
     - `composer.json`: PHP, Filament, illuminate, package-tools.
     - `package.json`: Tailwind v4, Vite, overige tooling.
   - Resultaten kort vastleggen in dit plan of een aparte ADR.

### Fase 2 – Filament v5 upgrade

1. **Automated upgrade tool installeren**  
   - `composer require filament/upgrade:"^5.0" -W --dev`

2. **Upgrade uitvoeren**  
   - `vendor/bin/filament-v5`  
   - Alle voorgestelde commando's uitvoeren wanneer veilig, o.a.:
     - `composer require filament/filament:"^5.0" -W --no-update`
     - `composer update`

3. **Opschonen**  
   - `composer remove filament/upgrade --dev`

4. **Composer.json validate**
   - `filament/filament`: `^5.0`.
   - `illuminate/contracts`: blijft `^10.0|^11.0|^12.0`.

5. **Composer audit**
   - `composer audit`.
   - Bij vulnerabilities → STOP-conditie, rapporteren + voorstel (bijv. afhankelijkheden upgraden).

### Fase 3 – Tailwind v4 controle en build

1. **Tailwind v4 setup valideren**
   - Inspecteer Tailwind entry (CLI-gebruik, CSS imports, eventuele config in `resources/css` of JS).
   - Indien nodig, alignen met Tailwind v4 syntax (bijv. `@tailwind`-directives, minimale config).

2. **Build**
   - `npm install`.
   - `npm run build`.
   - Falen van build = STOP tot opgelost.

### Fase 4 – Livewire v4 & custom JS

1. **Analyse custom JS**
   - `resources/js/components/filament-fullcalendar.js` controleren op:
     - Livewire event listeners / hooks.
     - Interactie met Filament actions / modals.

2. **Aanpassingen**
   - Updaten naar Livewire v4 patterns volgens officiële guide (events, refresh, entangle, modals).

3. **Rebuild**
   - Vite build opnieuw draaien na JS-wijzigingen.

### Fase 5 – Testen & Validatie

1. **Automated**
   - `composer test` (Pest).
   - Linting (Pint, PHPStan/Larastan indien geconfigureerd).

2. **Handmatig / scenario’s**
   - **CRUD-actions** via Filament-resources die deze kalender gebruiken.
   - **Event interactions**:
     - Event klik.
     - Event drag/resize (indien ondersteund).
   - **Modals**:
     - Open/close.
     - Validatie.
     - Interactie met kalender na sluiten (herladen, state synchroon).

3. **Rollback-plan**
   - Als upgrade faalt of incompatibel blijkt:
     - Terug naar `4.x` branch.
     - Branch `feature/RDT-510` behouden voor analyse, maar niet mergen.
     - Notitie in docs/JIRA waarom rollback.

## Acceptance Criteria

1. **Framework-compatibiliteit**
   - `composer.json` bevat `filament/filament:^5.0`.
   - `composer update` draait succesvol.
   - `composer audit` toont geen high/critical vulnerabilities gerelateerd aan de upgrade.
   - Package blijft compatibel met Laravel `11.28+` (constraints laten dit toe, tests slagen).

2. **Build & frontend**
   - `npm run build` slaagt zonder errors.
   - Tailwind v4 setup is consistent (geen runtime errors uit Tailwind/CLI, styles van de kalender werken visueel correct in een testomgeving).

3. **Functioneel gedrag**
   - CRUD-actions gekoppeld aan de fullcalendar-component werken zoals vóór de upgrade.
   - Event interactions (click/drag/resize voor zover ondersteund) functioneren zonder JS- of Livewire-fouten in de console.
   - Modals die via de kalender geopend worden werken inclusief validatie en sluiten correct zonder inconsistentie in de kalenderweergave.

4. **Codekwaliteit**
   - Alle relevante tests (Pest) zijn groen.
   - Linting (Pint/larastan/phpstan) draait zonder nieuwe errors/warnings gerelateerd aan de upgrade.
