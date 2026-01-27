<?php

declare(strict_types=1);

namespace Webkul\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class TranslationsChecker extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'bagisto:translations:check
                            {--locale= : Check only a specific locale against EN.}
                            {--package= : Check only a specific package.}
                            {--details : Show detailed error information.}';

    /**
     * The console command description.
     */
    protected $description = 'Check translation files consistency across all packages (EN is canonical).';

    /**
     * Base locale for comparison.
     */
    protected const BASE_LOCALE = 'en';

    /**
     * Maximum items to display in detailed output.
     */
    protected const MAX_DISPLAY_ITEMS = 10;

    /**
     * Package directories relative to base path.
     */
    protected const PACKAGE_DIRECTORIES = [
        'packages/Webkul',
    ];

    /**
     * Root lang folder relative to base path.
     */
    protected const ROOT_LANG_DIRECTORY = 'lang';

    /**
     * Possible lang folder paths within a package (in priority order).
     */
    protected const PACKAGE_LANG_PATHS = [
        '/src/Resources/lang',
        '/resources/lang',
    ];

    /**
     * Supported locales that must exist in all packages.
     */
    protected const SUPPORTED_LOCALES = [
        'ar',
        'bn',
        'ca',
        'de',
        'en',
        'es',
        'fa',
        'fr',
        'he',
        'hi_IN',
        'id',
        'it',
        'ja',
        'nl',
        'pl',
        'pt_BR',
        'ru',
        'sin',
        'tr',
        'uk',
        'zh_CN',
    ];

    /**
     * Track if errors occurred.
     */
    protected bool $hasError = false;

    /**
     * Collection of errors for detailed output.
     */
    protected Collection $errors;

    /**
     * Results for table display.
     */
    protected Collection $results;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->errors = collect();

        $this->results = collect();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $targetLocale = $this->option('locale');

        $targetPackage = $this->option('package');

        $showDetails = $this->option('details');

        $this->displayHeader($targetLocale, $targetPackage);

        // Check root lang folder first (unless a specific package is requested)
        if (! $targetPackage) {
            $this->processLangFolder('Root', base_path(self::ROOT_LANG_DIRECTORY), $targetLocale);
        }

        // Check package lang folders
        $this->processPackages($targetPackage, $targetLocale);

        $this->displayResults($showDetails);

        return $this->hasError ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Display the command header.
     */
    protected function displayHeader(?string $targetLocale, ?string $targetPackage): void
    {
        $this->newLine();

        $this->info('🔍 Bagisto Translations Checker');

        $this->line('   Canonical Locale: <fg=cyan>'.Str::upper(self::BASE_LOCALE).'</>');

        if ($targetLocale) {
            $this->line("   Filter Locale: <fg=yellow>{$targetLocale}</>");
        }

        if ($targetPackage) {
            $this->line("   Filter Package: <fg=yellow>{$targetPackage}</>");
        }

        $this->newLine();
    }

    /**
     * Process all packages for translations checking.
     */
    protected function processPackages(?string $targetPackage, ?string $targetLocale): void
    {
        collect(self::PACKAGE_DIRECTORIES)
            ->map(fn ($dir) => base_path($dir))
            ->filter(fn ($path) => File::isDirectory($path))
            ->flatMap(fn ($basePath) => File::directories($basePath))
            ->when($targetPackage, fn ($collection) => $collection->filter(
                fn ($path) => Str::lower(basename($path)) === Str::lower($targetPackage)
            ))
            ->each(function ($packageDir) use ($targetLocale) {
                $packageName = basename($packageDir);

                $langPath = collect(self::PACKAGE_LANG_PATHS)
                    ->map(fn ($path) => $packageDir.$path)
                    ->first(fn ($path) => File::isDirectory($path));

                if ($langPath) {
                    $this->processLangFolder($packageName, $langPath, $targetLocale);
                }
            });
    }

    /**
     * Process a lang folder and collect results.
     */
    protected function processLangFolder(string $name, string $langRoot, ?string $targetLocale): void
    {
        $enPath = $langRoot.'/'.self::BASE_LOCALE;

        if (! File::isDirectory($enPath)) {
            return;
        }

        $enFiles = $this->getPhpFiles($enPath);

        if ($enFiles->isEmpty()) {
            return;
        }

        $enFilesRel = $enFiles->map(fn ($f) => Str::after($f, $enPath.'/'));

        // Get existing locales in this lang folder
        $existingLocales = collect(File::directories($langRoot))
            ->map(fn ($d) => basename($d))
            ->sort()
            ->values();

        // Check for missing supported locales
        $supportedLocales = collect(self::SUPPORTED_LOCALES);

        $missingLocales = $supportedLocales
            ->reject(fn ($locale) => $existingLocales->contains($locale))
            ->when($targetLocale, fn ($collection) => $collection->filter(
                fn ($l) => Str::lower($l) === Str::lower($targetLocale)
            ))
            ->sort()
            ->values();

        // Report missing locales as failures
        $missingLocales->each(function ($locale) use ($name) {
            $this->results->push([
                'package' => $name,
                'locale' => $locale,
                'status' => 'fail',
                'issues' => 'Locale folder missing',
            ]);

            $this->errors->push([
                'package' => $name,
                'locale' => $locale,
                'type' => 'missing_locale',
                'message' => "Locale folder '{$locale}' does not exist",
            ]);

            $this->hasError = true;
        });

        // Get other locales to check (excluding base locale and filtered by target)
        $locales = $existingLocales
            ->reject(fn ($d) => $d === self::BASE_LOCALE)
            ->when($targetLocale, fn ($collection) => $collection->filter(
                fn ($l) => Str::lower($l) === Str::lower($targetLocale)
            ))
            ->sort()
            ->values();

        if ($locales->isEmpty() && $missingLocales->isEmpty()) {
            return;
        }

        $locales->each(function ($locale) use ($name, $langRoot, $enPath, $enFilesRel) {
            $result = $this->checkLocale($name, $langRoot, $enPath, $enFilesRel, $locale);
            $this->results->push($result);

            if ($result['status'] !== 'pass') {
                $this->hasError = true;
            }
        });
    }

    /**
     * Check a specific locale against EN and return results.
     */
    protected function checkLocale(
        string $packageName,
        string $langRoot,
        string $enPath,
        Collection $enFilesRel,
        string $locale
    ): array {
        $localePath = "{$langRoot}/{$locale}";

        $issues = [];

        // Check for missing files
        $missingFiles = $enFilesRel->filter(
            fn ($relFile) => ! File::exists("{$localePath}/{$relFile}")
        )->values();

        if ($missingFiles->isNotEmpty()) {
            $issues['missing_files'] = $missingFiles->all();

            $this->errors->push([
                'package' => $packageName,
                'locale' => $locale,
                'type' => 'missing_files',
                'files' => $missingFiles->all(),
            ]);
        }

        // Check for extra files
        $localeFiles = $this->getPhpFiles($localePath);

        $localeFilesRel = $localeFiles->map(fn ($f) => Str::after($f, $localePath.'/'));

        $extraFiles = $localeFilesRel->diff($enFilesRel)->values();

        if ($extraFiles->isNotEmpty()) {
            $issues['extra_files'] = $extraFiles->all();

            $this->errors->push([
                'package' => $packageName,
                'locale' => $locale,
                'type' => 'extra_files',
                'files' => $extraFiles->all(),
            ]);
        }

        // Check keys and structure
        $missingKeys = [];
        $extraKeys = [];
        $structureIssues = [];

        $enFilesRel->each(function ($relFile) use ($enPath, $localePath, &$missingKeys, &$extraKeys, &$structureIssues, &$issues) {
            $enFile = "{$enPath}/{$relFile}";

            $localeFile = "{$localePath}/{$relFile}";

            if (! File::exists($localeFile)) {
                return;
            }

            try {
                $locArray = $this->parseTranslationFile($localeFile);

                $enKeysWithLines = $this->flattenArrayWithLines($enFile);

                $locKeys = collect($this->flattenArray($locArray))->keys();

                $enKeys = collect($enKeysWithLines)->keys();

                $fileMissing = $enKeys->diff($locKeys);

                $fileExtra = $locKeys->diff($enKeys);

                if ($fileMissing->isNotEmpty()) {
                    $missingWithLines = $fileMissing->mapWithKeys(
                        fn ($key) => [$key => $enKeysWithLines[$key] ?? null]
                    )->all();

                    $missingKeys[$relFile] = $missingWithLines;
                }

                if ($fileExtra->isNotEmpty()) {
                    $locKeysWithLines = $this->flattenArrayWithLines($localeFile);

                    $extraWithLines = $fileExtra->mapWithKeys(
                        fn ($key) => [$key => $locKeysWithLines[$key] ?? null]
                    )->all();

                    $extraKeys[$relFile] = $extraWithLines;
                }

                // Always check structure (line-by-line comparison)
                $structureMismatches = $this->getStructureMismatches($enFile, $localeFile);

                if (! empty($structureMismatches)) {
                    $structureIssues[$relFile] = $structureMismatches;
                }
            } catch (Throwable) {
                $issues['parse_errors'][] = $relFile;
            }
        });

        if (! empty($missingKeys)) {
            $issues['missing_keys'] = $missingKeys;

            $this->errors->push([
                'package' => $packageName,
                'locale' => $locale,
                'type' => 'missing_keys',
                'data' => $missingKeys,
            ]);
        }

        if (! empty($extraKeys)) {
            $issues['extra_keys'] = $extraKeys;

            $this->errors->push([
                'package' => $packageName,
                'locale' => $locale,
                'type' => 'extra_keys',
                'data' => $extraKeys,
            ]);
        }

        if (! empty($structureIssues)) {
            $issues['structure_issues'] = $structureIssues;

            $this->errors->push([
                'package' => $packageName,
                'locale' => $locale,
                'type' => 'structure_issues',
                'data' => $structureIssues,
            ]);
        }

        return [
            'package' => $packageName,
            'locale' => $locale,
            'status' => empty($issues) ? 'pass' : 'fail',
            'issues' => $this->buildIssueSummary($issues),
        ];
    }

    /**
     * Build a summary string for issues.
     */
    protected function buildIssueSummary(array $issues): string
    {
        $summary = collect();

        if (! empty($issues['missing_files'])) {
            $summary->push(count($issues['missing_files']).' missing file(s)');
        }

        if (! empty($issues['extra_files'])) {
            $summary->push(count($issues['extra_files']).' extra file(s)');
        }

        if (! empty($issues['missing_keys'])) {
            $count = collect($issues['missing_keys'])->map(fn ($keys) => count($keys))->sum();

            $summary->push("{$count} missing key(s)");
        }

        if (! empty($issues['extra_keys'])) {
            $count = collect($issues['extra_keys'])->map(fn ($keys) => count($keys))->sum();

            $summary->push("{$count} extra key(s)");
        }

        if (! empty($issues['structure_issues'])) {
            $fileCount = count($issues['structure_issues']);

            $summary->push("{$fileCount} file(s) with structure issue(s)");
        }

        if (! empty($issues['parse_errors'])) {
            $summary->push(count($issues['parse_errors']).' parse error(s)');
        }

        return $summary->implode(', ') ?: '-';
    }

    /**
     * Display results in a clean table format.
     */
    protected function displayResults(bool $showDetails): void
    {
        if ($this->results->isEmpty()) {
            $this->warn('No translations found to check.');

            return;
        }

        // Group results by package
        $grouped = $this->results->groupBy('package');

        $passCount = 0;

        $failCount = 0;

        $grouped->each(function ($localeResults, $package) use (&$passCount, &$failCount) {
            $this->info("📦 {$package}");

            $tableRows = $localeResults->map(function ($result) use (&$passCount, &$failCount) {
                $isPassing = $result['status'] === 'pass';

                $statusIcon = $isPassing ? '<fg=green>✓</>' : '<fg=red>✗</>';

                $issueText = $isPassing ? '<fg=green>OK</>' : "<fg=red>{$result['issues']}</>";

                $isPassing ? $passCount++ : $failCount++;

                return [$result['locale'], $statusIcon, $issueText];
            })->all();

            $this->table(['Locale', 'Status', 'Issues'], $tableRows);

            $this->newLine();
        });

        // Summary
        $this->displaySummary($passCount, $failCount, $showDetails);
    }

    /**
     * Display the summary section.
     */
    protected function displaySummary(int $passCount, int $failCount, bool $showDetails): void
    {
        $this->line(str_repeat('─', 60));

        $total = $passCount + $failCount;

        $this->line("📊 <fg=white;options=bold>Summary:</> {$total} locale(s) checked");

        $this->line("   <fg=green>✓ Passed:</> {$passCount}");

        $this->line("   <fg=red>✗ Failed:</> {$failCount}");

        $this->line(str_repeat('─', 60));

        if ($this->hasError) {
            $this->newLine();

            $this->error('Translations check failed!');

            if ($showDetails) {
                $this->displayDetailedErrors();
            } else {
                $this->newLine();

                $this->line('<fg=yellow>💡 Use --details flag to see specific missing/extra keys.</>');
            }
        } else {
            $this->newLine();

            $this->info('✅ All translations are synchronized with EN!');
        }

        $this->newLine();
    }

    /**
     * Display detailed error information.
     */
    protected function displayDetailedErrors(): void
    {
        $this->newLine();

        $this->line('<fg=yellow;options=bold>📋 Detailed Issues:</>');

        $this->newLine();

        // Group errors by package and locale
        $grouped = $this->errors->groupBy(fn ($error) => "{$error['package']}:{$error['locale']}");

        $grouped->each(function ($errors, $key) {
            [$package, $locale] = explode(':', $key);

            $this->line("<fg=cyan>[{$package}]</> <fg=yellow>{$locale}</>");

            $errors->each(fn ($error) => $this->displayError($error));

            $this->newLine();
        });
    }

    /**
     * Display a single error based on its type.
     */
    protected function displayError(array $error): void
    {
        match ($error['type']) {
            'missing_locale' => $this->displayMissingLocale($error['message']),
            'missing_files' => $this->displayMissingFiles($error['files']),
            'extra_files' => $this->displayExtraFiles($error['files']),
            'missing_keys' => $this->displayMissingKeys($error['data']),
            'extra_keys' => $this->displayExtraKeys($error['data']),
            'structure_issues' => $this->displayStructureIssues($error['data']),
            default => null,
        };
    }

    /**
     * Display missing locale error.
     */
    protected function displayMissingLocale(string $message): void
    {
        $this->line("  <fg=red>⚠ {$message}</>");

        $this->newLine();
    }

    /**
     * Display missing files error.
     */
    protected function displayMissingFiles(array $files): void
    {
        $this->line('  <fg=red>Missing files:</>');

        collect($files)->each(fn ($file) => $this->line("    - {$file}"));

        $this->newLine();
    }

    /**
     * Display extra files error.
     */
    protected function displayExtraFiles(array $files): void
    {
        $this->line('  <fg=magenta>Extra files (not in EN):</>');

        collect($files)->each(fn ($file) => $this->line("    - {$file}"));

        $this->newLine();
    }

    /**
     * Display missing keys error.
     */
    protected function displayMissingKeys(array $data): void
    {
        $this->line('  <fg=red>Missing keys:</>');

        collect($data)->each(function ($keys, $file) {
            $this->line("    <fg=white;options=bold>{$file}:</>");

            $this->displayKeysWithLines($keys, 'Missing key');
        });

        $this->newLine();
    }

    /**
     * Display extra keys error.
     */
    protected function displayExtraKeys(array $data): void
    {
        $this->line('  <fg=magenta>Extra keys (not in EN):</>');

        collect($data)->each(function ($keys, $file) {
            $this->line("    <fg=white;options=bold>{$file}:</>");

            $this->displayKeysWithLines($keys, 'Extra key');
        });

        $this->newLine();
    }

    /**
     * Display keys with their line numbers.
     */
    protected function displayKeysWithLines(array $keys, string $label): void
    {
        $keysList = collect($keys);

        $displayed = 0;

        $keysList->each(function ($line, $key) use ($label, &$displayed, $keysList) {
            if ($displayed >= self::MAX_DISPLAY_ITEMS) {
                if ($displayed === self::MAX_DISPLAY_ITEMS) {
                    $remaining = $keysList->count() - self::MAX_DISPLAY_ITEMS;

                    $this->line("      <fg=gray>... and {$remaining} more</>");
                }

                return false;
            }

            if (is_int($key)) {
                $this->line("      <fg=yellow>⚠</> {$label}: {$line}");
            } else {
                $lineInfo = $line ? "<fg=cyan>Line {$line}:</>" : '<fg=cyan>Line ?:</>';

                $this->line("      {$lineInfo} {$label} '{$key}'");
            }

            $displayed++;
        });
    }

    /**
     * Display structure issues error.
     */
    protected function displayStructureIssues(array $data): void
    {
        $this->line('  <fg=yellow>Structure differs from EN:</>');

        collect($data)->each(function ($mismatches, $file) {
            $this->line("    <fg=white;options=bold>{$file}:</>");

            collect($mismatches)->each(function ($mismatch) {
                if (isset($mismatch['line'])) {
                    $this->line("      <fg=cyan>Line {$mismatch['line']}:</> {$mismatch['message']}");

                    collect(['en_structure', 'locale_structure', 'en_content', 'locale_content'])
                        ->each(function ($field) use ($mismatch) {
                            if (isset($mismatch[$field])) {
                                $label = Str::startsWith($field, 'en_') ? 'EN' : 'Locale';

                                $color = Str::startsWith($field, 'en_') ? 'green' : 'red';

                                $this->line("        <fg={$color}>{$label}:</> {$mismatch[$field]}");
                            }
                        });
                } else {
                    $this->line("      <fg=yellow>⚠</> {$mismatch['message']}");

                    if ($mismatch['type'] === 'line_count') {
                        $this->newLine();
                    }
                }
            });
        });

        $this->newLine();
    }

    /**
     * Check if locale file has structure mismatch with EN file.
     *
     * Compares line-by-line structure (keys position, indentation, blank lines).
     */
    protected function getStructureMismatches(string $enFile, string $localeFile): array
    {
        $enLines = collect(File::lines($enFile)->toArray());

        $locLines = collect(File::lines($localeFile)->toArray());

        $mismatches = [];

        $enLineCount = $enLines->count();

        $locLineCount = $locLines->count();

        // Different number of lines means structure mismatch
        if ($enLineCount !== $locLineCount) {
            $mismatches[] = [
                'type' => 'line_count',
                'message' => "Line count differs: EN has {$enLineCount} lines, locale has {$locLineCount} lines",
            ];
        }

        // Check line-by-line for structure differences
        $maxLines = max($enLineCount, $locLineCount);

        $detailedMismatches = collect();

        for ($lineNum = 0; $lineNum < $maxLines; $lineNum++) {
            $enLine = $enLines->get($lineNum);

            $locLine = $locLines->get($lineNum);

            $displayLineNum = $lineNum + 1;

            if ($enLine === null) {
                $detailedMismatches->push([
                    'line' => $displayLineNum,
                    'type' => 'extra_line',
                    'message' => 'Extra line in locale',
                    'locale_content' => $this->truncateLine($locLine),
                ]);

                continue;
            }

            if ($locLine === null) {
                $detailedMismatches->push([
                    'line' => $displayLineNum,
                    'type' => 'missing_line',
                    'message' => 'Missing line in locale',
                    'en_content' => $this->truncateLine($enLine),
                ]);

                continue;
            }

            $enStructure = $this->extractLineStructure($enLine);

            $locStructure = $this->extractLineStructure($locLine);

            if ($enStructure !== $locStructure) {
                $detailedMismatches->push([
                    'line' => $displayLineNum,
                    'type' => 'structure_diff',
                    'message' => $this->describeStructureDifference($enStructure, $locStructure, $enLine, $locLine),
                    'en_structure' => $this->truncateLine($enStructure),
                    'locale_structure' => $this->truncateLine($locStructure),
                ]);
            }
        }

        if ($detailedMismatches->isNotEmpty()) {
            $mismatches = array_merge($mismatches, $detailedMismatches->take(self::MAX_DISPLAY_ITEMS)->all());

            if ($detailedMismatches->count() > self::MAX_DISPLAY_ITEMS) {
                $remaining = $detailedMismatches->count() - self::MAX_DISPLAY_ITEMS;

                $mismatches[] = [
                    'type' => 'truncated',
                    'message' => "... and {$remaining} more structure differences",
                ];
            }
        }

        return $mismatches;
    }

    /**
     * Describe what kind of structure difference exists between two lines.
     */
    protected function describeStructureDifference(string $enStruct, string $locStruct, string $enLine, string $locLine): string
    {
        // Check for key difference
        $enKey = $this->extractKey($enLine);

        $locKey = $this->extractKey($locLine);

        if ($enKey !== null && $locKey !== null && $enKey !== $locKey) {
            return "Key mismatch: EN has '{$enKey}', locale has '{$locKey}'";
        }

        // Check for indentation difference
        $enIndent = Str::length($enLine) - Str::length(ltrim($enLine));

        $locIndent = Str::length($locLine) - Str::length(ltrim($locLine));

        if ($enIndent !== $locIndent) {
            return "Indentation mismatch: EN has {$enIndent} spaces, locale has {$locIndent} spaces";
        }

        // Check for different line types
        $enTrimmed = trim($enLine);

        $locTrimmed = trim($locLine);

        if ($enTrimmed === '' && $locTrimmed !== '') {
            return 'EN has blank line, locale has content';
        }

        if ($enTrimmed !== '' && $locTrimmed === '') {
            return 'EN has content, locale has blank line';
        }

        return 'Structure pattern differs';
    }

    /**
     * Extract key from a translation line.
     */
    protected function extractKey(string $line): ?string
    {
        if (preg_match('/[\'"]([^\'"]+)[\'"]\s*=>/', $line, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Truncate a line for display.
     */
    protected function truncateLine(string $line, int $maxLen = 60): string
    {
        $line = trim($line);

        return Str::length($line) > $maxLen
            ? Str::substr($line, 0, $maxLen - 3).'...'
            : $line;
    }

    /**
     * Extract the structural part of a line (indentation, key, array markers).
     */
    protected function extractLineStructure(string $line): string
    {
        $trimmed = trim($line);

        // Preserve blank lines and comments as-is
        if ($trimmed === '' || Str::startsWith($trimmed, ['//', '/*', '*'])) {
            return $line;
        }

        // For lines with => (key-value pairs), extract just the key part and indentation
        if (preg_match('/^(\s*)([\'"][^\'"]+[\'"])\s*=>\s*/', $line, $matches)) {
            return $matches[1].$matches[2].' =>';
        }

        // For array opening/closing brackets and other structural elements, return as-is
        if (preg_match('/^(\s*)([\[\],\];]+)/', $line, $matches)) {
            return $line;
        }

        // For 'return [' or similar
        if (Str::contains($line, 'return')) {
            return $line;
        }

        // For <?php tag
        if (Str::contains($line, '<?php')) {
            return $line;
        }

        return $line;
    }

    /**
     * Flatten nested arrays into dot notation keys.
     */
    protected function flattenArray(array $array, string $prefix = ''): array
    {
        return collect($array)->flatMap(function ($value, $key) use ($prefix) {
            $fullKey = $prefix === '' ? (string) $key : "{$prefix}.{$key}";

            return is_array($value)
                ? $this->flattenArray($value, $fullKey)
                : [$fullKey => true];
        })->all();
    }

    /**
     * Flatten array keys with their line numbers from the source file.
     */
    protected function flattenArrayWithLines(string $file): array
    {
        $lines = collect(explode("\n", File::get($file)));

        $result = [];

        $keyStack = [];

        $lines->each(function ($line, $lineNum) use (&$result, &$keyStack) {
            $displayLine = $lineNum + 1;

            // Match array key definitions like 'key' => or "key" =>
            if (preg_match('/^(\s*)[\'"]([^\'"]+)[\'"]\s*=>/', $line, $matches)) {
                $indent = Str::length($matches[1]);

                $key = $matches[2];

                $indentLevel = (int) ($indent / 4);

                // Adjust key stack based on indent level
                while (count($keyStack) > $indentLevel) {
                    array_pop($keyStack);
                }

                $keyStack[$indentLevel] = $key;

                // Check if this is a leaf node (has a value, not an array)
                if (! preg_match('/=>\s*\[/', $line)) {
                    $fullKey = implode('.', array_slice($keyStack, 0, $indentLevel + 1));

                    $result[$fullKey] = $displayLine;
                }
            }
        });

        return $result;
    }

    /**
     * Parse a PHP translation file and extract the array.
     */
    protected function parseTranslationFile(string $file): array
    {
        ob_start();

        try {
            $result = include $file;
        } catch (Throwable $e) {
            ob_end_clean();

            throw new RuntimeException("Failed to include file: {$file} - ".$e->getMessage());
        }

        ob_end_clean();

        if (! is_array($result)) {
            throw new RuntimeException("Translation file does not return an array: {$file}");
        }

        return $result;
    }

    /**
     * Get all PHP files in a directory recursively.
     */
    protected function getPhpFiles(string $dir): Collection
    {
        if (! File::isDirectory($dir)) {
            return collect();
        }

        return collect(File::allFiles($dir))
            ->filter(fn ($file) => $file->getExtension() === 'php')
            ->map(fn ($file) => $file->getPathname())
            ->sort()
            ->values();
    }
}
