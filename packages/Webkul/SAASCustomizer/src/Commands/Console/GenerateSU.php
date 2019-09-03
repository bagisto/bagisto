<?php

namespace Webkul\SAASCustomizer\Commands\Console;

use Illuminate\Console\Command;

use Webkul\SAASCustomizer\Repositories\CompanyRepository;
use Webkul\SAASCustomizer\Repositories\CompanyDetailsRepository;
use Webkul\User\Repositories\AdminRepository as Admin;
use Webkul\User\Repositories\RoleRepository as Role;
use Company;
use Validator;

class GenerateSU extends Command
{

    /**
     * Holds the execution signature of the command needed
     * to be executed for generating super user
     */
    protected $signature = 'saas:install';

    /**
     * Will inhibit the description related to this
     * command's role
     */
    protected $description = 'Generates only one super user for the system';

    public function __construct(CompanyRepository $company, CompanyDetailsRepository $details, Admin $admin, Role $role)
    {
        parent::__construct();

        $this->company = $company;
        $this->details = $details;
        $this->admin = $admin;
        $this->role = $role;
    }

    /**
     * Does the all sought of lifting required to be performed for
     * generating a super user
     */
    public function handle()
    {
        $this->comment('Generating super user for the system....');

        $email = $this->ask('Please enter email?');

        $data = [
            'email' => $email
        ];

        $validator = Validator::make($data, [
            'email' => 'required|email',
        ]);

        if($validator->fails()) {
            $this->comment('Email invalid, please enter try again.');

            return false;
        }

        unset($data);

        $this->comment('You entered = '. $email);

        $password = $this->ask('Please enter password?');
        $data = [
            'password' => $password
        ];

        $validator = Validator::make($data, [
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            $this->comment('Password invalid, make sure password is atleast 6 characters of length.');

            return false;
        }

        $this->comment('You entered = '. $password);

        unset($data);

        if ($this->confirm('Do you wish to continue?')) {
            $result = $this->generateSuperUserCompany($email, $password);

            if ($result) {
                $this->comment('Super user for the system is created');
            } else {
                $this->comment('Super user for the system already exists, please contact support@bagisto.com for troubleshooting.');
            }

        } else {
            $this->comment('Please try creating the super user again');
        }
    }

    public function generateSuperUserCompany($email, $password)
    {
        $super_admin = \DB::select('select * from super_admins');

        if(count($super_admin)) {
            return false;
        }

        $data = [
            'email' => $email,
            'password' => bcrypt($password),
        ];

        \DB::insert('insert into super_admins (id, email, password, created_at, updated_at) values (?, ?, ?, ?, ?)', [1, $data['email'], $data['password'], now(), now()]);

        return true;
    }
}