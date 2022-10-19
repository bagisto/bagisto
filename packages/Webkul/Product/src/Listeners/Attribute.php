<?php

namespace Webkul\Product\Listeners;

use Webkul\Product\Helpers\Indexers\Flat\Attribute as FlatIndexer;

class Attribute
{
    /**
     * Create a new listener instance.
     *
     * @param  \Webkul\Product\Helpers\Indexers\Flat\Attribute  $flatIndexer
     * @return void
     */
    public function __construct(protected FlatIndexer $flatIndexer)
    {
    }

    /**
     * After the attribute is created
     *
     * @param  \Webkul\Attribute\Contracts\Attribute  $attribute
     * @return void
     */
    public function afterCreate($attribute)
    {
        $this->flatIndexer->removeOrCreateColumn($attribute);
    }

    /**
     * After the attribute is created
     *
     * @param  \Webkul\Attribute\Contracts\Attribute  $attribute
     * @return void
     */
    public function afterUpdate($attribute)
    {
        $this->flatIndexer->removeOrCreateColumn($attribute);
    }

    /**
     * Before the attribute is removed
     *
     * @param  \Webkul\Attribute\Contracts\Attribute  $attribute
     * @return void
     */
    public function beforeRemove($attribute)
    {
        $this->flatIndexer->removeColumn($attribute);
    }
}
