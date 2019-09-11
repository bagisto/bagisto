<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Product\Repositories\ProductRepository as Product;

class GenerateProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bagisto:generate {value}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates product with random attribute values.';

    /**
     * ProductRepository instance
     */
    protected $product;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Product $product)
    {
        parent::__construct();

        $this->product = $product;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->argument('value') == 'products') {
            $this->comment('Under development.');
        } else {
            $this->line('Sorry, generate option is either invalid or does not exist.');
        }
    }
}
