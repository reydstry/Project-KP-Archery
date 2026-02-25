# Panduan Manajemen Terjemahan / Translation Management Guide

## Konfigurasi Bahasa / Language Configuration

### Bahasa Default / Default Language

Aplikasi ini menggunakan **Bahasa Indonesia** sebagai bahasa default. Konfigurasi ini dapat diubah di:

1. **File .env**

    ```env
    APP_LOCALE=id
    APP_FALLBACK_LOCALE=en
    ```

2. **config/app.php**
    ```php
    'locale' => env('APP_LOCALE', 'id'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
    ```

### Bahasa yang Tersedia / Available Languages

- 🇮🇩 **id** - Bahasa Indonesia (Default)
- 🇬🇧 **en** - English

## Struktur File Terjemahan / Translation File Structure

```
resources/
  lang/
    en/
      ├── about.php
      ├── contact.php
      ├── gallery.php
      ├── home.php
      ├── nav.php
      └── program.php
    id/
      ├── about.php
      ├── contact.php
      ├── gallery.php
      ├── home.php
      ├── nav.php
      └── program.php
```

## Command Artisan untuk Terjemahan / Artisan Commands for Translations

### 1. Check Translation (Cek Terjemahan)

Periksa konsistensi dan kelengkapan file terjemahan:

```bash
# Cek semua bahasa
php artisan translations:check

# Cek bahasa tertentu
php artisan translations:check --locale=id
php artisan translations:check --locale=en
```

**Output:**

- ✓ Menampilkan file yang sudah lengkap
- ✗ Menampilkan key yang hilang (missing keys)
- ⚠ Menampilkan key tambahan yang tidak ada di referensi

### 2. Sync Translation (Sinkronisasi Terjemahan)

Otomatis menambahkan key yang hilang dari bahasa sumber ke bahasa target:

```bash
# Sync dari English ke Indonesia
php artisan translations:sync --from=en --to=id

# Sync dari Indonesia ke English
php artisan translations:sync --from=id --to=en
```

**Catatan:**

- Key baru akan ditambahkan dengan prefix `[TODO]` yang perlu diterjemahkan manual
- Terjemahan yang sudah ada tidak akan ditimpa

## Workflow Terjemahan Otomatis / Automatic Translation Workflow

### Skenario 1: Menambah Terjemahan Baru

1. **Tambahkan key di bahasa referensi (English)**

    ```php
    // resources/lang/en/home.php
    'new_feature' => 'New Feature Description',
    ```

2. **Jalankan sync command**

    ```bash
    php artisan translations:sync --from=en --to=id
    ```

3. **Lengkapi terjemahan di file target**

    ```php
    // resources/lang/id/home.php
    'new_feature' => 'Deskripsi Fitur Baru',  // Ganti [TODO] dengan terjemahan
    ```

4. **Verifikasi dengan check command**
    ```bash
    php artisan translations:check
    ```

### Skenario 2: Pre-Commit Hook (Recommended)

Untuk memastikan terjemahan selalu konsisten sebelum commit, tambahkan hook di `.git/hooks/pre-commit`:

```bash
#!/bin/sh

# Check translations before commit
php artisan translations:check

if [ $? -ne 0 ]; then
    echo "❌ Translation check failed! Please fix translation issues before committing."
    exit 1
fi

echo "✅ Translation check passed!"
exit 0
```

Jadikan executable:

```bash
chmod +x .git/hooks/pre-commit
```

### Skenario 3: CI/CD Integration

Tambahkan di workflow CI/CD (contoh GitHub Actions):

```yaml
name: Translation Check

on: [push, pull_request]

jobs:
    translation-check:
        runs-on: ubuntu-latest

        steps:
            - uses: actions/checkout@v2

            - name: Setup PHP
              uses: shivammathur/setup-php@v2
              with:
                  php-version: "8.2"

            - name: Install Dependencies
              run: composer install

            - name: Check Translations
              run: php artisan translations:check
```

## Best Practices

### 1. Penamaan Key

- Gunakan snake_case untuk key
- Gunakan prefix untuk grouping (contoh: `hero_`, `program_`, `cta_`)
- Buat key yang deskriptif

```php
// ✅ Good
'hero_title' => 'Welcome',
'program_1_feature_1' => 'Feature One',

// ❌ Bad
'title1' => 'Welcome',
'f1' => 'Feature One',
```

### 2. Organisasi File

- Pisahkan terjemahan per halaman atau section
- Gunakan comment untuk memisahkan group

```php
<?php

return [
    // Hero Section
    'hero_title' => 'Welcome',
    'hero_subtitle' => 'Subtitle',

    // Features Section
    'features_title' => 'Features',
    'features_subtitle' => 'Our Features',
];
```

### 3. Penggunaan di Blade

```blade
{{-- Single translation --}}
<h1>{{ __('home.hero_title') }}</h1>

{{-- With parameters --}}
<p>{{ __('home.welcome_message', ['name' => $user->name]) }}</p>

{{-- Choice translations (singular/plural) --}}
<p>{{ trans_choice('home.items_count', $count) }}</p>
```

### 4. Penggunaan di Controller

```php
// Return translated message
return redirect()->back()->with('success', __('messages.update_success'));

// Validation messages
'required' => __('validation.required'),
```

## Troubleshooting

### Issue: Translation key tidak ditemukan

**Solusi:**

1. Jalankan `php artisan translations:check` untuk identifikasi missing keys
2. Jalankan `php artisan translations:sync` untuk auto-generate keys
3. Clear cache: `php artisan config:clear`

### Issue: Perubahan terjemahan tidak muncul

**Solusi:**

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Issue: File terjemahan tidak ter-load

**Solusi:**

1. Pastikan file berada di `resources/lang/{locale}/`
2. Pastikan return statement benar: `return [ ... ];`
3. Check syntax PHP dengan `php -l resources/lang/id/home.php`

## Monitoring & Maintenance

### Weekly Check

```bash
# Cek semua terjemahan setiap minggu
php artisan translations:check
```

### Sebelum Release

```bash
# Pastikan semua terjemahan lengkap
php artisan translations:check

# Sync jika perlu
php artisan translations:sync --from=en --to=id
```

### Review [TODO] Tags

```bash
# Cari semua tag [TODO] yang belum diterjemahkan
grep -r "\[TODO\]" resources/lang/
```

## Referensi

- [Laravel Localization Documentation](https://laravel.com/docs/localization)
- [Laravel Translation Manager Package](https://github.com/barryvdh/laravel-translation-manager)

## Kontak

Jika ada pertanyaan atau masalah terkait terjemahan, silakan hubungi tim development.
