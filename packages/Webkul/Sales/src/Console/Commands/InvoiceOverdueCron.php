<?php

namespace Webkul\Sales\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Sales\Contracts\Invoice;
use Webkul\Sales\Models\InvoiceProxy;

class InvoiceOverdueCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Invoice overdue reminders';

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
        InvoiceProxy::inOverdueAndRemindersLimit()
            ->get()
            ->each(function (Invoice $invoice) {
                $invoice->sendInvoiceReminder();
            });
    }
}
