<?php

namespace Brainstream\Giftcard\Repositories;

use Illuminate\Support\Facades\Event;
use Brainstream\Giftcard\Models\GiftCard;
use Brainstream\Giftcard\Models\GiftCardBalance;
use Webkul\Core\Eloquent\Repository;

class GiftCardRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return GiftCard::class;
    }

    /**
     * Create.
     *
     * @return mixed
     */
    public function create(array $attributes)
    {
        Event::dispatch('core.giftcard.create.before');

        $giftcard = parent::create($attributes);

        Event::dispatch('core.giftcard.create.after', $giftcard);

        return $giftcard;
    }

    /**
     * Update.
     *
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        Event::dispatch('core.giftcard.update.before', $id);

        $giftcard = parent::update($attributes, $id);

        Event::dispatch('core.giftcard.update.after', $giftcard);

        return $giftcard;
    }

    /**
     * Delete.
     *
     * @param  int  $id
     * @return bool
     */
    public function delete($id)
    {
        Event::dispatch('core.giftcard.delete.before', $id);

        if ($this->model->count() == 1) {
            return false;
        }

        if ($this->model->destroy($id)) {
            Event::dispatch('core.giftcard.delete.after', $id);

            return true;
        }

        return false;
    }

    public function findByCode($code)
    {
        return $this->model->where('giftcard_number', $code)->first();
    }


}
