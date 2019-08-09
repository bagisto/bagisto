<?php

namespace Webkul\Discount\Commands\Console;

use Illuminate\Console\Command;
use Webkul\Attribute\Repositories\AttributeRepository as Attribute;

class ActivateCatalogRule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bagisto:activate {param}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description;

    /**
     * To hold attribute repository instance
     */
    protected $attribute;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Attribute $attribute)
    {
        parent::__construct();

        $this->attribute = $attribute;

        $this->description = trans('admin::app.promotion.activate-catalog');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $param = $this->argument('param');

        if (isset($param) && $param == 'catalog-rule') {
            $attribute = $this->attribute->findWhere([
                'code' => 'special_price'
            ]);

            if ($attribute->count()) {
                $attribute = $attribute->first();

                if ($attribute->value_per_channel == 1) {
                    $this->info(trans('admin::app.promotion.catalog-rule-already-activated'));
                } else {
                    $attribute->update(['value_per_channel' => 1]);

                    $this->info(trans('admin::app.promotion.catalog-rule-activated'));
                }
            } else {
                $this->info(trans('admin::app.promotion.cannot-activate-catalog-rule'));
            }
        }
    }
}