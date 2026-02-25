<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CheckTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:check {--locale= : Check specific locale (default: all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for missing or inconsistent translations across language files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $langPath = resource_path('lang');
        $locales = ['en', 'id'];
        $specificLocale = $this->option('locale');

        if ($specificLocale) {
            if (!in_array($specificLocale, $locales)) {
                $this->error("Locale '{$specificLocale}' not found.");
                return 1;
            }
            $locales = [$specificLocale];
        }

        $this->info('=== Checking Translation Files ===');
        $this->newLine();

        // Get all translation files from the first locale as reference
        $referenceLocale = 'en';
        $referenceFiles = File::files(resource_path("lang/{$referenceLocale}"));
        
        $hasIssues = false;

        foreach ($referenceFiles as $file) {
            $fileName = $file->getFilename();
            $this->info("Checking file: {$fileName}");
            
            // Load reference translations
            $referenceTranslations = include $file->getPathname();
            
            // Check each locale
            foreach ($locales as $locale) {
                if ($locale === $referenceLocale) {
                    continue;
                }

                $localeFile = resource_path("lang/{$locale}/{$fileName}");
                
                if (!File::exists($localeFile)) {
                    $this->warn("  ⚠ Missing file for locale '{$locale}': {$fileName}");
                    $hasIssues = true;
                    continue;
                }

                $localeTranslations = include $localeFile;
                
                // Check for missing keys
                $missingKeys = $this->findMissingKeys($referenceTranslations, $localeTranslations);
                $extraKeys = $this->findMissingKeys($localeTranslations, $referenceTranslations);

                if (!empty($missingKeys)) {
                    $this->error("  ✗ Missing keys in '{$locale}':");
                    foreach ($missingKeys as $key) {
                        $this->line("    - {$key}");
                    }
                    $hasIssues = true;
                }

                if (!empty($extraKeys)) {
                    $this->warn("  ⚠ Extra keys in '{$locale}' (not in reference):");
                    foreach ($extraKeys as $key) {
                        $this->line("    - {$key}");
                    }
                    $hasIssues = true;
                }

                if (empty($missingKeys) && empty($extraKeys)) {
                    $this->info("  ✓ '{$locale}' is complete");
                }
            }
            
            $this->newLine();
        }

        if (!$hasIssues) {
            $this->info('✓ All translation files are consistent and complete!');
            return 0;
        }

        $this->warn('Please review and fix the issues above.');
        return 1;
    }

    /**
     * Find missing keys in target array compared to source
     */
    private function findMissingKeys(array $source, array $target, string $prefix = ''): array
    {
        $missing = [];

        foreach ($source as $key => $value) {
            $fullKey = $prefix ? "{$prefix}.{$key}" : $key;

            if (!array_key_exists($key, $target)) {
                $missing[] = $fullKey;
            } elseif (is_array($value) && is_array($target[$key])) {
                $nestedMissing = $this->findMissingKeys($value, $target[$key], $fullKey);
                $missing = array_merge($missing, $nestedMissing);
            }
        }

        return $missing;
    }
}
