<?php

namespace Webkul\FPC\Listeners;

use Webkul\FPC\Concerns\ForgetsPages;
use Webkul\Marketing\Repositories\URLRewriteRepository;

class URLRewrite
{
    use ForgetsPages;

    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected URLRewriteRepository $urlRewriteRepository) {}

    /**
     * After URL rewrite update.
     *
     * @param  \Webkul\Marketing\Contracts\URLRewrite  $urlRewrite
     * @return void
     */
    public function afterUpdate($urlRewrite)
    {
        $this->forgetPages(['/'.$urlRewrite->request_path]);
    }

    /**
     * Before URL rewrite delete.
     *
     * @param  int  $urlRewriteId
     * @return void
     */
    public function beforeDelete($urlRewriteId)
    {
        $urlRewrite = $this->urlRewriteRepository->find($urlRewriteId);

        if (! $urlRewrite) {
            return;
        }

        $this->forgetPages(['/'.$urlRewrite->request_path]);
    }
}
