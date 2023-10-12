<?php

namespace Webkul\Installer\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webkul\Installer\Http\Helpers\EnvironmentManager;
use Webkul\Installer\Http\Helpers\ServerRequirements;

class InstallerController extends Controller
{
    const MIN_PHP_VERSION = '8.1.0';

    public function __construct(
        protected ServerRequirements $serverRequirements,
        protected EnvironmentManager $environmentManager,
    )
    {
    }

    /**
     * Installer View Root Page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $phpVersion = $this->serverRequirements->checkPHPversion(self::MIN_PHP_VERSION);

        $requirements = $this->serverRequirements->validate();

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
        $message = $this->environmentManager->generateEnv($request);

        return new JsonResponse([
            'data' => $message,
        ]);
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
        $this->environmentManager->setEnvConfiguration(request()->input());
    }
}
