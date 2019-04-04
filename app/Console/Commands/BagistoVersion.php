<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BagistoVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bagisto:version';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Displays version of Bagisto installed';

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
