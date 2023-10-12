<?php

namespace Webkul\Installer\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    /**
     * ENV File Setup
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function envFileSetup(Request $request): JsonResponse
    {
        $message = $this->EnvironmentManager->saveFileClassic($request);

        return new JsonResponse([
            'data' => $message,
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

    /**
     * Admin Configuration Setup
     *
     * @return void
     */
    public function adminConfigSetup()
    {
        $admin = DB::table('admins')->find(1);

        $password = password_hash(request()->input('password'), PASSWORD_BCRYPT, ['cost' => 10]);

        $data = [
            'name'     => request()->input('admin'),
            'email'    => request()->input('email'),
            'password' => $password,
            'role_id'  => 1,
            'status'   => 1,
        ];

        try {
            if ($admin) {
                DB::table('admins')->where('id', 1)->update($data);
            } else {
                DB::table('admins')->insert($data);
            }
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    /**
     * SMTP connection setup for Mail
     *
     * @return void
     */
    public function smtpConfigSetup()
    {
        $this->EnvironmentManager->saveFileWizard(request()->input());
    }
}
