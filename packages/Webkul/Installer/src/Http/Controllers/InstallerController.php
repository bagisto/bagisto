<?php

namespace Webkul\Installer\Http\Controllers;

use Illuminate\Http\Request;
use Webkul\Installer\Helpers\DatabaseManager;
use Webkul\Installer\Helpers\EnvironmentManager;
use Webkul\Installer\Helpers\ServerRequirements;

class InstallerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected ServerRequirements $serverRequirements,
        protected EnvironmentManager $environmentManager,
        protected DatabaseManager $databaseManager,
    ) {}

    /**
     * Display the installer welcome page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $phpVersion = $this->serverRequirements->checkPHPversion();

        $requirements = $this->serverRequirements->validate();

        if (request()->has('locale')) {
            return redirect()->route('installer.index');
        }

        return view('installer::installer.index', compact('requirements', 'phpVersion'));
    }

    /**
     * Run migration.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function runMigration(Request $request)
    {
        $this->environmentManager->generateEnv($request->all());

        $this->environmentManager->loadEnvConfigs();

        $isDatabaseConnected = $this->databaseManager->checkDatabaseConnection();

        if (! $isDatabaseConnected) {
            return response()->json([
                'migrated' => false,
            ], 500);
        }

        return $this->databaseManager->migrateFresh()
            ? response()->json(['migrated' => true])
            : response()->json(['migrated' => false], 500);
    }

    /**
     * Run seeder.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function runSeeder()
    {
        $selectedParameters = request('selectedParameters');

        $allParameters = request('allParameters');

        $appLocale = $allParameters['app_locale'] ?? null;

        $appCurrency = $allParameters['app_currency'] ?? null;

        $allowedLocales = array_unique(
            array_merge(
                [($appLocale ?? 'en')],
                $selectedParameters['allowed_locales']
            )
        );

        $allowedCurrencies = array_unique(
            array_merge(
                [($appCurrency ?? 'USD')],
                $selectedParameters['allowed_currencies']
            )
        );

        $parameter = [
            'parameter' => [
                'default_locales' => $appLocale,
                'default_currency' => $appCurrency,
                'allowed_locales' => $allowedLocales,
                'allowed_currencies' => $allowedCurrencies,
                'skip_admin_creation' => true,
            ],
        ];

        $isEnvVariablesUpdated = $this->environmentManager->updateEnvVariables($allParameters);

        if ($isEnvVariablesUpdated) {
            $isSeeded = $this->databaseManager->seed($parameter);

            $this->environmentManager->storageLink();

            return $isSeeded
                ? response()->json(['seeded' => true])
                : response()->json(['seeded' => false], 500);
        }

        return response()->json(['seeded' => false], 500);
    }

    /**
     * Seed sample products.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function seedSampleProducts()
    {
        $defaultLocale = config('app.locale');

        $allowedLocales = array_merge([$defaultLocale], request()->input('selectedLocales'));

        $defaultCurrency = config('app.currency');

        $allowedCurrencies = array_merge([$defaultCurrency], request()->input('selectedCurrencies'));

        $isSeeded = $this->databaseManager->seedSampleProducts([
            'default_locale' => $defaultLocale,
            'allowed_locales' => $allowedLocales,
            'default_currency' => $defaultCurrency,
            'allowed_currencies' => $allowedCurrencies,
        ]);

        return $isSeeded
            ? response()->json(['sample_products_seeded' => true])
            : response()->json(['sample_products_seeded' => false], 500);
    }

    /**
     * Create admin user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createAdminUser()
    {
        $data = request()->only(['name', 'email', 'password']);

        return $this->databaseManager->createAdminUser($data)
            ? response()->json(['admin_user_created' => true])
            : response()->json(['admin_user_created' => false], 500);
    }
}
