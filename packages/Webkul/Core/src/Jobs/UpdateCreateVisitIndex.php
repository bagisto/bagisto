<?php
 
namespace Webkul\Core\Jobs;
 
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Shetabit\Visitor\Models\Visit;
 
class UpdateCreateVisitIndex implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
 
    /**
     * Create a new job instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  array  $log
     * @param  boolean  $cacheHit
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
        if (null !== $this->model && method_exists($this->model, 'visitLogs')) {
            $this->model->visitLogs()->updateOrCreate(
                Arr::only($this->log, ['method', 'url', 'ip', 'visitor_id', 'visitor_type']),
                Arr::only($this->log, ['request', 'headers', 'referer', 'useragent', 'device', 'platform', 'browser', 'languages'])
            );
        } else {
            Visit::updateOrCreate(
                Arr::only($this->log, ['method', 'url', 'ip', 'visitor_id', 'visitor_type']),
                Arr::only($this->log, ['request', 'headers', 'referer', 'useragent', 'device', 'platform', 'browser', 'languages'])
            );
        }
    }
}