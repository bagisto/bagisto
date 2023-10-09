<?php

namespace Webkul\Installer\Http\Controllers;

use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;
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

    public function __construct(
        RequirementsChecker $checker,
        EnvironmentManager $environmentManager
    )
    {
        $this->requirements = $checker;
        $this->EnvironmentManager = $environmentManager;
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

    public function storeEnvironment()
    {

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