<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bagisto:generate {products},{nos?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attempts to generate random products available in the system';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment('v' . config('app.version'));
    }
}
