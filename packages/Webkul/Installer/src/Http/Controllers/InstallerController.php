<?php

namespace Webkul\Installer\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Webkul\Installer\Http\Helpers\DatabaseManager;
use Webkul\Installer\Http\Helpers\EnvironmentManager;
use Webkul\Installer\Http\Helpers\RequirementsChecker;

class InstallerController extends Controller
{
    /**
     * @var RequirementsChecker
     */
    protected $requirements;

    /**
     * @var EnvironmentManager
     */
    protected $EnvironmentManager;

    /**
     * @var DatabaseManager
     */
    protected $databaseManager;

    public function __construct(
        RequirementsChecker $checker,
        EnvironmentManager $environmentManager,
        DatabaseManager $databaseManager,
    ) {
        $this->requirements = $checker;
        $this->EnvironmentManager = $environmentManager;
        $this->databaseManager = $databaseManager;
    }

    /**
     * Installer View Root Page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $phpVersion = $this->requirements->checkPHPversion(
            config('installer.core.minPhpVersion')
        );

        $requirements = $this->requirements->check();

        return view('installer::installer.installer', compact('requirements', 'phpVersion'));
    }

    public function envFileSetup(Request $request): JsonResponse
    {
        $message = $this->EnvironmentManager->saveFileClassic($request);

        $this->databaseManager->getEnvironment($request->all());

        return new JsonResponse([
            'message' => $message,
        ]);
    }

    /**
     * Display the Environment page.
     *
     * @return \Illuminate\View\View
     */
    public function environmentWizard()
    {
        $envConfig = $this->EnvironmentManager->getEnvContent();

        return view('vendor.installer.environment-wizard', compact('envConfig'));
    }
}
