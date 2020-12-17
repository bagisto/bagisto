<?php

namespace Webkul\Communication\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Webkul\Core\Models\SubscribersList;
use Webkul\Communication\Mail\Newsletter;
use Webkul\Communication\Repositories\NewsletterQueueRepository;

class SendNewsletter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will send your all newsletter which are in queue.';

    /**
     * Newsletter queue repository.
     *
     * @var Webkul\Communication\Repositories\NewsletterQueueRepository
     */
    protected $newsletterQueueRepository;

    /**
     * Create a new command instance.
     *
     * @param  Webkul\Communication\Repositories\NewsletterTemplateRepository $newsletterTemplateRepository
     * @return void
     */
    public function __construct(
        NewsletterQueueRepository $newsletterQueueRepository
    )
    {
        parent::__construct();

        $this->newsletterQueueRepository = $newsletterQueueRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /*
            just given time field in advance so that in future
            if time requirement is needed for specific newsletter
            then static value can be dynamic
        */
        $now = now()->toDateString() . ' ' . '00:00:00';

        $newsletters = $this->newsletterQueueRepository->where('queue_datetime', $now)->get();

        if (! $newsletters->isEmpty()) {
            $newsletters->each(function ($newsletter) {
                if (! ((Boolean) $newsletter->is_delivered)) {
                    $subscribers = SubscribersList::where('is_subscribed', 1)->get();

                    $subscribers->each(function ($subscriber) use ($newsletter) {
                        Mail::queue(new Newsletter($newsletter, $subscriber));
                    });
                    
                    $newsletter->is_delivered = 1;
                    $newsletter->save();
                }
            });
        }
    }
}
