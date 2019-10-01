<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Product\Helpers\GenerateProduct;

class GenerateProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bagisto:generate {value} {quantity}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates product with random attribute values.';

    /**
     * GenerateProduct instance
     */
    protected $generateProduct;

    /**
     * ProductRepository instance
     */
    protected $product;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(GenerateProduct $generateProduct)
    {
        parent::__construct();

        $this->generateProduct = $generateProduct;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {        
        if (! is_string($this->argument('value')) || ! is_numeric($this->argument('quantity'))) {
            $this->info('Illegal parameters or value of parameters are passed');
        } else {
            if (strtolower($this->argument('value')) == 'product'  || strtolower($this->argument('value')) == 'products') {
                $quantity = intval($this->argument('quantity'));

                while ($quantity > 0) {
                    try {
                        $result = $this->generateProduct->create();
                    } catch (\Exception $e) {
                        continue;
                    }
                    
                    $quantity--;
                } 
    
                if ($result)
                    $this->info('Product(s) created successfully.');
                else
                    $this->info('Product(s) cannot be created successfully.');
            } else {
                $this->line('Sorry, this generate option is invalid.');
            }
        }
    }
}
