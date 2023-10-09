<?php
 
namespace Webkul\Core\Jobs;
 
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use Webkul\Core\Repositories\VisitRepository;
 
class UpdateCreateVisitIndex implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
 
    /**
     * Create a new job instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  array  $log
     * @return void
     */
    public function __construct(
        protected $model,
        protected $log
    )
    {
    }
 
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $visitRepository = app(VisitRepository::class);

        $lastVisit = $visitRepository->where(Arr::only($this->log, [
            'method',
            'url',
            'ip',
            'visitor_id',
            'visitor_type',
        ]))->latest()->first();

        if ($lastVisit?->created_at->isToday()) {
            return;
        }

        if (null !== $this->model && method_exists($this->model, 'visitLogs')) {
            $this->model->visitLogs()->create($this->log);
        } else {
            $visitRepository->create($this->log);
        }
    }
}