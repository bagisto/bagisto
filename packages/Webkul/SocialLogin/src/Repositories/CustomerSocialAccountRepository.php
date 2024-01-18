<?php

namespace Webkul\SocialLogin\Repositories;

use Illuminate\Container\Container;
use Webkul\Core\Eloquent\Repository;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\Customer\Repositories\CustomerRepository;

class CustomerSocialAccountRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected CustomerGroupRepository $customerGroupRepository,
        Container $container
    ) {
        $this->_config = request('_config');

        parent::__construct($container);
    }

    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Webkul\SocialLogin\Contracts\CustomerSocialAccount';
    }

    /**
     * @param  array  $providerUser
     * @param  string  $provider
     * @return void
     */
    public function findOrCreateCustomer($providerUser, $provider)
    {
        $account = $this->findOneWhere([
            'provider_name' => $provider,
            'provider_id'   => $providerUser->getId(),
        ]);

        if ($account) {
            return $account->customer;
        } else {
            $customer = $providerUser->getEmail() ? $this->customerRepository->findOneByField('email', $providerUser->getEmail()) : null;

            if (! $customer) {
                $names = $this->getFirstLastName($providerUser->getName());

                $customer = $this->customerRepository->create([
                    'email'             => $providerUser->getEmail(),
                    'first_name'        => $names['first_name'],
                    'last_name'         => $names['last_name'],
                    'status'            => 1,
                    'is_verified'       => ! core()->getConfigData('customer.settings.email.verification'),
                    'customer_group_id' => $this->customerGroupRepository->findOneWhere(['code' => 'general'])->id,
                ]);
            }

            $this->create([
                'customer_id'   => $customer->id,
                'provider_id'   => $providerUser->getId(),
                'provider_name' => $provider,
            ]);

            return $customer;
        }
    }

    /**
     * Returns first and last name from name
     *
     * @param  string  $name
     * @return string
     */
    public function getFirstLastName($name)
    {
        $name = trim($name);

        $lastName = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);

        $firstName = trim(preg_replace('#'.$lastName.'#', '', $name));

        return [
            'first_name' => $firstName,
            'last_name'  => $lastName,
        ];
    }
}
