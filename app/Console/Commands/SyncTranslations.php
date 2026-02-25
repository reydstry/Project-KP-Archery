<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SyncTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:sync {--from=en : Source locale to sync from} {--to=id : Target locale to sync to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync translation keys from source locale to target locale (creates missing files and keys)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fromLocale = $this->option('from');
        $toLocale = $this->option('to');

        $fromPath = resource_path("lang/{$fromLocale}");
        $toPath = resource_path("lang/{$toLocale}");

        if (!File::exists($fromPath)) {
            $this->error("Source locale '{$fromLocale}' not found.");
            return 1;
        }

        if (!File::exists($toPath)) {
            File::makeDirectory($toPath, 0755, true);
            $this->info("Created directory for locale '{$toLocale}'");
        }

        $this->info("=== Syncing translations from '{$fromLocale}' to '{$toLocale}' ===");
        $this->newLine();

        $sourceFiles = File::files($fromPath);
        $syncCount = 0;

        foreach ($sourceFiles as $file) {
            $fileName = $file->getFilename();
            $targetFile = "{$toPath}/{$fileName}";

            $this->info("Processing: {$fileName}");

            $sourceTranslations = include $file->getPathname();
            $targetTranslations = File::exists($targetFile) ? include $targetFile : [];

            $updated = $this->syncKeys($sourceTranslations, $targetTranslations);

            if ($updated !== $targetTranslations) {
                $this->writeTranslationFile($targetFile, $updated, $file);
                $this->info("  ✓ Updated {$fileName}");
                $syncCount++;
            } else {
                $this->info("  - No changes needed");
            }
        }

        $this->newLine();
        
        if ($syncCount > 0) {
            $this->info("✓ Synced {$syncCount} file(s) successfully!");
            $this->warn("Note: New keys have placeholder values marked with [TODO]. Please translate them.");
        } else {
            $this->info("✓ All files are already in sync!");
        }

        return 0;
    }

    /**
     * Sync keys from source to target, preserving existing translations
     */
    private function syncKeys(array $source, array $target): array
    {
        $synced = $target;

        foreach ($source as $key => $value) {
            if (!array_key_exists($key, $synced)) {
                // Add missing key with placeholder
                if (is_array($value)) {
                    $synced[$key] = $this->syncKeys($value, []);
                } else {
                    $synced[$key] = "[TODO] {$value}";
                }
            } elseif (is_array($value) && is_array($synced[$key])) {
                // Recursively sync nested arrays
                $synced[$key] = $this->syncKeys($value, $synced[$key]);
            }
        }

        return $synced;
    }

    /**
     * Write translation array to file with proper formatting
     */
    private function writeTranslationFile(string $filePath, array $translations, $sourceFile): void
    {
        $content = "<?php\n\nreturn [\n";
        $content .= $this->arrayToString($translations, 1);
        $content .= "];\n";

        File::put($filePath, $content);
    }

    /**
     * Convert array to formatted string
     */
    private function arrayToString(array $array, int $indent = 0): string
    {
        $result = '';
        $indentStr = str_repeat('    ', $indent);

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result .= "{$indentStr}'{$key}' => [\n";
                $result .= $this->arrayToString($value, $indent + 1);
                $result .= "{$indentStr}],\n";
            } else {
                $escapedValue = addslashes($value);
                $result .= "{$indentStr}'{$key}' => '{$escapedValue}',\n";
            }
        }

        return $result;
    }
}
