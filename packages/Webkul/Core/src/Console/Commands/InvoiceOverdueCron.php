<?php

namespace Webkul\Core\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Sales\Models\Invoice;

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
    protected $description = 'Invoice reminders';

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
        // Get 'overdue' invoices
        Invoice::inOverdueAndRemindersLimit()
            ->get()
            ->each(function (Invoice $invoice) {
                $invoice->sendInvoiceReminder();
            });
    }
}
