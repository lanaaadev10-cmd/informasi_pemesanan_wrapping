# AGENTS.md — Informasi Pemesanan Wrapping

## Stack
- Laravel 12 + Filament 5 + Spatie Laravel Settings + Spatie Laravel Permission
- Tailwind 3 + Alpine.js + Vite

## Database Architecture

### Two content systems coexist
Content can be stored in **either** the `profil_perusahaans` table (Eloquent `ProfilPerusahaan`) **or** the `settings` table (Spatie Settings classes in `app/Settings/`). **All customer-facing views read only from `settings` via the `$profil` view composer** (`app/Providers/SettingsServiceProvider.php`).

**Watch out for duplicate admin pages** that look similar but save to different tables:
| Menu label | Saves to | Read by views? |
|---|---|---|
| `Edit Tentang Kami` → `TentangKamiResource` | `settings` | ✅ Yes |
| `Edit Beranda` → `BerandaResource` | `settings` | ✅ Yes |

When adding new settings-backed content, ensure the Settings class is listed in `SettingsServiceProvider` so views can access it via `$profil->propertyName`.

### Spatie Settings save quirk
When saving `app(SettingsClass::class)` after setting properties via loop, **must** call `$settings->settingsConfig()->resetDefaultValueLoadedProperties()` before `$settings->save()`. Without this, properties loaded with default (null) values are treated as "missing" and throw `MissingSettings`.

```php
// Correct save pattern (see EditBeranda.php:341, SettingsTableSeeder.php:48)
$settings = app(SomeSettings::class);
foreach ($data as $key => $value) { $settings->{$key} = $value; }
$settings->settingsConfig()->resetDefaultValueLoadedProperties();
$settings->save();
```

### 12 Settings classes in `app/Settings/`
All auto-discovered. Group names match class semantics (e.g., `HomepageSettings::group()` returns `'homepage'`). Stored in `settings` table with `group` column for grouping.

## Admin Panel (Filament 5)
- Resources in `app/Filament/Resources/`, pages in `app/Filament/Resources/*/Pages/`
- Uses Filament 5's `Schema` + `Schemas\Components` (not the older `Forms` API)
- Role-based: `admin` role for admin panel, `user` role for customer dashboard
- Settings-based resources use `DummyModel` (`app/Models/DummyModel.php`) as the Eloquent record
- `mount()` loads settings via `app(SettingsClass::class)->toArray()` merged with defaults

## View System
- `SettingsServiceProvider` registers a `*` view composer that merges all 12 Settings classes into a single `$profil` stdClass
- Every Blade view accesses settings via `$profil->propertyName`
- Layouts: `layouts.tampilan_utama` (public), `layouts.dashboard_customer` (logged-in customer)

## Key Commands
```bash
# Dev servers
php artisan serve            # Laravel backend at localhost:8000
npm run dev                  # Vite HMR at localhost:5173
npm run build                # Production asset build

# Settings
php artisan db:seed --class=SettingsTableSeeder   # Initialize all settings in DB
php artisan optimize:clear                        # Clear cache (cache breaks settings)
php artisan settings:cache                        # Cache discovered settings classes

# Migrations
php artisan migrate

# Tests (Pest)
php artisan test
```

## Routes (public, no auth)
- `/` → Beranda (homepage)
- `/tentang-kami` → Tentang Kami
- `/layanan` → Layanan
- `/galeri-karya` → Galeri
- `/katalog-layanan` → Katalog layanan
- `/profil-perusahaan` → Profil perusahaan
