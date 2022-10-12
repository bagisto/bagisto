<?php

namespace Webkul\Faker\Commands\Console;

use Illuminate\Console\Command;
use Webkul\Faker\Helpers\Faker as FakerHelper;

class Faker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bagisto:fake';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates fake records for testing';

    /**
     * Create a new command instance.
     *
     * @param  \Webkul\Faker\Helpers\Faker  $fakerHelper
     * @return void
     */
    public function __construct(protected FakerHelper $fakerHelper)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->fakerHelper->fake();
    }
}