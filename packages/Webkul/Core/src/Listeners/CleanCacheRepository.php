<?php

namespace Webkul\Core\Listeners;

use Illuminate\Support\Facades\Log;
use Prettus\Repository\Events\RepositoryEventBase;
use Prettus\Repository\Listeners\CleanCacheRepository as BaseCleanCacheRepository;
use Webkul\Core\Helpers\CacheKeys;

class CleanCacheRepository extends BaseCleanCacheRepository
{
    /**
     * Forget everything the written repository has cached, then stop tracking those keys.
     * Upstream leaves them recorded, so every later write forgets the same dead keys again.
     */
    public function handle(RepositoryEventBase $event)
    {
        try {
            $this->repository = $event->getRepository();

            $cleanEnabled = $this->repository->allowedClean();

            if ($cleanEnabled) {
                $this->model = $event->getModel();
                $this->action = $event->getAction();

                $className = get_class($this->repository);

                if (config("repository.cache.repositories.{$className}.clean.on.{$this->action}", config("repository.cache.clean.on.{$this->action}", true))) {
                    $cacheKeys = CacheKeys::getKeys($className);

                    if (is_array($cacheKeys)) {
                        foreach ($cacheKeys as $key) {
                            $this->cache->forget($key);
                        }
                    }

                    CacheKeys::forgetGroup($className);
                }
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
