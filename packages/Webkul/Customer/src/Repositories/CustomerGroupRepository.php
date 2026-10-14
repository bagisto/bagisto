<?php

namespace Webkul\Customer\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Customer\Contracts\CustomerGroup;

class CustomerGroupRepository extends Repository
{
    /**
     * The guest group, once it has been read.
     *
     * @var CustomerGroup|null
     */
    protected $guestGroup;

    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return CustomerGroup::class;
    }

    /**
     * Get the group every shopper who is not logged in belongs to.
     */
    public function getGuestGroup(): ?CustomerGroup
    {
        if ($this->guestGroup) {
            return $this->guestGroup;
        }

        return $this->guestGroup = $this->findOneByField('code', 'guest');
    }
}
