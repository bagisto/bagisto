<?php

namespace Webkul\Core\Listeners;

use Illuminate\Support\Facades\Log;
use Prettus\Repository\Events\RepositoryEventBase;
use Prettus\Repository\Listeners\CleanCacheRepository as BaseCleanCacheRepository;
use Webkul\Core\Helpers\CacheGeneration;

class CleanCacheRepository extends BaseCleanCacheRepository
{
    /**
     * Move the written repository on to a new cache generation, so every read it had
     * cached is unreachable from here on.
     */
    public function handle(RepositoryEventBase $event)
    {
        try {
            $this->repository = $event->getRepository();

            if (! $this->repository->allowedClean()) {
                return;
            }

            $this->model = $event->getModel();

            $this->action = $event->getAction();

            $className = get_class($this->repository);

            if (config("repository.cache.repositories.{$className}.clean.on.{$this->action}", config("repository.cache.clean.on.{$this->action}", true))) {
                CacheGeneration::bump($className);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
