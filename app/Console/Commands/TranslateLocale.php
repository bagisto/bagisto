<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslateLocale extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:translate-locale 
                            {--target=vi : Target locale code}
                            {--source=en : Source locale code}
                            {--namespace= : Specific namespace to translate (optional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-translate Webkul package language files to a target locale using Google Translate';

    /**
     * Package namespace mapping.
     *
     * @var array<string, string>
     */
    protected array $namespaceMap = [
        'admin' => 'Admin',
        'shop' => 'Shop',
        'installer' => 'Installer',
        'core' => 'Core',
        'product' => 'Product',
        'customer' => 'Customer',
        'attribute' => 'Attribute',
        'data_transfer' => 'DataTransfer',
        'paypal' => 'Paypal',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $targetLocale = $this->option('target');
        $sourceLocale = $this->option('source');
        $specificNamespace = $this->option('namespace');

        $this->info("Starting translation from {$sourceLocale} to {$targetLocale}...");

        try {
            $translator = new GoogleTranslate;
            $translator->setSource($sourceLocale);
            $translator->setTarget($targetLocale);
        } catch (\Exception $e) {
            $this->error("Failed to initialize Google Translate: {$e->getMessage()}");
            $this->warn('Make sure stichoza/google-translate-php is installed: composer require stichoza/google-translate-php --dev');

            return Command::FAILURE;
        }

        $namespaces = $specificNamespace
            ? [$specificNamespace => $this->namespaceMap[$specificNamespace] ?? null]
            : $this->namespaceMap;

        $namespaces = array_filter($namespaces);

        foreach ($namespaces as $namespace => $packageName) {
            $this->info("\nProcessing namespace: {$namespace} ({$packageName})");

            $langPath = base_path("packages/Webkul/{$packageName}/src/Resources/lang/{$sourceLocale}");

            if (! is_dir($langPath)) {
                $this->warn("  Skipping: {$langPath} does not exist");

                continue;
            }

            $langFiles = glob("{$langPath}/*.php");

            if (empty($langFiles)) {
                $this->warn("  No PHP files found in {$langPath}");

                continue;
            }

            foreach ($langFiles as $sourceFile) {
                $groupName = basename($sourceFile, '.php');
                $this->info("  Translating: {$groupName}.php");

                $sourceArray = require $sourceFile;

                if (! is_array($sourceArray)) {
                    $this->warn('    Skipping: File does not return an array');

                    continue;
                }

                $translatedArray = $this->translateArray($sourceArray, $translator, $namespace, $groupName);

                $targetDir = base_path("lang/vendor/{$namespace}/{$targetLocale}");
                if (! is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                $targetFile = "{$targetDir}/{$groupName}.php";
                $this->writePhpArrayFile($targetFile, $translatedArray);

                $this->info("    ✓ Saved to: {$targetFile}");
            }
        }

        $this->info("\n✓ Translation complete!");

        return Command::SUCCESS;
    }

    /**
     * Recursively translate array values.
     *
     * @param  array<string, mixed>  $array
     * @return array<string, mixed>
     */
    protected function translateArray(array $array, GoogleTranslate $translator, string $namespace, string $group): array
    {
        $translated = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $translated[$key] = $this->translateArray($value, $translator, $namespace, $group);
            } elseif (is_string($value) && ! empty(trim($value))) {
                $translated[$key] = $this->translateString($value, $translator);
            } else {
                $translated[$key] = $value;
            }
        }

        return $translated;
    }

    /**
     * Translate a single string, preserving placeholders.
     */
    protected function translateString(string $text, GoogleTranslate $translator): string
    {
        // Skip URLs, email addresses, and purely technical strings
        if (preg_match('/^https?:\/\//', $text) || filter_var($text, FILTER_VALIDATE_EMAIL)) {
            return $text;
        }

        // Extract placeholders (e.g., :name, :attribute, :current_year)
        preg_match_all('/:[\w]+/', $text, $placeholders);
        $placeholders = $placeholders[0] ?? [];

        // Extract HTML tags
        preg_match_all('/<[^>]+>/', $text, $htmlTags);
        $htmlTags = $htmlTags[0] ?? [];
        $htmlPositions = [];

        foreach ($htmlTags as $tag) {
            $pos = strpos($text, $tag);
            if ($pos !== false) {
                $htmlPositions[] = ['tag' => $tag, 'position' => $pos];
            }
        }

        // Replace placeholders with temporary markers
        $tempText = $text;
        $placeholderMap = [];
        foreach ($placeholders as $index => $placeholder) {
            $marker = "___PLACEHOLDER_{$index}___";
            $placeholderMap[$marker] = $placeholder;
            $tempText = str_replace($placeholder, $marker, $tempText);
        }

        // Replace HTML tags with temporary markers
        $htmlMap = [];
        foreach ($htmlPositions as $index => $htmlData) {
            $marker = "___HTML_{$index}___";
            $htmlMap[$marker] = $htmlData['tag'];
            $tempText = str_replace($htmlData['tag'], $marker, $tempText);
        }

        // Translate the cleaned text
        try {
            // Add small delay to avoid rate limiting
            usleep(100000); // 0.1 second

            $translated = $translator->translate($tempText);

            // Restore placeholders
            foreach ($placeholderMap as $marker => $placeholder) {
                $translated = str_replace($marker, $placeholder, $translated);
            }

            // Restore HTML tags
            foreach ($htmlMap as $marker => $htmlTag) {
                $translated = str_replace($marker, $htmlTag, $translated);
            }

            return $translated;
        } catch (\Exception $e) {
            $this->warn("    Translation failed for: {$text} - {$e->getMessage()}");

            return $text; // Return original on failure
        }
    }

    /**
     * Write PHP array to file with proper formatting.
     *
     * @param  array<string, mixed>  $array
     */
    protected function writePhpArrayFile(string $filePath, array $array): void
    {
        $content = "<?php\n\nreturn [\n";
        $content .= $this->arrayToString($array, 1);
        $content .= "];\n";

        file_put_contents($filePath, $content);
    }

    /**
     * Convert array to formatted string.
     *
     * @param  array<string, mixed>  $array
     */
    protected function arrayToString(array $array, int $indent = 0): string
    {
        $output = '';
        $indentStr = str_repeat('    ', $indent);

        foreach ($array as $key => $value) {
            $keyStr = is_string($key) && preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $key)
                ? $key
                : "'".addslashes($key)."'";

            if (is_array($value)) {
                $output .= "{$indentStr}{$keyStr} => [\n";
                $output .= $this->arrayToString($value, $indent + 1);
                $output .= "{$indentStr}],\n";
            } elseif (is_string($value)) {
                $valueStr = "'".addslashes($value)."'";
                $output .= "{$indentStr}{$keyStr} => {$valueStr},\n";
            } elseif (is_bool($value)) {
                $output .= "{$indentStr}{$keyStr} => ".($value ? 'true' : 'false').",\n";
            } elseif (is_null($value)) {
                $output .= "{$indentStr}{$keyStr} => null,\n";
            } else {
                $output .= "{$indentStr}{$keyStr} => ".var_export($value, true).",\n";
            }
        }

        return $output;
    }
}
