<?php

namespace Webkul\SocialLogin\Repositories;

use Illuminate\Container\Container;
use Laravel\Socialite\Contracts\User;
use Webkul\Core\Eloquent\Repository;
use Webkul\Customer\Contracts\Customer;
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
        parent::__construct($container);
    }

    /**
     * Specify Model class name.
     */
    public function model(): string
    {
        return 'Webkul\SocialLogin\Contracts\CustomerSocialAccount';
    }

    /**
     * Find the current channel's customer linked to a social identity, or create one for it; null when its
     * email already belongs to a customer, who must sign in with their password rather than be taken over.
     *
     * @param  User  $providerUser
     * @param  string  $provider
     */
    public function findOrCreateCustomer($providerUser, $provider): ?Customer
    {
        $channelId = core()->getCurrentChannel()->id;

        $account = $this->findOneWhere([
            'provider_name' => $provider,
            'provider_id' => $providerUser->getId(),
        ]);

        if ($account) {
            return $this->belongsToChannel($account->customer, $channelId) ? $account->customer : null;
        }

        if (
            $providerUser->getEmail()
            && $this->emailIsTaken($providerUser->getEmail(), $channelId)
        ) {
            return null;
        }

        $names = $this->getFirstLastName($providerUser->getName());

        $customer = $this->customerRepository->create([
            'email' => $providerUser->getEmail(),
            'first_name' => $names['first_name'],
            'last_name' => $names['last_name'],
            'status' => 1,
            'is_verified' => ! core()->getConfigData('customer.settings.email.verification'),
            'channel_id' => $channelId,
            'customer_group_id' => $this->customerGroupRepository->findOneWhere(['code' => 'general'])->id,
        ]);

        $this->create([
            'customer_id' => $customer->id,
            'provider_id' => $providerUser->getId(),
            'provider_name' => $provider,
        ]);

        return $customer;
    }

    /**
     * Returns first and last name from name.
     *
     * @param  string  $name
     * @return array
     */
    public function getFirstLastName($name)
    {
        $name = trim($name);

        $lastName = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);

        $firstName = trim(preg_replace('#'.$lastName.'#', '', $name));

        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
        ];
    }

    /**
     * Whether a customer belongs to the given channel, or to none as those created before customers had one.
     */
    protected function belongsToChannel(?Customer $customer, int $channelId): bool
    {
        return $customer
            && (
                is_null($customer->channel_id)
                || (int) $customer->channel_id === $channelId
            );
    }

    /**
     * Whether a customer of the given channel, or of none, already holds the email.
     */
    protected function emailIsTaken(string $email, int $channelId): bool
    {
        return $this->customerRepository
            ->where('email', $email)
            ->where(fn ($query) => $query->where('channel_id', $channelId)->orWhereNull('channel_id'))
            ->exists();
    }
}
