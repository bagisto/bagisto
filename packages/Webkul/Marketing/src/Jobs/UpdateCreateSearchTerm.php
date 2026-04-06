<?php

namespace Webkul\Marketing\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Webkul\Marketing\Repositories\SearchTermRepository;

class UpdateCreateSearchTerm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param  array  $data
     * @return void
     */
    public function __construct(protected $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SearchTermRepository $searchTermRepository)
    {
        $searchTerm = $searchTermRepository->findOneWhere([
            'term' => $this->data['term'],
            'channel_id' => $this->data['channel_id'],
            'locale' => $this->data['locale'],
        ]);

        if ($searchTerm) {
            $searchTerm->update([
                'uses' => DB::raw('uses + 1'),
                'results' => $this->data['results'],
            ]);
        } else {
            $searchTermRepository->create([
                'term' => $this->data['term'],
                'channel_id' => $this->data['channel_id'],
                'locale' => $this->data['locale'],
                'uses' => 1,
                'results' => $this->data['results'],
            ]);
        }
    }
}
