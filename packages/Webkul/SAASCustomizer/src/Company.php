<?php

namespace Webkul\SAASCustomizer;

use Illuminate\Support\Facades\Config;

use Webkul\SAASCustomizer\Repositories\CompanyRepository;
use Webkul\SAASCustomizer\Models\SuperAdmin;
use Carbon\Carbon;

class Company
{
    /**
     * To hold the instance of currently active company
     */
    protected $company;

    /**
     * Holds the currently request server name variable
     */
    protected $domain;

    /**
     * To hold the super admin instance
     */
    protected $superAdmin;

    public function __construct(CompanyRepository $company)
    {
        $this->company = $company;
    }

    public function isAllowed()
    {
        $primaryServerName = config('app.url');

        if (isset($_SERVER['SERVER_NAME']))
            $currentURL = $_SERVER['SERVER_NAME'];
        else
            $currentURL = $primaryServerName;

        $primaryServerNameWithoutProtocol = null;

        if (str_contains($primaryServerName, 'http://')) {
            $primaryServerNameWithoutProtocol = explode('http://', $primaryServerName)[1];
        } else if (str_contains($primaryServerName, 'https://')) {
            $primaryServerNameWithoutProtocol = explode('https://', $primaryServerName)[1];
        }

        if ($currentURL == $primaryServerNameWithoutProtocol) {
            return true;
        } else {
            return false;
        }
    }

    protected function getAllRegisteredDomains()
    {
        $domains = $this->company->all();

        return $domains;
    }

    public function getCurrent()
    {
        $superAdmin = new SuperAdmin;

        $primaryServerName = config('app.url');

        if (isset($_SERVER['SERVER_NAME']))
            $currentURL = $_SERVER['SERVER_NAME'];
        else
            $currentURL = $primaryServerName;

        if (str_contains($primaryServerName, 'http://')) {
            $primaryServerNameWithoutProtocol = explode('http://', $primaryServerName)[1];
        } else if (str_contains($primaryServerName, 'https://')) {
            $primaryServerNameWithoutProtocol = explode('https://', $primaryServerName)[1];
        }

        if (str_contains($currentURL, 'http://')) {
            $currentURL = explode('http://', $currentURL)[1];
        } else if (str_contains($currentURL, 'http://')) {
            $currentURL = explode('http://', $currentURL)[1];
        }

        if ($currentURL == $primaryServerNameWithoutProtocol) {
            if (session()->has('company')) {
                $company = session()->get('company');
            } else {
                $company = 'super-company';

                session()->put('company', $company);
            }

            return $company;
        } else {
            if (session()->has('company')) {
                $company = session()->get('company');
            }else {
                $company = $this->company->findWhere(['domain' => $currentURL])->first();

                if ($company->is_active == 0) {
                    throw new \Exception('company_blocked_by_administrator', 400);
                }

                session()->put('company', $company);
            }

            return $company;
        }
    }

    /**
     * Returns if there are companies
     * already created
     */
    public function count()
    {
        return $this->company->findWhere([['id', '>', '0']])->count();
    }

    public function getPrimaryUrl()
    {
        if (str_contains(env('APP_URL'), 'http://')) {
            $primaryServerNameWithoutProtocol = explode('http://', env('APP_URL'))[1];
        } else if (str_contains($primaryServerName, 'https://')) {
            $primaryServerNameWithoutProtocol = explode('https://', env('APP_URL'))[1];
        }

        return $primaryServerNameWithoutProtocol;
    }
}